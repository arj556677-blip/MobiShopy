-- ============================================================
-- MobiShopy - Mobile Shop Management System
-- Phase 4: Transactions — SAVEPOINT, COMMIT, ROLLBACK
-- ============================================================

USE mobile_shop_db;

-- ------------------------------------------------------------
-- DEMO: Successful Transaction (stock is available)
-- ------------------------------------------------------------
START TRANSACTION;

    SAVEPOINT before_sale;

    -- Step 1: Insert the sale
    INSERT INTO SALES (customer_id, mobile_id, quantity, amount, sales_date, warranty)
    VALUES (2, 3, 1, 18999.00, NOW(), NULL);

    -- Step 2: Reduce stock
    UPDATE MOBILE
    SET stock = stock - 1
    WHERE mobile_id = 3;

COMMIT;

SELECT 'Transaction committed successfully.' AS result;

-- ------------------------------------------------------------
-- DEMO: Rollback Scenario (simulating an error)
-- ------------------------------------------------------------
START TRANSACTION;

    SAVEPOINT before_sale;

    INSERT INTO SALES (customer_id, mobile_id, quantity, amount, sales_date, warranty)
    VALUES (3, 5, 1, 69999.00, NOW(), '1 Year Warranty');

    UPDATE MOBILE
    SET stock = stock - 1
    WHERE mobile_id = 5;

    -- Simulate: something went wrong, roll back to savepoint
    ROLLBACK TO SAVEPOINT before_sale;

ROLLBACK;

SELECT 'Transaction rolled back — no changes saved.' AS result;

-- ------------------------------------------------------------
-- Verify: FOREIGN KEY integrity check
-- ------------------------------------------------------------

-- Sales with no matching customer (should return 0 rows)
SELECT s.sales_id FROM SALES s
WHERE s.customer_id NOT IN (SELECT customer_id FROM CUSTOMER);

-- Sales with no matching mobile (should return 0 rows)
SELECT s.sales_id FROM SALES s
WHERE s.mobile_id NOT IN (SELECT mobile_id FROM MOBILE);

-- Mobiles with no matching supplier (should return 0 rows)
SELECT m.mobile_id FROM MOBILE m
WHERE m.supplier_id NOT IN (SELECT supplier_id FROM SUPPLIER);
