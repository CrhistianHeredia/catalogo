# Code Review — Catálogo de Usuarios

Fecha: 2026-06-11 | Archivos revisados: 12 (index.php, header.php, footer.php, controller.php, BD.php, campo.php, usuario.php, usuario.js, script.js, style.css, bdprueba.sql, phpunit.xml)

## Estado de remediación (2026-06-11)

- **P1 Seguridad** — ✅ aplicado: 1.1 XSS (`escapeHtml`), 1.2 autenticación (login + sesiones + bcrypt + logout). 1.3 SQLi ya estaba mitigada (prepared statements).
- **P2 Arquitectura** — ✅ aplicado: 2.1 DI en DAOs, 2.2 routing con whitelist + auth, 2.3 credenciales externas (`config.local.php`).
- **P3 Calidad** — ✅ aplicado: 3.1 propiedad dinámica, 3.2 `strict_types` + type hints, 3.3 `sendData()` → `$.ajax`, 3.5 código muerto. ⏳ **PENDIENTE: 3.4 namespaces PSR-4** — omitido deliberadamente para no romper el autoload y los 20 tests; requiere reestructura a `src/` con actualización de todos los require/include y el bootstrap de tests. Retomar en sesión dedicada.
- Tests: 20 PHPUnit (57 assertions) + JS, todos verdes tras la remediación.

---

## 🔴 Prioridad 1 — Seguridad

### 1.1 XSS en el frontend — salida directa de datos a HTML

**Archivo:** `js/usuario.js` — función `printTable()` (líneas 180-197)

`allUsuarios[i]['user_name']`, `email` y `phone` se concatenan directamente al innerHTML mediante `$('#tbodyAllUsuarios').html(tr)`.

Si un atacante logra insertar un valor como `<script>alert('xss')</script>` en la base de datos (por ejemplo mediante la API sin sanitización server-side), ese script se ejecutará en el navegador de cada usuario que visite la página. Lo mismo aplica a los templates de jquery-confirm.

**Solución:** escapar con `$('<div>').text(valor).html()` o usar `text()` en vez de concatenación HTML para los valores que vienen del servidor.

---

### 1.2 Falta de autenticación y control de acceso

**Archivo:** `controller/controller.php` (líneas 71-76)

El script de routing expone un método `$control->$request($arg)` donde `$request` viene directamente de `$_REQUEST['request']`, permitiendo invocar cualquier método público de la clase `Control` con argumentos arbitrarios. No hay verificación de sesión ni token CSRF. Cualquier persona que alcance este endpoint puede crear, modificar o eliminar usuarios sin restricción.

---

### 1.3 Inyección SQL neutralizada — pero frágil

**Archivo:** `controller/DAO/usuario.php`

El uso de consultas preparadas con PDO (`bindParam`) en `agregar()`, `modificar()` y `eliminar()` es correcto y previene inyección SQL. Sin embargo, la dependencia directa en `$this->name` como propiedad dinámica (ver sección 3.1 de calidad) rompe la legibilidad y dificulta auditorías de seguridad.

---

## 🔴 Prioridad 2 — Arquitectura

### 2.1 Acoplamiento rígido a la base de datos

**Archivos:** `BD.php` + `usuario.php`

`Usuario` extiende `DB` directamente, lo que imposibilita testear sin una base de datos real. La conexión se crea con credenciales hardcodeadas en `campo.php`. No hay inyección de dependencias ni interfaz que permita mockear.

Para agregar testabilidad se necesitaría un refactor hacia dependencia inyectada (p.ej. `class Usuario { private $db; function __construct(PDO $db)`).

---

### 2.2 Routing inseguro y mezcla de responsabilidades

**Archivo:** `controller/controller.php`

El archivo define una clase `Control` (lógica de negocio) y en el mismo archivo ejecuta un script de routing (líneas 71-76). Esto mezcla capa de presentación con capa de aplicación.

El routing `$control->$request($arg)` permite invocar **cualquier método público** de la clase `Control`, sin whitelist de métodos permitidos. Esto es peligroso si en el futuro se agregan métodos sensibles a la clase.

---

### 2.3 Sin separación de entornos ni configuración externa

**Archivo:** `controller/DAO/campo.php`

Las credenciales de base de datos están hardcodeadas en el repositorio (`root`, sin contraseña, database `prueba`). No se pueden rotar sin modificar el código fuente.

---

## 🟡 Prioridad 3 — Calidad de código

### 3.1 Propiedad dinámica `$this->name` en Usuario

**Archivo:** `controller/DAO/usuario.php` (líneas 16-20, 35-36, 49)

La clase declara `private $nombre` pero el setter `setName()` escribe en `$this->name` (sin declarar). Esto genera una propiedad dinámica, que en PHP 8.2+ lanza un deprecation warning y será error en PHP 9.0. Ya se agregó `private $name = null;` para evitar el warning, pero la confusión semántica entre `$nombre` y `$name` persiste.

**Además:** `getName()` retorna `parse_str($this->nombre)` que es incorrecto — `parse_str()` no retorna un string, es una función de análisis de query string que modifica variables del ámbito actual. Debería ser simplemente `return $this->nombre`.

**Además:** `getEmail()` usa `parse_str($this->email)` con el mismo problema.

---

### 3.2 Sin tipos ni type hints

Ninguna clase en PHP 8 utiliza tipos declarados (`int`, `string`, `?array`, `void`), ni `declare(strict_types=1)`. Las funciones `setName`, `setPhone`, etc. aceptan `$arg` sin tipado. PHP 8.5 soporta tipos nativos y sería más seguro usarlos.

---

### 3.3 `sendData()` construye un formulario en memoria

**Archivo:** `js/script.js` (líneas 10-23)

Crea un `<form>` jQuery, lo llena con inputs hidden, lo agrega al body y hace submit. Esto funciona pero es inusual. Preferir `fetch` o `$.ajax` con método POST estándar.

---

### 3.4 Estructura de proyecto plana y sin namespaces

Todas las clases están en el namespace global. No hay PSR-4 autoloading (el `composer.json` usa `classmap`). Para un proyecto de este tamaño es aceptable, pero a futuro el classmap se vuelve frágil.

---

### 3.5 Código muerto / no utilizado

- `js/script.js` define `$('#btnLogout').click(...)` y `sendData()` pero no hay botón de logout en el HTML actual.
- `css/style.css` tiene variables CSS declaradas (`--sidebar-width`, `--sidebar-collapsed`) que no se usan en ninguna regla fuera del media query de footer. Solo `--navbar-height` y `--footer-height` tienen uso real.

---

## 🟢 Prioridad 4 — Rendimiento

### 4.1 Sin caché de consultas

La página consulta la BD completa (`SELECT * FROM users`) en cada request PHP. Para una tabla pequeña (<100 registros) es irrelevante, pero sin paginación el problema escala. No hay índice adicional sobre `email` o `user_name`.

### 4.2 Assets sin minificar en producción

Se sirve `bootstrap.css` (281 KB), `all.css` (74 KB), `style.css` (3.7 KB) en versiones sin minificar. Los archivos `.min.css` y `.min.js` existen en `lib/` pero no se usan en `index.php`. Cambiar las referencias a las versiones minificadas reduciría ~300 KB por carga.

---

## 🟢 Prioridad 5 — Mejores prácticas

### 5.1 Schema SQL sin `updated_at` ni `created_at`

La tabla `users` carece de columnas de timestamp. No se puede auditar cuándo se creó o modificó un registro.

### 5.2 Sin validación server-side de email duplicado

El modelo permite insertar emails duplicados. No hay constraint `UNIQUE` en la columna `email`.

### 5.3 Sin paginación

Si el número de usuarios crece, la tabla se vuelve inmanejable sin paginación server-side o client-side.

### 5.4 Columna `phone` muy corta

`phone varchar(15)`: un número internacional con código de país, área y extensión puede exceder 15 caracteres fácilmente (`+52 998 319 4110` = 16). Recomendable `varchar(20)`.

---

## Resumen priorizado

| Prioridad | Área | Hallazgo | Impacto |
|-----------|------|----------|---------|
| 🔴 1 | Seguridad | XSS en `printTable()` | Alto — script injection al renderizar usuarios |
| 🔴 1 | Seguridad | Sin autenticación ni CSRF | Alto — cualquiera puede ejecutar CRUD |
| 🔴 1 | Seguridad | Routing dinámico sin whitelist | Alto — exposición de métodos internos |
| 🔴 2 | Arquitectura | Acoplamiento DB directo en Usuario | Medio — tests requieren BD real |
| 🔴 2 | Arquitectura | Credenciales hardcodeadas en repo | Medio — fuga de secrets en VCS |
| 🟡 3 | Calidad | `parse_str()` usado como getter (incorrecto) | Medio — retorna `1` siempre, no el valor |
| 🟡 3 | Calidad | Propiedad dinámica en Usuario | Bajo — ya reparado, pero código confuso |
| 🟡 3 | Calidad | Sin tipos ni strict_types | Bajo — oportunidad de mejora con PHP 8 |
| 🟢 4 | Rendimiento | Assets sin minificar | Bajo — ~300 KB extra por carga |
| 🟢 4 | Rendimiento | Sin paginación en consultas | Bajo — escala mal con >1000 registros |
| 🟢 5 | Mejores prácticas | Schema sin timestamps, sin UNIQUE email | Bajo — pérdida de auditabilidad |
