# Guía: Monitorear Rendimiento con Query Monitor

## 📦 Instalación de Query Monitor

### Opción 1: Desde el Admin de WordPress (Recomendado)

1. Ve a **Plugins → Añadir nuevo**
2. Busca "Query Monitor"
3. Instala y activa el plugin desarrollado por **John Blackbourn**

### Opción 2: Desde WP-CLI (si tienes acceso)

```bash
wp plugin install query-monitor --activate
```

### Opción 3: Manualmente

1. Descarga desde: https://wordpress.org/plugins/query-monitor/
2. Sube la carpeta a `/wp-content/plugins/`
3. Activa desde **Plugins → Plugins instalados**

---

## 🔍 Cómo Usar Query Monitor

### Acceso a Query Monitor

Una vez instalado, verás un panel en la parte inferior de tu sitio (solo para usuarios administradores). También puedes acceder desde la barra de administración de WordPress.

### Paneles Principales

1. **Database Queries** - Consultas a la base de datos
2. **Hooks & Actions** - Hooks de WordPress ejecutados
3. **Scripts & Styles** - Archivos CSS/JS cargados
4. **HTTP API Calls** - Llamadas HTTP externas
5. **PHP Errors** - Errores PHP
6. **Timing** - Tiempo de ejecución

---

## 📊 Monitorear la Caché de Banners

### Antes de la Caché (Línea Base)

1. **Limpia la caché de transients** (para forzar una carga sin caché):
   ```php
   // Ejecuta esto en el admin o vía WP-CLI:
   wp transient delete --all
   ```

2. **Recarga la página principal** (home)

3. **Abre Query Monitor** y ve a la pestaña **"Database Queries"**

4. **Busca consultas relacionadas con ACF**:
   - Busca: `get_field`, `acf`, `postmeta`
   - Deberías ver **20+ consultas** para los 3 banners

5. **Anota el número total de queries** y el tiempo

### Después de la Caché (Primera Carga)

1. **Limpia la caché nuevamente**:
   ```php
   wp transient delete --all
   ```

2. **Recarga la página principal**

3. **En Query Monitor → Database Queries**:
   - Busca: `get_transient`, `set_transient`
   - Deberías ver consultas para **guardar en caché**

4. **Anota el número de queries ACF** (debería ser similar al anterior, pero ahora se guardan en caché)

### Después de la Caché (Cargas Subsecuentes)

1. **Recarga la página principal** (sin limpiar caché)

2. **En Query Monitor → Database Queries**:
   - Busca: `get_transient`
   - Deberías ver **solo 3-6 consultas** para leer de caché
   - **NO deberías ver** consultas `get_field` para los banners

3. **Compara los resultados**:
   - **Antes**: 20+ queries ACF
   - **Después**: 3-6 queries de transients
   - **Reducción**: ~80-90%

---

## 🎯 Qué Buscar Específicamente

### 1. Consultas ACF Reducidas

**Antes (sin caché)**:
```
SELECT * FROM wp_postmeta WHERE post_id = 2873 AND meta_key LIKE 'slide_%'
SELECT * FROM wp_postmeta WHERE post_id = 2894 AND meta_key LIKE 'slide_vestidos_%'
SELECT * FROM wp_postmeta WHERE post_id = 2915 AND meta_key LIKE 'banner2_slide_%'
... (múltiples consultas por cada campo)
```

**Después (con caché)**:
```
SELECT option_value FROM wp_options WHERE option_name = '_transient_blessrom_banner_slide_2873'
SELECT option_value FROM wp_options WHERE option_name = '_transient_blessrom_banner_slide_vestidos_2894'
... (solo 3-6 consultas simples)
```

### 2. Tiempo de Ejecución

- **Antes**: ~50-100ms para leer campos ACF
- **Después**: ~5-10ms para leer de caché
- **Mejora**: ~80-90% más rápido

### 3. Número Total de Queries

- **Antes**: 100-150 queries totales
- **Después**: 80-120 queries totales
- **Reducción**: 20-30 queries menos

---

## 🔧 Verificar que la Caché Funciona

### Test 1: Primera Carga (Sin Caché)

1. Limpia transients: `wp transient delete --all`
2. Recarga la página
3. En Query Monitor, busca `set_transient`
4. Deberías ver consultas INSERT para guardar en caché

### Test 2: Segunda Carga (Con Caché)

1. Recarga la página (sin limpiar caché)
2. En Query Monitor, busca `get_transient`
3. Deberías ver consultas SELECT para leer de caché
4. **NO deberías ver** consultas `get_field` para los banners

### Test 3: Invalidación Automática

1. Edita una página de configuración (2873, 2894, o 2915)
2. Cambia algún campo ACF del banner
3. Guarda la página
4. Recarga la página principal
5. En Query Monitor, deberías ver `set_transient` nuevamente (la caché se regeneró)

---

## 📈 Métricas a Monitorear

### En Query Monitor → Database Queries

| Métrica | Sin Caché | Con Caché | Mejora |
|---------|-----------|-----------|--------|
| Queries ACF | 20-30 | 0-3 | ~90% |
| Queries Transients | 0 | 3-6 | - |
| Tiempo ACF | 50-100ms | 5-10ms | ~85% |
| Total Queries | 100-150 | 80-120 | ~20% |

### En Query Monitor → Timing

- **Tiempo total de página**: Debería reducirse en 40-80ms
- **Tiempo de PHP**: Debería reducirse en 30-60ms

---

## 🐛 Troubleshooting

### Problema: No veo reducción en queries

**Solución**:
1. Verifica que la caché esté activa: busca `get_transient` en Query Monitor
2. Limpia la caché y recarga: `wp transient delete --all`
3. Verifica que los IDs de página sean correctos (2873, 2894, 2915)

### Problema: La caché no se invalida al guardar

**Solución**:
1. Verifica que el hook `acf/save_post` esté activo
2. Revisa `app/setup.php` línea ~550
3. Verifica que ACF esté activo

### Problema: Query Monitor no muestra transients

**Solución**:
1. Query Monitor puede no mostrar todas las queries de transients
2. Usa la pestaña "Hooks & Actions" para verificar que `acf/save_post` se ejecute
3. Verifica manualmente en la base de datos:
   ```sql
   SELECT * FROM wp_options WHERE option_name LIKE '_transient_blessrom_banner_%';
   ```

---

## 💡 Tips Adicionales

### 1. Filtrar Queries en Query Monitor

- Haz clic en "Database Queries"
- Usa el filtro de búsqueda para buscar: `get_field`, `transient`, `acf`

### 2. Exportar Datos

- Query Monitor permite exportar datos en JSON
- Útil para comparar antes/después

### 3. Monitorear en Producción

- Query Monitor solo funciona para usuarios administradores
- No afecta el rendimiento para usuarios normales
- Puedes desactivarlo en producción si prefieres

### 4. Alternativas

Si Query Monitor no está disponible, puedes usar:
- **Debug Bar** (plugin similar)
- **New Relic** (monitoreo avanzado)
- **Query Monitor CLI** (para servidores)

---

## 📝 Checklist de Verificación

- [ ] Query Monitor instalado y activo
- [ ] Línea base establecida (queries sin caché)
- [ ] Caché funcionando (queries con `get_transient`)
- [ ] Reducción de queries ACF verificada
- [ ] Invalidación automática funcionando
- [ ] Tiempo de ejecución mejorado

---

## 🚀 Próximos Pasos

Una vez verificado que la caché funciona:

1. **Monitorear en producción** (si es posible)
2. **Ajustar tiempo de expiración** si es necesario (actualmente 1 hora)
3. **Implementar más mejoras** de la lista de mejoras técnicas
4. **Documentar métricas** para referencia futura

