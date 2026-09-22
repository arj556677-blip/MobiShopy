-- ============================================================
-- MobiShopy - Mobile Shop Management System
-- Phase 3: Basic Queries, Joins, Subqueries, Aggregates
-- ============================================================

USE mobile_shop_db;

-- ------------------------------------------------------------
-- BASIC QUERIES
-- ------------------------------------------------------------

-- 1. Show all mobiles currently in stock
SELECT brand, model, price, stock
FROM MOBILE
WHERE stock > 0
ORDER BY brand ASC;

-- 2. Show all mobiles sorted by price (low to high)
SELECT brand, model, price, stock
FROM MOBILE
ORDER BY price ASC;

-- 3. Recent sales (latest first)
SELECT sales_id, customer_id, mobile_id, quantity, amount, sales_date
FROM SALES
ORDER BY sales_date DESC
LIMIT 10;

-- 4. Filter mobiles by brand
SELECT brand, model, price, stock
FROM MOBILE
WHERE brand = 'Samsung';

-- ------------------------------------------------------------
-- AGGREGATE FUNCTIONS
-- ------------------------------------------------------------

-- 5. Count of sales per mobile
SELECT m.brand, m.model, COUNT(s.sales_id) AS total_sales
FROM MOBILE m
LEFT JOIN SALES s ON m.mobile_id = s.mobile_id
GROUP BY m.mobile_id, m.brand, m.model
ORDER BY total_sales DESC;

-- 6. Average sale amount
SELECT AVG(amount) AS avg_sale_amount FROM SALES;

-- 7. Total revenue per supplier
SELECT su.supplier_name, SUM(s.amount) AS total_revenue
FROM SUPPLIER su
JOIN MOBILE m  ON su.supplier_id = m.supplier_id
JOIN SALES  s  ON m.mobile_id    = s.mobile_id
GROUP BY su.supplier_id, su.supplier_name
ORDER BY total_revenue DESC;

-- 8. NULL handling — sales without warranty
SELECT s.sales_id, c.customer_name, m.model, s.warranty
FROM SALES s
JOIN CUSTOMER c ON s.customer_id = c.customer_id
JOIN MOBILE   m ON s.mobile_id   = m.mobile_id
WHERE s.warranty IS NULL;

-- 9. Sales with warranty (IS NOT NULL)
SELECT s.sales_id, c.customer_name, m.model, s.warranty
FROM SALES s
JOIN CUSTOMER c ON s.customer_id = c.customer_id
JOIN MOBILE   m ON s.mobile_id   = m.mobile_id
WHERE s.warranty IS NOT NULL;

-- ------------------------------------------------------------
-- JOINS
-- ------------------------------------------------------------

-- 10. Bill / Purchase History — 3-table join
SELECT c.customer_name, m.brand, m.model,
       s.quantity, s.amount, s.sales_date, s.warranty
FROM SALES s
JOIN CUSTOMER c ON s.customer_id = c.customer_id
JOIN MOBILE   m ON s.mobile_id   = m.mobile_id
ORDER BY s.sales_date DESC;

-- 11. Supplier + their supplied mobiles
SELECT su.supplier_name, m.brand, m.model, m.price, m.stock
FROM SUPPLIER su
JOIN MOBILE m ON su.supplier_id = m.supplier_id
ORDER BY su.supplier_name, m.brand;

-- ------------------------------------------------------------
-- SUBQUERIES / NESTED QUERIES
-- ------------------------------------------------------------

-- 12. Mobiles priced above the average price
SELECT brand, model, price
FROM MOBILE
WHERE price > (SELECT AVG(price) FROM MOBILE)
ORDER BY price DESC;

-- 13. Customers whose total purchase amount is above average
SELECT c.customer_name, SUM(s.amount) AS total_spent
FROM CUSTOMER c
JOIN SALES s ON c.customer_id = s.customer_id
GROUP BY c.customer_id, c.customer_name
HAVING SUM(s.amount) > (SELECT AVG(amount) FROM SALES)
ORDER BY total_spent DESC;
