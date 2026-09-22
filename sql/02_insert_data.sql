-- ============================================================
-- MobiShopy - Mobile Shop Management System
-- Phase 2: DML - Sample Data
-- Run AFTER 01_create_db.sql
-- ============================================================

USE mobile_shop_db;

-- ------------------------------------------------------------
-- SUPPLIER data (passwords are hashed versions of 'pass1234')
-- NOTE: These hashes are for demo only.
--       Real registration uses PHP password_hash().
-- ------------------------------------------------------------
INSERT INTO SUPPLIER (supplier_name, phone, username, password) VALUES
('Samsung India Pvt Ltd',  '9000000001', 'samsung_sup',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Apple Distributors',     '9000000002', 'apple_sup',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('OnePlus Technologies',   '9000000003', 'oneplus_sup',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Xiaomi Corp',            '9000000004', 'xiaomi_sup',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Vivo Electronics',       '9000000005', 'vivo_sup',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ------------------------------------------------------------
-- CUSTOMER data
-- ------------------------------------------------------------
INSERT INTO CUSTOMER (customer_name, address, phone, username, password) VALUES
('Arjun Sharma',    'MG Road, Bangalore',       '9100000001', 'arjun_s',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Priya Nair',      'Park Street, Kolkata',     '9100000002', 'priya_n',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Rohit Verma',     'Linking Road, Mumbai',     '9100000003', 'rohit_v',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Sneha Patel',     'CG Road, Ahmedabad',       '9100000004', 'sneha_p',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Kiran Kumar',     'Jubilee Hills, Hyderabad', '9100000005', 'kiran_k',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Meena Reddy',     'Anna Nagar, Chennai',      '9100000006', 'meena_r',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Amit Singh',      'Connaught Place, Delhi',   '9100000007', 'amit_s',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Deepa Joseph',    'MG Road, Kochi',           '9100000008', 'deepa_j',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Naveen Rao',      'Brigade Road, Bangalore',  '9100000009', 'naveen_r',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Lakshmi Devi',    'Old Town, Bhubaneswar',    '9100000010', 'lakshmi_d', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- Note: All sample passwords above are hash of 'password' (for demo only)
-- Real users register via the frontend and get proper hashes.

-- ------------------------------------------------------------
-- MOBILE data
-- ------------------------------------------------------------
INSERT INTO MOBILE (supplier_id, brand, model, price, stock) VALUES
-- Samsung (supplier 1)
(1, 'Samsung', 'Galaxy S24',         79999.00, 15),
(1, 'Samsung', 'Galaxy A55',         38999.00, 20),
(1, 'Samsung', 'Galaxy M34',         18999.00, 25),
-- Apple (supplier 2)
(2, 'Apple',   'iPhone 15',         79999.00, 10),
(2, 'Apple',   'iPhone 14',         69999.00, 12),
(2, 'Apple',   'iPhone SE 3rd Gen', 49999.00,  8),
-- OnePlus (supplier 3)
(3, 'OnePlus', 'OnePlus 12',        64999.00, 18),
(3, 'OnePlus', 'OnePlus Nord CE4',  24999.00, 22),
-- Xiaomi (supplier 4)
(4, 'Xiaomi',  'Redmi Note 13 Pro', 27999.00, 30),
(4, 'Xiaomi',  'POCO X6 Pro',       22999.00, 25),
(4, 'Xiaomi',  'Redmi 13C',         10999.00, 40),
-- Vivo (supplier 5)
(5, 'Vivo',    'Vivo V30 Pro',      49999.00, 14),
(5, 'Vivo',    'Vivo Y200',         19999.00, 20),
(5, 'Vivo',    'Vivo T3 Lite',      13999.00, 35);

-- ------------------------------------------------------------
-- SALES data
-- Note: warranty is NULL for some sales (testing IS NULL)
-- ------------------------------------------------------------
INSERT INTO SALES (customer_id, mobile_id, quantity, amount, sales_date, warranty) VALUES
(1,  4, 1, 79999.00, '2026-01-05 10:30:00', '1 Year Apple Warranty'),
(2,  1, 1, 79999.00, '2026-01-08 14:00:00', '1 Year Samsung Warranty'),
(3,  7, 1, 64999.00, '2026-01-12 11:15:00', '1 Year OnePlus Warranty'),
(4,  9, 2, 55998.00, '2026-01-15 16:00:00', NULL),
(5,  3, 1, 18999.00, '2026-01-20 09:45:00', '6 Month Warranty'),
(6,  5, 1, 69999.00, '2026-02-02 13:30:00', '1 Year Apple Warranty'),
(7,  2, 1, 38999.00, '2026-02-10 12:00:00', '1 Year Samsung Warranty'),
(8, 12, 1, 49999.00, '2026-02-14 17:30:00', '1 Year Vivo Warranty'),
(9, 10, 1, 22999.00, '2026-02-18 10:00:00', NULL),
(10, 6, 1, 49999.00, '2026-02-22 15:00:00', '1 Year Apple Warranty'),
(1,  8, 1, 24999.00, '2026-03-01 11:00:00', '1 Year OnePlus Warranty'),
(2, 11, 2, 21998.00, '2026-03-05 14:30:00', NULL),
(3, 13, 1, 19999.00, '2026-03-10 09:00:00', '6 Month Warranty'),
(4,  4, 1, 79999.00, '2026-03-15 16:45:00', '1 Year Apple Warranty'),
(5,  1, 1, 79999.00, '2026-03-20 10:15:00', '1 Year Samsung Warranty'),
(6,  9, 1, 27999.00, '2026-03-25 13:00:00', NULL),
(7, 14, 2, 27998.00, '2026-04-02 11:30:00', '6 Month Warranty'),
(8,  7, 1, 64999.00, '2026-04-08 15:00:00', '1 Year OnePlus Warranty'),
(9,  3, 1, 18999.00, '2026-04-12 09:30:00', NULL),
(10, 2, 1, 38999.00, '2026-04-18 14:00:00', '1 Year Samsung Warranty'),
(1,  5, 1, 69999.00, '2026-05-03 10:45:00', '1 Year Apple Warranty'),
(2, 12, 1, 49999.00, '2026-05-10 16:00:00', '1 Year Vivo Warranty'),
(3,  1, 2,159998.00, '2026-05-15 12:30:00', '1 Year Samsung Warranty'),
(4, 10, 1, 22999.00, '2026-05-20 09:00:00', NULL),
(5,  8, 1, 24999.00, '2026-05-25 13:15:00', '1 Year OnePlus Warranty'),
(6,  4, 1, 79999.00, '2026-06-01 11:00:00', '1 Year Apple Warranty'),
(7,  9, 2, 55998.00, '2026-06-07 14:30:00', NULL),
(8,  6, 1, 49999.00, '2026-06-12 10:00:00', '1 Year Apple Warranty'),
(9, 11, 1, 10999.00, '2026-06-18 15:45:00', NULL),
(10, 7, 1, 64999.00, '2026-06-25 09:30:00', '1 Year OnePlus Warranty');
