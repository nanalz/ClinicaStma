# 📋 Sistema de Gestión de Pacientes y Consultas - Centro de Salud

Este proyecto es un sistema web desarrollado en **PHP**, **HTML**, **CSS (Bootstrap)** y **MySQL**, que permite a un **Centro de Salud** registrar, consultar, editar y eliminar pacientes, así como gestionar consultas médicas. También implementa seguridad básica mediante inicio de sesión.
---

## 🚑 Funcionalidades principales

- Registro de pacientes con validación de datos.  
- Verificación de CI duplicada para evitar registros repetidos.  
- Edición de datos de pacientes existentes.  
- Registro de consultas médicas con búsqueda de pacientes mediante autocompletado.  
- Filtros para consultas: fecha, nombre, CI y profesional referido.  
- Ordenamiento dinámico en tablas (por CI, apellidos, etc.).   
- Inicio de sesión con validación de credenciales.  

---

## 📁 Estructura de archivos principales

- `index.php` → Página principal de consultas, con filtros y tabla ordenable.
- `registro.php` → Formulario de registro de pacientes.
- `editar_paciente.php` → Edición de datos de pacientes con validación.
- `lista_pacientes.php` → Lista de pacientes registrados, con opción de editar y eliminar.
- `login.php` → Inicio de sesión de usuarios autorizados.
- `logout.php` → Cierra sesión de usuario.
- `registro.php` → Registro de nueva consulta con autocompletar paciente.
- `buscar_paciente.php` → Backend para autocompletado.
- `conexion.php` → Archivo de conexión a la base de datos.
- `README.md` → Documentación del proyecto (este archivo).

