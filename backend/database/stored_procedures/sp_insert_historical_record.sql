DELIMITER $$

CREATE PROCEDURE sp_insert_historical_record (
    IN p_user_id INT,
    IN p_date DATE,
    IN p_clima INT,
    IN p_afluencia INT,
    IN p_reservas INT,
    IN p_ocupacion DECIMAL(5,2),
    IN p_dia_festivo BOOLEAN,
    IN p_meta JSON
)
BEGIN
    INSERT INTO historical_records (
        user_id, date, clima, afluencia_turistica,
        num_reservas, porcentaje_ocupacion,
        dia_festivo, meta, created_at, updated_at
    ) VALUES (
        p_user_id, p_date, p_clima, p_afluencia,
        p_reservas, p_ocupacion,
        p_dia_festivo, p_meta, NOW(), NOW()
    );
END$$

DELIMITER ;
