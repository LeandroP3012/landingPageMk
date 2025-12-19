-- =============================================
-- Base de datos para Landing Page MK
-- =============================================

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS bdpage CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE bdpage;

-- =============================================
-- Tabla: clients
-- =============================================
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    logo VARCHAR(500) DEFAULT NULL,
    short_description TEXT DEFAULT NULL,
    description TEXT DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: client_gallery (para galería de trabajos)
-- =============================================
CREATE TABLE IF NOT EXISTS client_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    caption VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Datos de ejemplo
-- =============================================
INSERT INTO clients (name, slug, logo, short_description, description, is_active) VALUES
('Empresa Demo', 'empresa-demo', 'storage/uploads/clients/cliente-demo.png', 'Descripción corta de la empresa demo', 'Descripción completa de la empresa demo con todos los detalles del proyecto realizado.', 1),
('Cliente Ejemplo', 'cliente-ejemplo', 'storage/uploads/clients/cliente-demo.png', 'Cliente del sector alimentario', 'Proyecto integral de branding y marketing para cliente del sector alimentario.', 1);
