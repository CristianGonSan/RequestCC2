UPDATE money_requests
SET
    paid_at = updated_at
WHERE
    status = 'S05'
    AND paid_at IS NULL;
