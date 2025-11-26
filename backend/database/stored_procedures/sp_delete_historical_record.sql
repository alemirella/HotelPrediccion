DELIMITER $$

CREATE PROCEDURE sp_delete_historical_record (
    IN p_id INT
)
BEGIN
    DELETE FROM historical_records
    WHERE id = p_id;
END$$

DELIMITER ;
