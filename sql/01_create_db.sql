-- ============================================================
-- MobiShopy - Mobile Shop Management System
-- Phase 2: DDL - Create Database and Tabladmin
-- ============================================================
CREATE DATABASE IF NOT EXISTS mobile_shop_db;
USE mobile_shop_db;
-- ------------------------------------------------------------
-- Table: CUSTOMER
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS CUSTOMER (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
-- ------------------------------------------------------------
-- Table: SUPPLIER
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS SUPPLIER (
    supplier_id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
-- ------------------------------------------------------------
-- Table: MOBILE
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS MOBILE (
    mobile_id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_id INT NOT NULL,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL CHECK (price > 0),
    stock INT NOT NULL DEFAULT 0 CHECK (stock >= 0),
    FOREIGN KEY (supplier_id) REFERENCES SUPPLIER(supplier_id) ON DELETE RESTRICT ON UPDATE CASCADE
);
-- ------------------------------------------------------------
-- Table: SALES
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS SALES (
    sales_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    mobile_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    amount DECIMAL(10, 2) NOT NULL,
    sales_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    warranty VARCHAR(50) NULL,
    FOREIGN KEY (customer_id) REFERENCES CUSTOMER(customer_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (mobile_id) REFERENCES MOBILE(mobile_id) ON DELETE RESTRICT ON UPDATE CASCADE
);