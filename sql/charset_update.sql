-- Charset de la base
SELECT
    default_character_set_name,
    default_collation_name
FROM
    information_schema.SCHEMATA
WHERE
    schema_name = 'tu_base';

-- Charset de cada tabla
SELECT
    table_name,
    table_collation
FROM
    information_schema.TABLES
WHERE
    table_schema = 'tu_base';

-- Charset de cada columna (aquí suele estar el problema real)
SELECT
    table_name,
    column_name,
    character_set_name,
    collation_name
FROM
    information_schema.COLUMNS
WHERE
    table_schema = 'tu_base'
    AND character_set_name IS NOT NULL;

-- Convertir la base de datos
ALTER DATABASE tu_base CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Convertir cada tabla (esto sí arrastra las columnas)
ALTER TABLE messages CONVERT TO CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Generar los comandos automáticamente
SELECT
    CONCAT (
        'ALTER TABLE ',
        table_name,
        ' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;'
    ) AS stmt
FROM
    information_schema.TABLES
WHERE
    table_schema = 'tu_base';

-- Verificar que ya estés en el formato correcto
SHOW VARIABLES LIKE 'innodb_large_prefix';

SHOW VARIABLES LIKE 'innodb_file_format';

SHOW VARIABLES LIKE 'innodb_default_row_format';

-- Detectar de antemano cuáles índices van a explotar
SELECT DISTINCT
    t.table_name,
    s.column_name,
    s.index_name,
    c.character_maximum_length
FROM
    information_schema.STATISTICS s
    JOIN information_schema.TABLES t ON s.table_name = t.table_name
    AND s.table_schema = t.table_schema
    JOIN information_schema.COLUMNS c ON s.table_name = c.table_name
    AND s.column_name = c.column_name
    AND s.table_schema = c.table_schema
WHERE
    s.table_schema = 'tu_base'
    AND c.character_maximum_length > 191
    AND c.data_type IN ('varchar', 'char');
