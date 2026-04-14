-- =========================
-- BASE DE DATOS
-- =========================
CREATE DATABASE tienda_padel;
USE tienda_padel;

-- =========================
-- TABLA: producto (solo palas)
-- =========================
CREATE TABLE producto (
    cod VARCHAR(12) PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    nombre_corto VARCHAR(50),
    descripcion TEXT,
    marca VARCHAR(100),
    nivel ENUM('principiante', 'intermedio', 'avanzado'),
    forma ENUM('redonda', 'lagrima', 'diamante'),
    peso INT, -- en gramos
    pvp DECIMAL(10,2) NOT NULL,
    oferta BOOLEAN DEFAULT FALSE
);

-- =========================
-- TABLA: tienda
-- =========================
CREATE TABLE tienda (
    cod INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tlf VARCHAR(13)
);

-- =========================
-- TABLA: stock
-- =========================
CREATE TABLE stock (
    producto VARCHAR(12),
    tienda INT,
    unidades INT DEFAULT 0,

    PRIMARY KEY (producto, tienda),
    FOREIGN KEY (producto) REFERENCES producto(cod),
    FOREIGN KEY (tienda) REFERENCES tienda(cod)
);

-- =========================
-- TABLA: usuarios
-- =========================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(15),
    direccion TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    es_admin BOOLEAN DEFAULT FALSE
);