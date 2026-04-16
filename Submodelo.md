erDiagram
    %% ==========================================
    %% SUB-MODELO DE IDENTIDAD Y ACCESOS
    %% ==========================================

    usuarios {
        int id_usuario PK
        varchar nombre
        varchar correo
        varchar contrasena
        tinyint estado
    }

    roles {
        int id_rol PK
        varchar nombre_rol
    }

    usuario_roles {
        int id_usuario PK, FK
        int id_rol PK, FK
    }

    %% Relaciones de control de acceso (RBAC)
    usuarios ||--o{ usuario_roles : "tiene asignado"
    roles ||--o{ usuario_roles : "define permisos"

    %% ==========================================
    %% PERFILES OPERATIVOS (Extensión de Usuario)
    %% ==========================================

    estudiantes {
        int id_estudiante PK
        int id_usuario FK
        varchar correo
    }

    docentes {
        int id_docente PK
        int id_usuario FK
        varchar correo
    }

    administrativo {
        int id_personal PK
        int id_usuario FK
        varchar correo
    }

    %% Relaciones de herencia de identidad (1 a 1 lógico)
    usuarios ||--|| estudiantes : "es perfil de"
    usuarios ||--|| docentes : "es perfil de"
    usuarios ||--|| administrativo : "es perfil de"
