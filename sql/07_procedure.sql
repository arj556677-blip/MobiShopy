-- ============================================================
-- MobiShopy - Mobile Shop Management System
-- Phase 3: Stored Procedure
-- ============================================================

USE mobile_shop_db;

DROP PROCEDURE IF EXISTS AddSale;

DELIMITER $$

CREATE PROCEDURE AddSale(
    IN p_customer_id INT,
    IN p_mobile_id   INT,
    IN p_quantity    INT,
    IN p_warranty    VARCHAR(50)
)
BEGIN
    DECLARE v_stock INT DEFAULT 0;
    DECLARE v_price DECIMAL(10,2) DEFAULT 0;

    -- Get current stock and price
    SELECT stock, price INTO v_stock, v_price
    FROM MOBILE
    WHERE mobile_id = p_mobile_id;

    -- Check if enough stock is available
    IF v_stock >= p_quantity THEN
        -- Insert the sale record
        INSERT INTO SALES (customer_id, mobile_id, quantity, amount, sales_date, warranty)
        VALUES (p_customer_id, p_mobile_id, p_quantity,
                v_price * p_quantity, NOW(), p_warranty);

        -- Reduce stock
        UPDATE MOBILE
        SET stock = stock - p_quantity
        WHERE mobile_id = p_mobile_id;

        SELECT 'SUCCESS' AS status, 'Purchase completed.' AS message;
    ELSE
        SELECT 'ERROR' AS status, 'Not enough stock available.' AS message;
    END IF;
END$$

DELIMITER ;

-- Test the procedure (buy 1 unit of mobile_id=3)
-- CALL AddSale(1, 3, 1, '6 Month Warranty');
