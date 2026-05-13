<?php
declare(strict_types=1);
session_start();

/*
|--------------------------------------------------------------------------
| CONFIGURACION
|--------------------------------------------------------------------------
*/
const DB_HOST = '127.0.0.1';
const DB_NAME = 'universidad_db';
const DB_USER = 'root';
const DB_PASS = '';

/*
|--------------------------------------------------------------------------
| CONEXION
|--------------------------------------------------------------------------
*/
function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}

/*
|--------------------------------------------------------------------------
| HELPERS
|--------------------------------------------------------------------------
*/
function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url = 'index.php'): void
{
    header('Location: ' . $url);
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $token = $_POST['csrf_token'] ?? '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if (!hash_equals($sessionToken, $token)) {
        throw new RuntimeException('Token CSRF inválido. Recarga la página e inténtalo nuevamente.');
    }
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function require_login(): int
{
    if (!is_logged_in()) {
        throw new RuntimeException('Debes iniciar sesión.');
    }

    return (int)$_SESSION['user_id'];
}

function money(float $amount): string
{
    return 'Q' . number_format($amount, 2);
}

function uploads_dir(): string
{
    $dir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    return $dir;
}

/*
|--------------------------------------------------------------------------
| DATOS DE USUARIO
|--------------------------------------------------------------------------
*/
function find_student_by_id(int $studentId): ?array
{
    $stmt = db()->prepare(
        'SELECT a.id_alumno, a.nombre, a.correo, a.password_hash, a.estado, c.nombre AS carrera
         FROM alumnos a
         INNER JOIN carreras c ON c.id_carrera = a.id_carrera
         WHERE a.id_alumno = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $studentId]);

    $student = $stmt->fetch();
    return $student ?: null;
}

function current_student(): ?array
{
    if (!is_logged_in()) {
        return null;
    }

    $student = find_student_by_id((int)$_SESSION['user_id']);

    if (!$student) {
        session_destroy();
        return null;
    }

    return $student;
}

function authenticate_student(string $studentId, string $password): ?array
{
    $stmt = db()->prepare(
        'SELECT a.id_alumno, a.nombre, a.correo, a.password_hash, a.estado, c.nombre AS carrera
         FROM alumnos a
         INNER JOIN carreras c ON c.id_carrera = a.id_carrera
         WHERE a.id_alumno = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => (int)$studentId]);

    $student = $stmt->fetch();

    if (!$student) {
        return null;
    }

    $storedPassword = (string)$student['password_hash'];

    $isValid = password_verify($password, $storedPassword) || hash_equals($storedPassword, $password);

    if (!$isValid) {
        return null;
    }

    if (hash_equals($storedPassword, $password)) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $upd = db()->prepare('UPDATE alumnos SET password_hash = :hash WHERE id_alumno = :id');
        $upd->execute([
            'hash' => $newHash,
            'id' => $student['id_alumno'],
        ]);
    }

    return $student;
}

function login_student(int $studentId): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = $studentId;
}

function logout_student(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            (bool)$params['secure'],
            (bool)$params['httponly']
        );
    }

    session_destroy();
}

/*
|--------------------------------------------------------------------------
| CONSULTAS DEL PORTAL
|--------------------------------------------------------------------------
*/
function get_student_overview(int $studentId): array
{
    $stmt = db()->prepare(
        'SELECT 
            COALESCE((SELECT SUM(c.monto) FROM cargos c WHERE c.id_alumno = :id AND c.estado <> "anulado"), 0) AS total_cargos,
            COALESCE((SELECT SUM(p.monto) FROM pagos p WHERE p.id_alumno = :id AND p.estado = "confirmado"), 0) AS total_pagos,
            COALESCE((SELECT COUNT(*) FROM inscripciones i WHERE i.id_alumno = :id AND i.estado = "inscrito"), 0) AS cursos_activos,
            COALESCE((
                SELECT COUNT(*)
                FROM tareas t
                INNER JOIN secciones s ON s.id_seccion = t.id_seccion
                INNER JOIN inscripciones i ON i.id_seccion = s.id_seccion AND i.id_alumno = :id
                WHERE i.estado = "inscrito"
            ), 0) AS tareas_publicadas'
    );
    $stmt->execute(['id' => $studentId]);
    $data = $stmt->fetch() ?: [];

    $totalCargos = (float)($data['total_cargos'] ?? 0);
    $totalPagos = (float)($data['total_pagos'] ?? 0);

    return [
        'total_cargos' => $totalCargos,
        'total_pagos' => $totalPagos,
        'saldo_pendiente' => max($totalCargos - $totalPagos, 0),
        'cursos_activos' => (int)($data['cursos_activos'] ?? 0),
        'tareas_publicadas' => (int)($data['tareas_publicadas'] ?? 0),
    ];
}

function get_student_courses(int $studentId): array
{
    $stmt = db()->prepare(
        'SELECT 
            c.codigo,
            c.nombre,
            c.creditos,
            c.semestre_sugerido,
            s.nombre_seccion,
            s.aula,
            s.horario,
            p.nombre AS periodo,
            i.estado
         FROM inscripciones i
         INNER JOIN secciones s ON s.id_seccion = i.id_seccion
         INNER JOIN cursos c ON c.id_curso = s.id_curso
         INNER JOIN periodos_academicos p ON p.id_periodo = s.id_periodo
         WHERE i.id_alumno = :id
         ORDER BY p.nombre DESC, c.nombre ASC'
    );
    $stmt->execute(['id' => $studentId]);
    return $stmt->fetchAll();
}

function get_student_pensum(int $studentId): array
{
    $stmt = db()->prepare(
        'SELECT pc.ciclo, c.codigo, c.nombre, c.creditos, pc.obligatorio
         FROM alumnos a
         INNER JOIN pensum_carrera pc ON pc.id_carrera = a.id_carrera
         INNER JOIN cursos c ON c.id_curso = pc.id_curso
         WHERE a.id_alumno = :id
         ORDER BY pc.ciclo ASC, c.nombre ASC'
    );
    $stmt->execute(['id' => $studentId]);
    return $stmt->fetchAll();
}

function get_student_grades(int $studentId): array
{
    $stmt = db()->prepare(
        'SELECT 
            c.codigo,
            c.nombre AS curso,
            ROUND(AVG(cal.punteo), 2) AS promedio,
            COUNT(cal.id_calificacion) AS evaluaciones,
            p.nombre AS periodo,
            s.nombre_seccion
         FROM calificaciones cal
         INNER JOIN evaluaciones e ON e.id_evaluacion = cal.id_evaluacion
         INNER JOIN secciones s ON s.id_seccion = e.id_seccion
         INNER JOIN cursos c ON c.id_curso = s.id_curso
         INNER JOIN periodos_academicos p ON p.id_periodo = s.id_periodo
         WHERE cal.id_alumno = :id
         GROUP BY c.id_curso, c.codigo, c.nombre, p.nombre, s.nombre_seccion
         ORDER BY p.nombre DESC, c.nombre ASC'
    );
    $stmt->execute(['id' => $studentId]);
    return $stmt->fetchAll();
}

function get_student_assignments(int $studentId): array
{
    $stmt = db()->prepare(
        'SELECT 
            t.id_tarea,
            t.titulo,
            t.descripcion,
            t.fecha_limite,
            t.puntos,
            c.nombre AS curso,
            c.codigo,
            s.nombre_seccion,
            et.id_entrega,
            et.archivo,
            et.comentario,
            et.fecha_entrega,
            et.calificacion,
            et.estado
         FROM inscripciones i
         INNER JOIN secciones s ON s.id_seccion = i.id_seccion
         INNER JOIN cursos c ON c.id_curso = s.id_curso
         INNER JOIN tareas t ON t.id_seccion = s.id_seccion
         LEFT JOIN entregas_tareas et ON et.id_tarea = t.id_tarea AND et.id_alumno = i.id_alumno
         WHERE i.id_alumno = :id
         ORDER BY t.fecha_limite IS NULL, t.fecha_limite ASC, t.id_tarea DESC'
    );
    $stmt->execute(['id' => $studentId]);
    return $stmt->fetchAll();
}

function get_student_payments(int $studentId): array
{
    $stmt = db()->prepare(
        'SELECT referencia, monto, metodo_pago, estado, fecha_pago, observacion
         FROM pagos
         WHERE id_alumno = :id
         ORDER BY fecha_pago DESC, id_pago DESC'
    );
    $stmt->execute(['id' => $studentId]);
    return $stmt->fetchAll();
}

function get_student_charges(int $studentId): array
{
    $stmt = db()->prepare(
        'SELECT concepto, monto, estado, fecha_cargo
         FROM cargos
         WHERE id_alumno = :id
         ORDER BY fecha_cargo DESC, id_cargo DESC'
    );
    $stmt->execute(['id' => $studentId]);
    return $stmt->fetchAll();
}

function register_payment(int $studentId, float $amount, string $method, string $reference, string $observation): void
{
    if ($amount <= 0) {
        throw new RuntimeException('El monto debe ser mayor a cero.');
    }

    $allowedMethods = ['efectivo', 'transferencia', 'tarjeta', 'deposito'];
    if (!in_array($method, $allowedMethods, true)) {
        throw new RuntimeException('Método de pago inválido.');
    }

    $overview = get_student_overview($studentId);

    if ($amount > $overview['saldo_pendiente']) {
        throw new RuntimeException('El monto excede el saldo pendiente.');
    }

    $stmt = db()->prepare(
        'INSERT INTO pagos (id_alumno, referencia, monto, metodo_pago, estado, observacion)
         VALUES (:id_alumno, :referencia, :monto, :metodo_pago, "confirmado", :observacion)'
    );
    $stmt->execute([
        'id_alumno' => $studentId,
        'referencia' => $reference !== '' ? $reference : null,
        'monto' => $amount,
        'metodo_pago' => $method,
        'observacion' => $observation !== '' ? $observation : null,
    ]);
}

function upsert_assignment_submission(int $studentId, int $taskId, string $comment, array $file): void
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Debes seleccionar un archivo válido.');
    }

    if (($file['size'] ?? 0) > 8 * 1024 * 1024) {
        throw new RuntimeException('El archivo supera el límite de 8MB.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    $allowed = [
        'application/pdf' => 'pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/msword' => 'doc',
        'application/zip' => 'zip',
        'application/x-zip-compressed' => 'zip',
        'text/plain' => 'txt',
    ];

    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Formato no permitido. Solo PDF, DOC, DOCX, ZIP y TXT.');
    }

    $stmt = db()->prepare(
        'SELECT COUNT(*)
         FROM tareas t
         INNER JOIN secciones s ON s.id_seccion = t.id_seccion
         INNER JOIN inscripciones i ON i.id_seccion = s.id_seccion
         WHERE t.id_tarea = :task AND i.id_alumno = :student'
    );
    $stmt->execute([
        'task' => $taskId,
        'student' => $studentId,
    ]);

    if ((int)$stmt->fetchColumn() === 0) {
        throw new RuntimeException('No tienes acceso a esa tarea.');
    }

    $dir = uploads_dir();
    $safeName = 'tarea_' . $studentId . '_' . $taskId . '_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
    $destination = $dir . DIRECTORY_SEPARATOR . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('No se pudo guardar el archivo.');
    }

    $stmt = db()->prepare('SELECT id_entrega, archivo FROM entregas_tareas WHERE id_tarea = :task AND id_alumno = :student LIMIT 1');
    $stmt->execute([
        'task' => $taskId,
        'student' => $studentId,
    ]);
    $existing = $stmt->fetch();

    $state = 'entregado';

    $deadlineStmt = db()->prepare('SELECT fecha_limite FROM tareas WHERE id_tarea = :task LIMIT 1');
    $deadlineStmt->execute(['task' => $taskId]);
    $deadline = $deadlineStmt->fetchColumn();

    if ($deadline && strtotime((string)$deadline) < time()) {
        $state = 'atrasado';
    }

    if ($existing) {
        $update = db()->prepare(
            'UPDATE entregas_tareas
             SET archivo = :archivo, comentario = :comentario, fecha_entrega = CURRENT_TIMESTAMP, estado = :estado
             WHERE id_entrega = :id'
        );
        $update->execute([
            'archivo' => $safeName,
            'comentario' => $comment !== '' ? $comment : null,
            'estado' => $state,
            'id' => $existing['id_entrega'],
        ]);

        if (!empty($existing['archivo'])) {
            $oldPath = $dir . DIRECTORY_SEPARATOR . $existing['archivo'];
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        return;
    }

    $insert = db()->prepare(
        'INSERT INTO entregas_tareas (id_tarea, id_alumno, archivo, comentario, estado)
         VALUES (:task, :student, :archivo, :comentario, :estado)'
    );
    $insert->execute([
        'task' => $taskId,
        'student' => $studentId,
        'archivo' => $safeName,
        'comentario' => $comment !== '' ? $comment : null,
        'estado' => $state,
    ]);
}

/*
|--------------------------------------------------------------------------
| DESCARGA DE ARCHIVO
|--------------------------------------------------------------------------
*/
try {
    if (isset($_GET['download']) && isset($_GET['task'])) {
        $studentId = require_login();
        $taskId = (int)$_GET['task'];

        $stmt = db()->prepare(
            'SELECT et.archivo
             FROM entregas_tareas et
             INNER JOIN tareas t ON t.id_tarea = et.id_tarea
             INNER JOIN secciones s ON s.id_seccion = t.id_seccion
             INNER JOIN inscripciones i ON i.id_seccion = s.id_seccion AND i.id_alumno = et.id_alumno
             WHERE et.id_tarea = :task AND et.id_alumno = :student
             LIMIT 1'
        );
        $stmt->execute([
            'task' => $taskId,
            'student' => $studentId,
        ]);
        $fileName = $stmt->fetchColumn();

        if (!$fileName) {
            throw new RuntimeException('Archivo no encontrado.');
        }

        $path = uploads_dir() . DIRECTORY_SEPARATOR . $fileName;

        if (!is_file($path)) {
            throw new RuntimeException('El archivo físico no existe.');
        }

        $mime = mime_content_type($path) ?: 'application/octet-stream';
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . (string)filesize($path));
        header('Content-Disposition: inline; filename="' . basename($path) . '"');
        readfile($path);
        exit;
    }
} catch (Throwable $e) {
    http_response_code(404);
    echo '<h3 style="font-family:Arial,sans-serif;padding:30px;">' . e($e->getMessage()) . '</h3>';
    exit;
}

/*
|--------------------------------------------------------------------------
| CONTROLADOR
|--------------------------------------------------------------------------
*/
$flash = get_flash();
$student = current_student();
$error = null;

try {
    if (isset($_GET['logout'])) {
        logout_student();
        set_flash('success', 'Sesión cerrada correctamente.');
        redirect('index.php');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf();

        $action = $_POST['action'] ?? '';

        if ($action === 'login') {
            $studentId = trim($_POST['student_id'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($studentId === '' || $password === '') {
                throw new RuntimeException('Ingresa tu ID y contraseña.');
            }

            $authStudent = authenticate_student($studentId, $password);

            if (!$authStudent) {
                throw new RuntimeException('Credenciales inválidas.');
            }

            login_student((int)$authStudent['id_alumno']);
            set_flash('success', 'Bienvenido al portal universitario.');
            redirect('index.php');
        }

        $studentId = require_login();

        if ($action === 'payment') {
            $amount = filter_var($_POST['amount'] ?? null, FILTER_VALIDATE_FLOAT);
            $method = trim($_POST['payment_method'] ?? 'transferencia');
            $reference = trim($_POST['reference'] ?? '');
            $observation = trim($_POST['observation'] ?? '');

            if ($amount === false) {
                throw new RuntimeException('Ingresa un monto válido.');
            }

            register_payment($studentId, (float)$amount, $method, $reference, $observation);
            set_flash('success', 'Pago registrado correctamente.');
            redirect('index.php#pagos');
        }

        if ($action === 'submit_assignment') {
            $taskId = (int)($_POST['task_id'] ?? 0);
            $comment = trim($_POST['comment'] ?? '');

            if ($taskId <= 0) {
                throw new RuntimeException('Tarea inválida.');
            }

            upsert_assignment_submission($studentId, $taskId, $comment, $_FILES['assignment_file'] ?? []);
            set_flash('success', 'Entrega registrada correctamente.');
            redirect('index.php#tareas');
        }
    }
} catch (Throwable $exception) {
    $error = $exception->getMessage();
}

$student = current_student();

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/
if (!$student):
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Universitario Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --card: rgba(16, 27, 46, 0.78);
            --card-border: rgba(255,255,255,0.08);
            --text: #ecf2ff;
            --muted: #9eb0ce;
            --primary: #5b8cff;
            --primary-strong: #3b6fff;
            --shadow: 0 24px 60px rgba(0,0,0,0.35);
        }
        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(91, 140, 255, 0.22), transparent 24%),
                radial-gradient(circle at bottom right, rgba(24, 194, 156, 0.18), transparent 22%),
                linear-gradient(160deg, #05101d 0%, #081423 45%, #0c1728 100%);
            color: var(--text);
        }
        .auth-shell {
            position: relative;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 2rem;
            overflow: hidden;
        }
        .auth-bg {
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            filter: blur(30px);
            opacity: 0.5;
        }
        .auth-bg-one {
            top: -80px;
            left: -60px;
            background: rgba(91, 140, 255, 0.25);
        }
        .auth-bg-two {
            bottom: -100px;
            right: -60px;
            background: rgba(24, 194, 156, 0.18);
        }
        .glass {
            background: var(--card);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(18px);
            box-shadow: var(--shadow);
        }
        .auth-card {
            position: relative;
            z-index: 1;
            width: min(100%, 520px);
            border-radius: 28px;
            padding: 2rem;
        }
        .brand-pill {
            display: inline-flex;
            padding: .45rem .75rem;
            border-radius: 999px;
            background: rgba(91, 140, 255, 0.12);
            border: 1px solid rgba(91, 140, 255, 0.28);
            color: #cfe0ff;
            font-size: .88rem;
            font-weight: 700;
        }
        h1 {
            margin: 1rem 0 .75rem;
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 1.05;
        }
        .muted { color: var(--muted); }
        .alert {
            width: 100%;
            margin: 0 0 1rem;
            padding: 1rem 1.2rem;
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.08);
        }
        .alert-success { background: rgba(24, 194, 156, 0.14); }
        .alert-danger { background: rgba(255, 107, 129, 0.12); }
        .form-grid {
            display: grid;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        label {
            display: grid;
            gap: .55rem;
        }
        label span {
            color: var(--muted);
            font-size: .95rem;
            font-weight: 600;
        }
        input {
            width: 100%;
            border: 1px solid rgba(255,255,255,0.09);
            background: rgba(255,255,255,0.04);
            border-radius: 16px;
            padding: .95rem 1rem;
            color: var(--text);
            outline: none;
        }
        .btn {
            appearance: none;
            border: 0;
            border-radius: 16px;
            padding: .95rem 1.2rem;
            font-weight: 700;
            cursor: pointer;
            background: linear-gradient(135deg, var(--primary), var(--primary-strong));
            color: white;
        }
        code {
            background: rgba(255,255,255,0.08);
            padding: .2rem .45rem;
            border-radius: 8px;
        }
        .auth-hint {
            margin-top: 1rem;
            color: var(--muted);
        }
    </style>
</head>
<body>
<div class="auth-shell">
    <div class="auth-bg auth-bg-one"></div>
    <div class="auth-bg auth-bg-two"></div>

    <div class="auth-card glass">
        <div class="brand-pill">Portal del Estudiante</div>
        <h1>Universidad Regional Pro</h1>
        <p class="muted">Ingresa con tu ID académico y contraseña para acceder a cursos, notas, tareas y pagos.</p>

        <?php if (!empty($flash)): ?>
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="form-grid">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="action" value="login">

            <label>
                <span>ID del alumno</span>
                <input type="number" name="student_id" placeholder="Ej. 1000" required>
            </label>

            <label>
                <span>Contraseña</span>
                <input type="password" name="password" placeholder="••••••••" required>
            </label>

            <button class="btn" type="submit">Ingresar al portal</button>
        </form>

        <div class="auth-hint">
            <strong>Demo:</strong> ID <code>1000</code> · contraseña <code>1234</code>
        </div>
    </div>
</div>
</body>
</html>
<?php
exit;
endif;

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
$overview = get_student_overview((int)$student['id_alumno']);
$courses = get_student_courses((int)$student['id_alumno']);
$pensum = get_student_pensum((int)$student['id_alumno']);
$grades = get_student_grades((int)$student['id_alumno']);
$assignments = get_student_assignments((int)$student['id_alumno']);
$payments = get_student_payments((int)$student['id_alumno']);
$charges = get_student_charges((int)$student['id_alumno']);

$gradeLabels = json_encode(array_map(static fn(array $g): string => $g['curso'], $grades), JSON_UNESCAPED_UNICODE);
$gradeValues = json_encode(array_map(static fn(array $g): float => (float)$g['promedio'], $grades), JSON_UNESCAPED_UNICODE);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Universitario Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --card: rgba(16, 27, 46, 0.76);
            --card-border: rgba(255,255,255,0.08);
            --text: #ecf2ff;
            --muted: #9eb0ce;
            --primary: #5b8cff;
            --primary-strong: #3b6fff;
            --accent: #18c29c;
            --shadow: 0 24px 60px rgba(0,0,0,0.35);
        }
        * {
            box-sizing: border-box;
            scroll-behavior: smooth;
        }
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(91, 140, 255, 0.22), transparent 24%),
                radial-gradient(circle at bottom right, rgba(24, 194, 156, 0.18), transparent 22%),
                linear-gradient(160deg, #05101d 0%, #081423 45%, #0c1728 100%);
            color: var(--text);
            min-height: 100%;
        }
        a {
            color: inherit;
            text-decoration: none;
        }
        .glass {
            background: var(--card);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(18px);
            box-shadow: var(--shadow);
        }
        .muted { color: var(--muted); }
        .alert {
            width: 100%;
            margin: 0 0 1rem;
            padding: 1rem 1.2rem;
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.08);
        }
        .alert-success { background: rgba(24, 194, 156, 0.14); }
        .alert-danger { background: rgba(255, 107, 129, 0.12); }
        .btn {
            appearance: none;
            border: 0;
            border-radius: 16px;
            padding: .95rem 1.2rem;
            font-weight: 700;
            cursor: pointer;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-strong));
            color: white;
        }
        .btn-secondary {
            background: linear-gradient(135deg, #15253f, #0f1d31);
            color: white;
            border: 1px solid rgba(255,255,255,0.08);
        }
        input, select, textarea {
            width: 100%;
            border: 1px solid rgba(255,255,255,0.09);
            background: rgba(255,255,255,0.04);
            border-radius: 16px;
            padding: .95rem 1rem;
            color: var(--text);
            outline: none;
        }
        select option { color: #111827; }
        label {
            display: grid;
            gap: .55rem;
        }
        label span {
            color: var(--muted);
            font-size: .95rem;
            font-weight: 600;
        }
        .app-shell {
            display: grid;
            grid-template-columns: 290px 1fr;
            gap: 1.5rem;
            min-height: 100vh;
            padding: 1.25rem;
        }
        .sidebar {
            border-radius: 28px;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: sticky;
            top: 1.25rem;
            height: calc(100vh - 2.5rem);
        }
        .logo-wrap {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 2rem;
        }
        .logo-mark {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
        }
        .menu {
            display: grid;
            gap: .55rem;
        }
        .menu a, .logout-link {
            padding: .9rem 1rem;
            border-radius: 16px;
            color: #dbe7ff;
        }
        .menu a:hover, .logout-link:hover {
            background: rgba(255,255,255,0.06);
        }
        .content {
            display: grid;
            gap: 1.35rem;
            padding-bottom: 2rem;
        }
        .hero {
            border-radius: 30px;
            padding: 1.8rem;
            display: flex;
            justify-content: space-between;
            gap: 1.25rem;
            align-items: flex-start;
        }
        .hero-badge, .pill {
            display: inline-flex;
            padding: .45rem .75rem;
            border-radius: 999px;
            background: rgba(91, 140, 255, 0.12);
            border: 1px solid rgba(91, 140, 255, 0.28);
            color: #cfe0ff;
            font-size: .88rem;
            font-weight: 700;
        }
        .hero h1 {
            margin: 1rem 0 .75rem;
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 1.05;
        }
        .hero-meta {
            min-width: 260px;
            display: grid;
            gap: .7rem;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
            padding: 1rem;
            border-radius: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        .stat-card, .panel {
            border-radius: 24px;
            padding: 1.25rem;
        }
        .stat-card {
            display: grid;
            gap: .55rem;
        }
        .stat-card span, .stat-card small { color: var(--muted); }
        .stat-card strong { font-size: 1.8rem; }
        .panel-grid {
            display: grid;
            gap: 1rem;
        }
        .two-cols { grid-template-columns: 1fr 1fr; }
        .wide-right { grid-template-columns: 0.95fr 1.05fr; }
        .panel-header { margin-bottom: 1rem; }
        .profile-list {
            display: grid;
            gap: .95rem;
        }
        .profile-list div {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: .95rem 1rem;
            background: rgba(255,255,255,0.04);
            border-radius: 18px;
        }
        .profile-list span { color: var(--muted); }
        .table-wrap {
            overflow: auto;
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.06);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }
        thead { background: rgba(255,255,255,0.05); }
        th, td {
            padding: .95rem 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .assignment-grid {
            display: grid;
            gap: 1rem;
        }
        .assignment-card {
            border: 1px solid rgba(255,255,255,0.06);
            background: rgba(255,255,255,0.03);
            border-radius: 22px;
            padding: 1rem;
        }
        .assignment-top {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: flex-start;
        }
        .assignment-description, .assignment-meta, .mini-section, .panel-header p { color: var(--muted); }
        .assignment-meta {
            display: flex;
            gap: .8rem;
            flex-wrap: wrap;
            font-size: .95rem;
            margin-bottom: 1rem;
        }
        .assignment-form {
            display: grid;
            gap: .8rem;
        }
        .submitted-box {
            display: grid;
            gap: .35rem;
            margin: 0 0 1rem;
            padding: .95rem 1rem;
            background: rgba(24, 194, 156, 0.08);
            border-radius: 16px;
        }
        .mini-section + .mini-section {
            margin-top: 1.2rem;
        }
        @media (max-width: 1180px) {
            .app-shell,
            .stats-grid,
            .two-cols,
            .wide-right,
            .hero {
                grid-template-columns: 1fr;
            }
            .sidebar {
                position: static;
                height: auto;
            }
        }
    </style>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar glass">
        <div>
            <div class="logo-wrap">
                <div class="logo-mark">UR</div>
                <div>
                    <h2>Campus Pro</h2>
                    <p class="muted">Portal estudiantil</p>
                </div>
            </div>

            <nav class="menu">
                <a href="#inicio">Resumen</a>
                <a href="#perfil">Perfil</a>
                <a href="#cursos">Cursos</a>
                <a href="#pensum">Pensum</a>
                <a href="#notas">Notas</a>
                <a href="#tareas">Tareas</a>
                <a href="#pagos">Pagos</a>
            </nav>
        </div>

        <a class="logout-link" href="?logout=1">Cerrar sesión</a>
    </aside>

    <main class="content">
        <section id="inicio" class="hero glass">
            <div>
                <span class="hero-badge">Panel académico</span>
                <h1>Bienvenido, <?= e($student['nombre']) ?></h1>
                <p class="muted">Consulta tu progreso académico, cursos inscritos, tareas activas e historial financiero desde un solo lugar.</p>
            </div>
            <div class="hero-meta">
                <div><strong>ID:</strong> <?= e((string)$student['id_alumno']) ?></div>
                <div><strong>Estado:</strong> <?= e(ucfirst($student['estado'])) ?></div>
                <div><strong>Carrera:</strong> <?= e($student['carrera']) ?></div>
            </div>
        </section>

        <?php if (!empty($flash)): ?>
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <section class="stats-grid">
            <article class="stat-card glass">
                <span>Saldo pendiente</span>
                <strong><?= e(money($overview['saldo_pendiente'])) ?></strong>
                <small>Total cargos: <?= e(money($overview['total_cargos'])) ?></small>
            </article>
            <article class="stat-card glass">
                <span>Total pagado</span>
                <strong><?= e(money($overview['total_pagos'])) ?></strong>
                <small>Historial financiero actualizado</small>
            </article>
            <article class="stat-card glass">
                <span>Cursos activos</span>
                <strong><?= e((string)$overview['cursos_activos']) ?></strong>
                <small>Inscripciones vigentes</small>
            </article>
            <article class="stat-card glass">
                <span>Tareas publicadas</span>
                <strong><?= e((string)$overview['tareas_publicadas']) ?></strong>
                <small>Asignaciones visibles para ti</small>
            </article>
        </section>

        <section id="perfil" class="panel-grid two-cols">
            <article class="panel glass">
                <div class="panel-header">
                    <h3>Perfil del alumno</h3>
                </div>
                <div class="profile-list">
                    <div><span>Nombre</span><strong><?= e($student['nombre']) ?></strong></div>
                    <div><span>Correo</span><strong><?= e($student['correo']) ?></strong></div>
                    <div><span>Carrera</span><strong><?= e($student['carrera']) ?></strong></div>
                    <div><span>ID académico</span><strong><?= e((string)$student['id_alumno']) ?></strong></div>
                </div>
            </article>

            <article class="panel glass">
                <div class="panel-header">
                    <h3>Resumen financiero</h3>
                </div>
                <div class="profile-list">
                    <div><span>Total cargos</span><strong><?= e(money($overview['total_cargos'])) ?></strong></div>
                    <div><span>Total pagado</span><strong><?= e(money($overview['total_pagos'])) ?></strong></div>
                    <div><span>Saldo pendiente</span><strong><?= e(money($overview['saldo_pendiente'])) ?></strong></div>
                    <div><span>Cuenta</span><strong><?= $overview['saldo_pendiente'] > 0 ? 'Con saldo pendiente' : 'Solvente' ?></strong></div>
                </div>
            </article>
        </section>

        <section id="cursos" class="panel glass">
            <div class="panel-header">
                <h3>Cursos asignados</h3>
                <p>Secciones activas e inscritas por periodo.</p>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Curso</th>
                        <th>Créditos</th>
                        <th>Sección</th>
                        <th>Periodo</th>
                        <th>Aula</th>
                        <th>Horario</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?= e($course['codigo']) ?></td>
                            <td><?= e($course['nombre']) ?></td>
                            <td><?= e((string)$course['creditos']) ?></td>
                            <td><?= e($course['nombre_seccion']) ?></td>
                            <td><?= e($course['periodo']) ?></td>
                            <td><?= e($course['aula'] ?? '-') ?></td>
                            <td><?= e($course['horario'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="pensum" class="panel glass">
            <div class="panel-header">
                <h3>Pensum de la carrera</h3>
                <p>Plan de estudios estructurado por ciclo.</p>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Ciclo</th>
                        <th>Código</th>
                        <th>Curso</th>
                        <th>Créditos</th>
                        <th>Tipo</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pensum as $item): ?>
                        <tr>
                            <td><?= e((string)$item['ciclo']) ?></td>
                            <td><?= e($item['codigo']) ?></td>
                            <td><?= e($item['nombre']) ?></td>
                            <td><?= e((string)$item['creditos']) ?></td>
                            <td><?= (int)$item['obligatorio'] === 1 ? 'Obligatorio' : 'Optativo' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="notas" class="panel-grid two-cols wide-right">
            <article class="panel glass">
                <div class="panel-header">
                    <h3>Rendimiento</h3>
                    <p>Promedio por curso inscrito.</p>
                </div>
                <canvas id="gradesChart"></canvas>
            </article>

            <article class="panel glass">
                <div class="panel-header">
                    <h3>Notas consolidadas</h3>
                    <p>Promedio calculado a partir de evaluaciones.</p>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Sección</th>
                            <th>Periodo</th>
                            <th>Evaluaciones</th>
                            <th>Promedio</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($grades as $grade): ?>
                            <tr>
                                <td><?= e($grade['curso']) ?></td>
                                <td><?= e($grade['nombre_seccion']) ?></td>
                                <td><?= e($grade['periodo']) ?></td>
                                <td><?= e((string)$grade['evaluaciones']) ?></td>
                                <td><?= e((string)$grade['promedio']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

        <section id="tareas" class="panel glass">
            <div class="panel-header">
                <h3>Tareas y entregas</h3>
                <p>Sube tu archivo o reemplaza una entrega existente.</p>
            </div>

            <div class="assignment-grid">
                <?php foreach ($assignments as $assignment): ?>
                    <article class="assignment-card">
                        <div class="assignment-top">
                            <div>
                                <h4><?= e($assignment['titulo']) ?></h4>
                                <p><?= e($assignment['curso']) ?> · Sección <?= e($assignment['nombre_seccion']) ?></p>
                            </div>
                            <span class="pill"><?= e((string)$assignment['puntos']) ?> pts</span>
                        </div>

                        <p class="assignment-description"><?= e($assignment['descripcion'] ?? 'Sin descripción adicional.') ?></p>

                        <div class="assignment-meta">
                            <span>Fecha límite: <?= e($assignment['fecha_limite'] ?? 'Sin límite') ?></span>
                            <span>Estado: <?= e($assignment['estado'] ?? 'Pendiente de entrega') ?></span>
                            <?php if ($assignment['calificacion'] !== null): ?>
                                <span>Calificación: <?= e((string)$assignment['calificacion']) ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($assignment['archivo'])): ?>
                            <div class="submitted-box">
                                <strong>Entrega actual:</strong>
                                <a href="?download=1&task=<?= e((string)$assignment['id_tarea']) ?>" target="_blank">Ver archivo enviado</a>
                                <small>Fecha de entrega: <?= e($assignment['fecha_entrega'] ?? '-') ?></small>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data" class="assignment-form">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="action" value="submit_assignment">
                            <input type="hidden" name="task_id" value="<?= e((string)$assignment['id_tarea']) ?>">

                            <textarea name="comment" rows="3" placeholder="Comentario para el docente"><?= e($assignment['comentario'] ?? '') ?></textarea>
                            <input type="file" name="assignment_file" required>
                            <button type="submit" class="btn btn-secondary">Guardar entrega</button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="pagos" class="panel-grid two-cols">
            <article class="panel glass">
                <div class="panel-header">
                    <h3>Registrar pago</h3>
                    <p>Tu saldo pendiente actual es <?= e(money($overview['saldo_pendiente'])) ?>.</p>
                </div>

                <form method="POST" class="assignment-form">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="action" value="payment">

                    <label>
                        <span>Monto</span>
                        <input type="number" name="amount" step="0.01" min="0.01" max="<?= e((string)$overview['saldo_pendiente']) ?>" required>
                    </label>

                    <label>
                        <span>Método de pago</span>
                        <select name="payment_method">
                            <option value="transferencia">Transferencia</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="deposito">Depósito</option>
                            <option value="efectivo">Efectivo</option>
                        </select>
                    </label>

                    <label>
                        <span>Referencia</span>
                        <input type="text" name="reference" placeholder="Ej. TRX-2026-001">
                    </label>

                    <label>
                        <span>Observación</span>
                        <textarea name="observation" rows="4" placeholder="Comentario opcional"></textarea>
                    </label>

                    <button type="submit" class="btn btn-primary">Registrar pago</button>
                </form>
            </article>

            <article class="panel glass">
                <div class="panel-header">
                    <h3>Movimientos de cuenta</h3>
                    <p>Cargos administrativos y pagos confirmados.</p>
                </div>

                <div class="mini-section">
                    <h4>Cargos</h4>
                    <div class="table-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>Concepto</th>
                                <th>Monto</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($charges as $charge): ?>
                                <tr>
                                    <td><?= e($charge['concepto']) ?></td>
                                    <td><?= e(money((float)$charge['monto'])) ?></td>
                                    <td><?= e(ucfirst($charge['estado'])) ?></td>
                                    <td><?= e($charge['fecha_cargo']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mini-section">
                    <h4>Pagos</h4>
                    <div class="table-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>Referencia</th>
                                <th>Monto</th>
                                <th>Método</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?= e($payment['referencia'] ?? '-') ?></td>
                                    <td><?= e(money((float)$payment['monto'])) ?></td>
                                    <td><?= e(ucfirst($payment['metodo_pago'])) ?></td>
                                    <td><?= e(ucfirst($payment['estado'])) ?></td>
                                    <td><?= e($payment['fecha_pago']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
        </section>
    </main>
</div>

<script>
const gradeLabels = <?= $gradeLabels ?>;
const gradeValues = <?= $gradeValues ?>;

if (gradeLabels.length > 0) {
    const ctx = document.getElementById('gradesChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: gradeLabels,
            datasets: [{
                label: 'Promedio',
                data: gradeValues,
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { stepSize: 10 }
                }
            }
        }
    });
}
</script>
</body>
</html>