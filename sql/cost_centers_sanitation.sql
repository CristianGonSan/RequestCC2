-- Verificación previa
SELECT
    r.id,
    r.cost_center_name AS original_requests,
    cc.name AS original_cost_centers,
    REGEXP_REPLACE (UPPER(r.cost_center_name), '[^A-Z0-9]', '') AS normalizado_requests,
    REGEXP_REPLACE (
        UPPER(REGEXP_REPLACE (cc.name, '\s*-\s*FINALIZADO$', '')),
        '[^A-Z0-9]',
        ''
    ) AS normalizado_cc,
    cc.id AS cost_center_id_encontrado
FROM
    requests r
    LEFT JOIN cost_centers cc ON REGEXP_REPLACE (UPPER(r.cost_center_name), '[^A-Z0-9]', '') = REGEXP_REPLACE (
        UPPER(REGEXP_REPLACE (cc.name, '\s*-\s*FINALIZADO$', '')),
        '[^A-Z0-9]',
        ''
    )
WHERE
    r.cost_center_name IS NOT NULL;

-- Consulta para aislar solo los registros que no matchearon
SELECT
    r.id,
    r.cost_center_name AS original_requests,
    REGEXP_REPLACE (UPPER(r.cost_center_name), '[^A-Z0-9]', '') AS normalizado_requests
FROM
    requests r
    LEFT JOIN cost_centers cc ON REGEXP_REPLACE (UPPER(r.cost_center_name), '[^A-Z0-9]', '') = REGEXP_REPLACE (
        UPPER(REGEXP_REPLACE (cc.name, '\s*-\s*FINALIZADO$', '')),
        '[^A-Z0-9]',
        ''
    )
WHERE
    r.cost_center_name IS NOT NULL
    AND cc.id IS NULL
ORDER BY
    r.cost_center_name;

-- Agrupa los registros que no matchearon para ver si hay patrones
SELECT
    r.cost_center_name AS original,
    REGEXP_REPLACE (UPPER(r.cost_center_name), '[^A-Z0-9]', '') AS normalizado,
    COUNT(*) AS cantidad
FROM
    requests r
    LEFT JOIN cost_centers cc ON REGEXP_REPLACE (UPPER(r.cost_center_name), '[^A-Z0-9]', '') = REGEXP_REPLACE (
        UPPER(REGEXP_REPLACE (cc.name, '\s*-\s*FINALIZADO$', '')),
        '[^A-Z0-9]',
        ''
    )
WHERE
    r.cost_center_name IS NOT NULL
    AND cc.id IS NULL
GROUP BY
    r.cost_center_name
ORDER BY
    cantidad DESC;

-- Actualización de la tabla requests para asociar el cost_center_id correspondiente
UPDATE requests r
JOIN cost_centers cc ON REGEXP_REPLACE (UPPER(r.cost_center_name), '[^A-Z0-9]', '') = REGEXP_REPLACE (
    UPPER(REGEXP_REPLACE (cc.name, '\s*-\s*FINALIZADO$', '')),
    '[^A-Z0-9]',
    ''
)
SET
    r.cost_center_id = cc.id
WHERE
    r.cost_center_name IS NOT NULL;
