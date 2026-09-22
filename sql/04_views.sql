-- ============================================================
-- MobiShopy - Mobile Shop Management System
-- Phase 3: View
-- ============================================================

USE mobile_shop_db;

-- Drop if exists, then recreate
DROP VIEW IF EXISTS Mobile_Sales_Summary;

CREATE VIEW Mobile_Sales_Summary AS
SELECT
    m.mobile_id,
    m.brand,
    m.model,
    m.price,
    m.stock,
    COALESCE(SUM(s.quantity), 0) AS total_qty_sold,
    COALESCE(SUM(s.amount),   0) AS total_revenue
FROM MOBILE m
LEFT JOIN SALES s ON m.mobile_id = s.mobile_id
GROUP BY m.mobile_id, m.brand, m.model, m.price, m.stock;

-- Test the view
SELECT * FROM Mobile_Sales_Summary ORDER BY total_revenue DESC;
