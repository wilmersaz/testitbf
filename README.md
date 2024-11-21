# **Manual de Ejecución del Proyecto**

Seguir los pasos a continuación para ejecutar correctamente el proyecto.

---

## **1. Clonar el repositorio**

Clona el repositorio usando el siguiente comando:

```bash
git clone https://github.com/wilmersaz/testitbf.git
```

O, si lo desea, puede descargarlo como archivo ZIP, haciendo clic en el siguiente enlace y descomprímiéndolo en el lugar de su preferencia:

https://github.com/wilmersaz/testitbf/archive/refs/heads/main.zip

## **2. Configurar la base de datos**

En la raíz del proyecto, abrir el archivo `.env` y configurar los parámetros de su base de datos. Por ejemplo:

```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=testitbfbd
DB_USERNAME=postgres
DB_PASSWORD=
```

## **3. Crear la base de datos**

Usar su herramienta preferida (`phpMyAdmin, HeidiSQL, DBeaver, etc.`) para crear la base de datos. Puede ejecutar el siguiente comando SQL:


```bash
CREATE DATABASE testitbfbd;
```

## **4. Ejecutar las migraciones y seeders**
Ejecutar el siguiente comando en la terminal para crear las tablas y poblarlas con datos iniciales:

```bash
php artisan migrate:fresh --seed
```

## **5. Iniciar el servidor del proyecto**
Iniciar el servidor con el comando:

```bash
php artisan serve
```

## **6. Acceder al proyecto**
Abrir el navegador de su elección y acceder a la URL sugerida, generalmente:

http://localhost:8000