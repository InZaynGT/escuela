# Guía de Pruebas — Sistema EORM

## Preparar la base de datos

```bash
php artisan migrate:fresh
php artisan db:seed --class=DemoSeeder
php artisan serve
```

El seeder crea toda la estructura demo: grados, secciones, materias CNB, 4 períodos 2026, 3 docentes, ~25 estudiantes, tareas y calificaciones para las 2 primeras unidades, y asistencias desde enero hasta mediados de abril.

---

## Credenciales de acceso

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | admin@escuela.com | admin123 |
| Docente – Matemáticas | ana@escuela.com | docente123 |
| Docente – Comunicación L1 | luis@escuela.com | docente123 |
| Docente – Comunicación L2 | rosa@escuela.com | docente123 |
| Estudiante (cualquiera) | est1@escuela.com … est25@escuela.com | est123 |

---

## FASE 1 — Autenticación

### 1.1 Login de administrador
1. Ir a `/` — debe mostrar el formulario de login.
2. Ingresar `admin@escuela.com` / `admin123` → pulsar **Iniciar sesión**.
3. **Esperado:** redirige a `/admin/dashboard`.
4. Cerrar sesión desde el menú de usuario — regresa al login.

### 1.2 Roles incorrectos
1. Ingresar credenciales de docente (`ana@escuela.com`) → **Esperado:** redirige a `/teacher/dashboard`.
2. Ingresar credenciales de estudiante (`est1@escuela.com`) → **Esperado:** redirige a `/student/dashboard`.
3. Intentar acceder a `/admin/dashboard` mientras se está logueado como docente → **Esperado:** error 403.

---

## FASE 2 — Panel Admin: Catálogos

### 2.1 Grados
1. Ir a **Admin → Grados**.
2. Verificar que existen los 6 grados de primaria creados por el seeder.
3. Crear un nuevo grado: nombre `Preprimaria` → guardar.
4. **Esperado:** aparece en la lista con mensaje de éxito (SweetAlert verde).
5. Editar `Preprimaria` → cambiar nombre a `Kínder` → guardar.
6. Eliminar `Kínder` → confirmar en el diálogo SweetAlert → **Esperado:** desaparece de la lista.

### 2.2 Secciones
1. Ir a **Admin → Secciones** — deben existir `A` y `B`.
2. Crear sección `C` → guardar → verificar en lista.
3. Eliminar `C`.

### 2.3 Grado-Sección
1. Ir a **Admin → Grado-Sección**.
2. Verificar que aparecen las combinaciones creadas: todos los grados con `A`, y Primero/Segundo con `B`.
3. Crear una nueva combinación: `Kínder` (si existe) con `A`, o cualquier grado con `C` si se creó.
4. Eliminar la combinación recién creada.

### 2.4 Períodos
1. Ir a **Admin → Períodos**.
2. Verificar los 4 períodos de 2026 (Primer, Segundo, Tercer, Cuarto Unidad).
3. Crear un período de prueba: nombre `Prueba`, año 2025 → guardar.
4. Editar → cambiar nombre → guardar.
5. Eliminar el período de prueba.

### 2.5 Materias
1. Ir a **Admin → Materias**.
2. Verificar que existen las materias CNB por cada grado-sección (generadas automáticamente por el observer al crear el GradoSeccion).
3. Crear nueva materia: nombre `Arte`, grado-sección `Primero Primaria — A` → guardar.
4. Editar `Arte` → cambiar nombre → guardar.
5. Eliminar la materia creada.

---

## FASE 3 — Panel Admin: Profesores

### 3.1 Lista y creación
1. Ir a **Admin → Profesores**.
2. Verificar los 3 docentes del seeder con sus materias asignadas en la columna correspondiente.
3. Crear nuevo profesor: nombre `Carlos`, apellidos `Ruiz Méndez`, teléfono `50000000` → guardar.
4. **Esperado:** aparece en la lista. La columna de materias muestra `0`.

### 3.2 Asignar materias (nueva interfaz)
1. En la lista, pulsar **Asignar** en el profesor `Carlos Ruiz Méndez`.
2. **Esperado:** pantalla dividida por grado-sección, cada uno con sus materias como checkboxes.
3. Verificar que algunas materias aparecen **deshabilitadas** con el nombre del docente que ya las tiene en paréntesis (ej. `Matemáticas (Ana López Morales)`).
4. Seleccionar al menos 2 materias disponibles de grados distintos.
5. Usar el enlace **Seleccionar todo** en un grupo → verificar que marca solo las no bloqueadas.
6. Pulsar **Guardar asignación** → **Esperado:** redirige a la lista con mensaje de éxito.
7. Volver a **Asignar** del mismo profesor → verificar que las materias elegidas aparecen marcadas.

### 3.3 Cuenta de acceso
1. Pulsar **Editar** en `Carlos Ruiz Méndez`.
2. Pulsar **Gestionar cuenta** (o **Crear cuenta de acceso**).
3. Ingresar email `carlos@escuela.com` y contraseña `carlos123` (con confirmación) → guardar.
4. **Esperado:** mensaje de éxito, aparece badge "Cuenta activa".
5. Cerrar sesión e iniciar como `carlos@escuela.com` / `carlos123` → **Esperado:** portal de docente.

### 3.4 Eliminar profesor
1. Regresar como admin.
2. Eliminar al profesor `Carlos Ruiz Méndez` → confirmar → **Esperado:** eliminado junto con su cuenta de usuario.

---

## FASE 4 — Panel Admin: Estudiantes

### 4.1 Filtros con cascada
1. Ir a **Admin → Estudiantes**.
2. **Esperado:** tabla vacía con mensaje "Selecciona un grado para ver estudiantes".
3. Seleccionar grado `Primero Primaria` → pulsar **Buscar** sin seleccionar sección.
4. **Esperado:** aparecen los 10 estudiantes de Primero A + Primero B combinados.
5. Seleccionar sección `A` → **Buscar** → **Esperado:** solo los 6 de Primero A.
6. Escribir parte de un apellido en **Nombre** → **Buscar** → filtro funciona.

### 4.2 Crear estudiante
1. Pulsar **Nuevo estudiante**.
2. Completar: nombre `Pedro`, apellidos `García López`, CUI `9999999901` → guardar.
3. **Esperado:** redirige a la lista con éxito.

### 4.3 Inscribir estudiante
1. Pulsar **Inscribir** en `Pedro García López`.
2. Seleccionar grado-sección `Segundo Primaria — A`, año `2026` → guardar.
3. **Esperado:** inscripción registrada. Buscando en Segundo Primaria A debe aparecer Pedro.

### 4.4 Cuenta de estudiante
1. Pulsar **Editar** en `Pedro García López`.
2. Pulsar **Crear cuenta de acceso**.
3. Email `pedro@escuela.com`, contraseña `pedro123` → guardar.
4. Cerrar sesión e ingresar como `pedro@escuela.com` / `pedro123` → **Esperado:** portal de estudiante (sin materias porque no tiene tareas aún en Segundo A).

---

## FASE 5 — Panel Admin: Responsables

### 5.1 Crear y asignar
1. Ir a **Admin → Responsables**.
2. Crear responsable: nombre `María`, apellidos `García Tzul`, teléfono `55551234`, parentesco `Madre` → guardar.
3. Pulsar **Asignar estudiantes** en la fila de `García Tzul, María`.
4. **Esperado:** lista de estudiantes con checkboxes, filtros de grado/sección/nombre en la parte superior.
5. Filtrar por grado `Primero Primaria` → verificar que se reducen los estudiantes mostrados.
6. Marcar a `Sofía Ajú Tun` y `Diego Caal Xo` → **Guardar asignación**.
7. **Esperado:** en la lista de responsables, `García Tzul, María` muestra `2 estudiantes`.

---

## FASE 6 — Panel Admin: Reportes

### 6.1 Listado por grado-sección
1. Ir a **Admin → Reportes → Listado por Grado**.
2. Seleccionar grado `Primero Primaria` → seleccionar sección `A` → año `2026` → **Buscar**.
3. **Esperado:** tabla con 6 estudiantes ordenados por apellidos, con CUI y teléfono.
4. Pulsar **Imprimir** → abre el diálogo de impresión del navegador.

### 6.2 Boleta individual (vista)
1. Ir a **Admin → Reportes → Boletas**.
2. Seleccionar grado `Primero Primaria`, sección `A` en el selector de boleta individual.
3. **Esperado:** el select de estudiantes se carga via AJAX con los estudiantes de esa sección.
4. Seleccionar a `Ajú Tun, Sofía` → año `2026` → **Ver boleta**.
5. **Esperado:** tabla con materias, notas de Primer y Segundo Unidad completadas, Tercer y Cuarto con `—`, columna "Estado" muestra `EN CURSO`.
6. Pulsar **Descargar PDF** → debe descargarse un archivo `.pdf` con formato de boleta MINEDUC.
7. Verificar en el PDF: encabezado del ministerio, nombre del estudiante, grado, tabla de materias y notas, líneas de firma.

### 6.3 Boleta sin inscripción
1. En el selector de boleta, buscar a `Pedro García López` (inscrito en Segundo Primaria A).
2. Cambiar el año a `2025` → **Ver boleta**.
3. **Esperado:** mensaje "El estudiante seleccionado no tiene inscripción activa para el año 2025".

### 6.4 ZIP de boletas por grado
1. En la sección "Descargar todas las boletas", seleccionar grado `Primero Primaria`, sección `A`, año `2026` → **Generar ZIP**.
2. **Esperado:** se descarga un archivo `.zip` (nombre como `boletas_Primero_Primaria_A_2026.zip`).
3. Abrir el ZIP → debe contener un PDF por cada estudiante de Primero A.

### 6.5 Reporte de asistencia
1. Ir a **Admin → Reportes → Asistencia**.
2. Seleccionar grado-sección `Primero Primaria — A`, año `2026` → **Buscar**.
3. **Esperado:** tabla por estudiante con totales de presentes, ausentes, tardanzas y porcentaje.
4. Verificar que los porcentajes son razonables (el seeder siembra 80% presente).

---

## FASE 7 — Portal Docente

### 7.1 Dashboard
1. Iniciar sesión como `ana@escuela.com` / `docente123`.
2. **Esperado:** dashboard con tarjetas de las materias asignadas a Ana (Matemáticas en Primero, Segundo y Tercero A y B).

### 7.2 Tareas — ver y crear
1. Pulsar cualquier materia → **Esperado:** pantalla con las 4 unidades/períodos y la cantidad de tareas y puntos por unidad.
2. Pulsar sobre `Primer Unidad` → **Esperado:** lista de 3 tareas (Actividades 30pts, Examen 40pts, Trabajo 30pts). Barra de puntos al 100%.
3. Intentar crear nueva tarea en `Primer Unidad` → **Esperado:** el botón "Nueva tarea" no aparece porque ya se alcanzaron los 100 pts.
4. Ir a `Tercer Unidad` (sin tareas del seeder) → crear tarea: título `Evaluación`, ponderación `60` → guardar.
5. Crear segunda tarea: título `Trabajo grupal`, ponderación `41` → **Esperado:** error "Solo quedan 40 pts disponibles".
6. Crear segunda tarea con ponderación `40` → guardar exitosamente.

### 7.3 Eliminar tarea — bloqueo con notas
1. En `Primer Unidad`, pulsar el botón de eliminar en cualquier tarea.
2. Confirmar en el diálogo SweetAlert.
3. **Esperado:** SweetAlert rojo con mensaje "No se puede eliminar esta tarea porque ya tiene calificaciones registradas".

### 7.4 Eliminar tarea — sin notas
1. En `Tercer Unidad`, eliminar la tarea `Evaluación` que se creó en el paso 7.2.
2. Confirmar → **Esperado:** eliminada correctamente, lista actualizada.

### 7.5 Calificaciones
1. En `Primer Unidad`, pulsar **Calificar**.
2. **Esperado:** tabla con todos los estudiantes de esa sección y columnas por cada tarea del período.
3. Las notas del seeder ya están llenadas (Unidades 1 y 2).
4. Cambiar una nota (ej. de 28 a 25) → **Guardar calificaciones**.
5. **Esperado:** mensaje de éxito, la nota actualizada persiste al recargar.
6. Ir a `Tercer Unidad` → calificar → los campos están vacíos.
7. Llenar las notas de un par de estudiantes → guardar → verificar persistencia.

### 7.6 Asistencia
1. Ir a **Asistencia** en el menú del docente.
2. **Esperado:** selector de grado-sección entre los que tiene asignados.
3. Seleccionar `Primero Primaria — A` → **Registrar asistencia**.
4. **Esperado:** lista de estudiantes de esa sección con campos de fecha y estado (presente/ausente/tardanza/justificado).
5. Cambiar la fecha → cambiar estados de algunos estudiantes → **Guardar**.
6. **Esperado:** mensaje de éxito.

---

## FASE 8 — Portal Estudiante

### 8.1 Materias
1. Iniciar sesión como `est1@escuela.com` / `est123` (Sofía Ajú Tun, Primero A).
2. Ir a **Mis materias**.
3. **Esperado:** tarjetas de las materias de Primero A (Matemáticas, Comunicación y Lenguaje, etc.).
4. Pulsar una materia → **Esperado:** tarjetas de las 4 unidades, cada una muestra puntos ganados / puntos máximos.
5. Pulsar `Primer Unidad` → **Esperado:** lista de tareas con nota obtenida en cada una.

### 8.2 Calificaciones (mini-boleta)
1. Ir a **Calificaciones**.
2. **Esperado:** tabla estilo boleta con materias en filas y unidades en columnas.
3. Primer y Segundo Unidad con notas coloreadas (verde ≥70, amarillo ≥60, rojo <60).
4. Tercer y Cuarto Unidad con `—`.
5. Columna "Total" muestra resultado parcial o `EN CURSO`.

### 8.3 Asistencia
1. Ir a **Asistencia**.
2. **Esperado:** tabla con fecha, estado (Presente / Ausente / Tardanza / Justificado) y observación.
3. Los registros deben corresponder a los días hábiles desde enero hasta mediados de abril 2026.

---

## Casos límite adicionales

| Situación | Dónde probar | Esperado |
|-----------|-------------|----------|
| Docente accede a materia que no le pertenece | Cambiar el ID en la URL del docente | Redirige con error "No tienes acceso" |
| Materia sin tareas en boleta | Ver boleta de cualquier materia sin tareas | Muestra `—` en todas las unidades |
| Eliminar grado con grado-sección activa | Admin → Grados → Eliminar | Error de FK o mensaje bloqueado |
| Contraseña sin confirmación coincidente | Admin → Cuenta de estudiante → nueva contraseña | Error de validación inline |
| ZIP con grado-sección sin estudiantes activos | Reportes → ZIP → grado sin inscritos | Error 404 "No hay estudiantes activos" |
