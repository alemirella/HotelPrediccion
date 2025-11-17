USE ml_hotel;
DELIMITER $$

/*
 * SP: sp_insert_historical_record
 * Inserta un solo registro histórico.
 * Devuelve el id insertado en out_last_id (OUT param).
 */
CREATE PROCEDURE sp_insert_historical_record(
    IN p_user_id BIGINT,
    IN p_date DATE,
    IN p_clima INT,
    IN p_afluencia_turistica INT,
    IN p_num_reservas INT,
    IN p_porcentaje_ocupacion DECIMAL(5,2),
    IN p_dia_festivo TINYINT,
    IN p_meta LONGTEXT,
    OUT out_last_id BIGINT
)
BEGIN
    INSERT INTO historical_records (
        user_id, `date`, clima, afluencia_turistica, num_reservas,
        porcentaje_ocupacion, dia_festivo, meta, created_at, updated_at
    ) VALUES (
        p_user_id, p_date, p_clima, p_afluencia_turistica, p_num_reservas,
        p_porcentaje_ocupacion, p_dia_festivo, p_meta, NOW(), NOW()
    );

    SET out_last_id = LAST_INSERT_ID();
END$$

/*
 * SP: sp_bulk_insert_historical_from_temp
 * Copia filas desde una tabla temporal (tmp_import_historical_records)
 * hacia historical_records para el usuario indicado.
 *
 * Requiere que previamente hayas cargado tus filas a la tabla `tmp_import_historical_records`
 * (estructura propuesta abajo).
 *
 * La SP inserta solo las filas que tengan una fecha válida y valores numéricos básicos.
 * Devuelve la cantidad de filas insertadas en out_inserted_count (OUT param).
 */
CREATE PROCEDURE sp_bulk_insert_historical_from_temp(
    IN p_user_id BIGINT,
    OUT out_inserted_count INT
)
BEGIN7
    DECLARE v_count INT DEFAULT 0;

    /* Inserta solo filas válidas */
    INSERT INTO historical_records (
        user_id, `date`, clima, afluencia_turistica, num_reservas,
        porcentaje_ocupacion, dia_festivo, meta, created_at, updated_at
    )
    SELECT
        p_user_id,
        -- intentamos convertir la fecha; si ya viene en Y-m-d, STR_TO_DATE devuelve NULL si el formato no coincide,
        -- por eso hacemos COALESCE con la cadena misma si ya está en formato Y-m-d
        COALESCE(STR_TO_DATE(TRIM(date_str), '%d/%m/%Y'),
                 STR_TO_DATE(TRIM(date_str), '%d-%m-%Y'),
                 STR_TO_DATE(TRIM(date_str), '%Y-%m-%d'),
                 NULL) AS converted_date,
        clima,
        afluencia,
        reservas,
        ocupacion,
        IFNULL(dia_festivo, 0),
        meta,
        NOW(),
        NOW()
    FROM tmp_import_historical_records
    WHERE
        -- filtramos solo filas con fecha convertida (no nula) y campos numéricos mínimos
        ( (STR_TO_DATE(TRIM(date_str), '%d/%m/%Y') IS NOT NULL)
          OR (STR_TO_DATE(TRIM(date_str), '%d-%m-%Y') IS NOT NULL)
          OR (STR_TO_DATE(TRIM(date_str), '%Y-%m-%d') IS NOT NULL)
        )
        AND clima IS NOT NULL
        AND afluencia IS NOT NULL
        AND reservas IS NOT NULL;

    SET v_count = ROW_COUNT();
    SET out_inserted_count = v_count;
END$$

DELIMITER ;
