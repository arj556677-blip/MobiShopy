-- ============================================================
-- MobiShopy - Mobile Shop Management System
-- Phase 3: Stored Function
-- ============================================================

USE mobile_shop_db;

DROP FUNCTION IF EXISTS GetMobileSalesCount;

DELIMITER $$

CREATE FUNCTION GetMobileSalesCount(p_mobile_id INT)
RETURNS INT
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_count INT;
    SELECT COUNT(*) INTO v_count
    FROM SALES
    WHERE mobile_id = p_mobile_id;
    RETURN v_count;
END$$

DELIMITER ;

-- Test the function
SELECT mobile_id, brand, model,
       GetMobileSalesCount(mobile_id) AS times_sold
FROM MOBILE
ORDER BY times_sold DESC;
