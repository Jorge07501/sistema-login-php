# Sistema web de autenticación y perfil con PHP y MySQL

Sistema pequeño desarrollado en PHP y MySQL que permite registrar usuarios, iniciar sesión, acceder a una zona privada, actualizar datos básicos del perfil, cambiar la contraseña de forma segura y cerrar sesión.

## Funcionalidades

- Registro de usuario con cédula, nombre, correo y contraseña.
- Validación de correo repetido y cédula repetida.
- Contraseña almacenada con `password_hash()`.
- Inicio de sesión con correo y contraseña.
- Validación de credenciales con `password_verify()`.
- Creación de sesión usando `$_SESSION`.
- Perfil privado visible solo para usuarios autenticados.
- Actualización de nombre y correo.
- Cambio de contraseña verificando la contraseña actual.
- Cierre de sesión con destrucción de sesión.
- Consultas preparadas con PDO para reducir riesgos de inyección SQL.

## Requisitos

- PHP 8 o superior.
- MySQL o MariaDB.
- Servidor local como XAMPP, WAMP, Laragon o MAMP.
- Navegador web.

## Estructura de archivos

```text
sistema_perfil_php/
├── config/
│   └── conexion.php
├── includes/
│   ├── auth.php
│   ├── header.php
│   └── footer.php
├── public/
│   └── styles.css
├── cambiar_password.php
├── database.sql
├── index.php
├── login.php
├── logout.php
├── perfil.php
├── registro.php
└── README.md
```

## Instalación local

1. Copiar la carpeta `sistema_perfil_php` dentro del directorio del servidor local.
   - En XAMPP: `htdocs/sistema_perfil_php`.
   - En Laragon: `www/sistema_perfil_php`.

2. Iniciar Apache y MySQL desde el panel del servidor local.

3. Crear la base de datos importando el archivo:

```sql
database.sql
```

Puede importarse desde phpMyAdmin o ejecutarse directamente en MySQL.

4. Revisar la conexión en `config/conexion.php`:

```php
$host = 'localhost';
$dbname = 'sistema_perfil';
$user = 'root';
$pass = '';
```

Cambiar usuario o contraseña si el entorno local lo requiere.

5. Abrir el proyecto en el navegador:

```text
http://localhost/sistema_perfil_php/
```

## Flujo de prueba

1. Entrar a `registro.php` y crear un usuario.
2. Iniciar sesión en `login.php`.
3. Ingresar al perfil privado en `perfil.php`.
4. Actualizar nombre o correo.
5. Entrar a `cambiar_password.php` y cambiar la contraseña.
6. Cerrar sesión desde `logout.php`.
7. Volver a iniciar sesión con la nueva contraseña.

## Seguridad aplicada

- Las contraseñas se guardan con `password_hash()`.
- Las contraseñas se validan con `password_verify()`.
- Las consultas a la base de datos usan PDO con consultas preparadas.
- Las páginas privadas llaman a `verificarSesion()` para comprobar que exista `$_SESSION['usuario_id']`.
- Se valida que los campos no estén vacíos.
- Se valida el formato del correo con `filter_var()`.
- Se usa `htmlspecialchars()` al mostrar datos en pantalla.
- Se usa `session_regenerate_id(true)` después del login para reducir riesgo de fijación de sesión.

