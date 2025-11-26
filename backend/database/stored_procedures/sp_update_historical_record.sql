DELIMITER $$

CREATE PROCEDURE sp_update_historical_record (
    IN p_id INT,
    IN p_date DATE,
    IN p_clima INT,
    IN p_afluencia INT,
    IN p_reservas INT,
    IN p_ocupacion DECIMAL(5,2),
    IN p_dia_festivo BOOLEAN,
    IN p_meta JSON
)
BEGIN
    UPDATE historical_records
    SET
        date = p_date,
        clima = p_clima,
        afluencia_turistica = p_afluencia,
        num_reservas = p_reservas,
        porcentaje_ocupacion = p_ocupacion,
        dia_festivo = p_dia_festivo,
        meta = p_meta,
        updated_at = NOW()
    WHERE id = p_id;
END$$

DELIMITER ;
