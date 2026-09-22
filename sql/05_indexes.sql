-- ============================================================
-- MobiShopy - Mobile Shop Management System
-- Phase 3: Query Optimization — EXPLAIN + Indexes
-- ============================================================

USE mobile_shop_db;

-- ------------------------------------------------------------
-- STEP 1: EXPLAIN before adding indexes
-- ------------------------------------------------------------
EXPLAIN
SELECT c.customer_name, m.brand, m.model, s.quantity, s.amount, s.sales_date
FROM SALES s
JOIN CUSTOMER c ON s.customer_id = c.customer_id
JOIN MOBILE   m ON s.mobile_id   = m.mobile_id;

-- ------------------------------------------------------------
-- STEP 2: Create indexes on frequently searched columns
-- ------------------------------------------------------------
CREATE INDEX idx_sales_customer  ON SALES(customer_id);
CREATE INDEX idx_sales_mobile    ON SALES(mobile_id);
CREATE INDEX idx_mobile_supplier ON MOBILE(supplier_id);

-- ------------------------------------------------------------
-- STEP 3: EXPLAIN after indexes (compare query plan)
-- ------------------------------------------------------------
EXPLAIN
SELECT c.customer_name, m.brand, m.model, s.quantity, s.amount, s.sales_date
FROM SALES s
JOIN CUSTOMER c ON s.customer_id = c.customer_id
JOIN MOBILE   m ON s.mobile_id   = m.mobile_id;
