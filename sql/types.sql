UPDATE requests r
JOIN types t ON r.type_key = t.key
SET
    r.type_id = t.id
WHERE
    r.type_key IS NOT NULL;
