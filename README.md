# 📦 Control de Inventario

Sistema web de gestión de inventario desarrollado en PHP, orientado a la administración de productos, categorías, stock y usuarios, con interfaz modular y operaciones CRUD mediante ventanas modales.

Este proyecto es ideal como trabajo académico, sistema administrativo básico o base para un proyecto real de inventario.

--------------------------------------------------

<details>
<summary><strong>🧠 ¿Qué es Control de Inventario?</strong></summary>

Control de Inventario es una aplicación web que permite:

✔ Administrar productos y categorías
✔ Controlar entradas y salidas de stock
✔ Gestionar usuarios del sistema
✔ Autenticación mediante login
✔ Operaciones CRUD con ventanas modales
✔ Interfaz simple y organizada para administración

El sistema está construido con PHP clásico, separando vistas, lógica y recursos de apoyo (JS, librerías y modales).

</details>

--------------------------------------------------

<details>
<summary><strong>📌 Funcionalidades principales</strong></summary>

📦 Gestión de productos
- Registro de nuevos productos
- Edición y eliminación
- Asociación a categorías
- Control de stock por producto

🗂 Gestión de categorías
- Crear, editar y eliminar categorías
- Organización lógica de productos

📊 Control de stock
- Agregar y eliminar stock
- Visualización del estado del inventario
- Acciones mediante modales

👤 Gestión de usuarios
- Registro de usuarios
- Edición de datos
- Cambio de contraseña
- Eliminación de usuarios

🔐 Autenticación
- Login de acceso al sistema
- Control básico de sesión

</details>

--------------------------------------------------

<details>
<summary><strong>🛠 Tecnologías utilizadas</strong></summary>

- PHP – Lógica del servidor
- HTML5 – Estructura de vistas
- CSS – Estilos de la interfaz
- JavaScript – Interacciones y validaciones
- Bootstrap (implícito) – Diseño responsivo
- Modales PHP – Formularios dinámicos
- password_compatibility_library.php – Compatibilidad para hash de contraseñas

</details>

--------------------------------------------------

<details>
<summary><strong>📂 Estructura del proyecto</strong></summary>

Control-inventario/
├── index.php
├── login.php
├── categorias.php
├── producto.php
├── stock.php
├── usuarios.php
├── funciones.php
├── head.php
├── navbar.php
├── footer.php
├── modal/
│   ├── registro_productos.php
│   ├── editar_productos.php
│   ├── eliminar_stock.php
│   ├── editar_categorias.php
│   ├── registro_usuarios.php
│   └── ...
├── js/
│   ├── usuarios.js
│   └── VentanaCentrada.js
├── libraries/
│   └── password_compatibility_library.php
├── License.txt
└── README.md

</details>

--------------------------------------------------

<details>
<summary><strong>🚀 Cómo ejecutar el proyecto localmente</strong></summary>

1. Requisitos
- Servidor web local (XAMPP, WAMP, Laragon o similar)
- PHP 7.x o superior
- Navegador web

2. Clonar el repositorio

git clone https://github.com/zomni/Control-inventario.git

3. Configuración
- Copiar la carpeta en el directorio htdocs (o equivalente)
- Configurar la conexión a base de datos si corresponde
- Verificar que Apache esté activo

4. Ejecutar

http://localhost/Control-inventario/

</details>

--------------------------------------------------

<details>
<summary><strong>🧪 Uso del sistema</strong></summary>

- Iniciar sesión desde el login
- Acceder al panel principal
- Administrar productos, categorías, stock y usuarios
- Todas las acciones se realizan mediante ventanas modales

</details>

--------------------------------------------------


