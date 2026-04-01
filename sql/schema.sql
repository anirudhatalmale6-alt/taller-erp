-- Taller ERP - Database Schema v2.0
-- Based on client's structure document

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS taller_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE taller_erp;

-- =============================================
-- CORE TABLES
-- =============================================

-- Users (login)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','operario','recepcion') DEFAULT 'recepcion',
    activo VARCHAR(2) DEFAULT 'SI',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Company configuration
CREATE TABLE IF NOT EXISTS configuracion (
    id INT PRIMARY KEY DEFAULT 1,
    empresa_nombre VARCHAR(200) DEFAULT 'Mi Taller',
    empresa_cif VARCHAR(20) DEFAULT '',
    empresa_direccion TEXT,
    empresa_telefono VARCHAR(20) DEFAULT '',
    empresa_email VARCHAR(150) DEFAULT '',
    empresa_logo VARCHAR(255) DEFAULT '',
    iva_porcentaje DECIMAL(5,2) DEFAULT 21.00,
    moneda VARCHAR(10) DEFAULT 'EUR',
    prefijo_deposito VARCHAR(10) DEFAULT 'DEP',
    prefijo_presupuesto VARCHAR(10) DEFAULT 'PRE',
    prefijo_orden VARCHAR(10) DEFAULT 'ORD',
    prefijo_albaran VARCHAR(10) DEFAULT 'ALB',
    prefijo_factura VARCHAR(10) DEFAULT 'FAC',
    prefijo_proyecto VARCHAR(10) DEFAULT 'PRY',
    prefijo_cita VARCHAR(10) DEFAULT 'CIT',
    siguiente_num_deposito INT DEFAULT 1,
    siguiente_num_presupuesto INT DEFAULT 1,
    siguiente_num_orden INT DEFAULT 1,
    siguiente_num_albaran INT DEFAULT 1,
    siguiente_num_factura INT DEFAULT 1,
    siguiente_num_proyecto INT DEFAULT 1,
    siguiente_num_cita INT DEFAULT 1,
    condiciones_presupuesto TEXT,
    condiciones_factura TEXT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CLIENTE
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(75) DEFAULT '',
    nif VARCHAR(20) DEFAULT '',
    direccion VARCHAR(100) DEFAULT '',
    cpostal VARCHAR(30) DEFAULT '',
    poblacion VARCHAR(50) DEFAULT '',
    provincia VARCHAR(50) DEFAULT '',
    pais VARCHAR(50) DEFAULT 'Espana',
    telefono VARCHAR(20) DEFAULT '',
    email VARCHAR(50) DEFAULT '',
    redsocial1 VARCHAR(75) DEFAULT '',
    redsocial2 VARCHAR(75) DEFAULT '',
    redsocial3 VARCHAR(75) DEFAULT '',
    redsocial4 VARCHAR(75) DEFAULT '',
    fecha_alta DATE DEFAULT NULL,
    fecha_modificacion DATE DEFAULT NULL,
    activo VARCHAR(2) DEFAULT 'SI',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nombre (nombre, apellidos),
    INDEX idx_nif (nif),
    INDEX idx_telefono (telefono)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- VEHICULO
CREATE TABLE IF NOT EXISTS vehiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(15) NOT NULL,
    num_chasis VARCHAR(75) DEFAULT '',
    marca VARCHAR(30) DEFAULT '',
    modelo VARCHAR(30) DEFAULT '',
    version_modelo VARCHAR(30) DEFAULT '',
    color VARCHAR(30) DEFAULT '',
    potencia VARCHAR(20) DEFAULT '',
    anio INT DEFAULT NULL,
    num_motor VARCHAR(50) DEFAULT '',
    emisiones VARCHAR(30) DEFAULT '',
    tipo_aceite VARCHAR(30) DEFAULT '',
    fecha_matriculacion DATE DEFAULT NULL,
    ano_fabricacion INT DEFAULT NULL,
    en_venta VARCHAR(2) DEFAULT 'NO',
    vendido VARCHAR(2) DEFAULT 'NO',
    sustitucion VARCHAR(2) DEFAULT 'NO',
    id_cliente INT DEFAULT NULL,
    activo VARCHAR(2) DEFAULT 'SI',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id),
    INDEX idx_matricula (matricula),
    INDEX idx_cliente (id_cliente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- VEH_MAN_DET (Vehicle Maintenance Tracking)
CREATE TABLE IF NOT EXISTS veh_man_det (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(15) NOT NULL,
    id_tipo_man VARCHAR(50) DEFAULT '',
    fecha DATE DEFAULT NULL,
    km INT DEFAULT 0,
    proxima_fecha DATE DEFAULT NULL,
    proximos_km INT DEFAULT 0,
    activo VARCHAR(2) DEFAULT 'SI',
    INDEX idx_matricula (matricula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- OPERARIO
CREATE TABLE IF NOT EXISTS operarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_iniciales VARCHAR(10) DEFAULT '',
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(75) DEFAULT '',
    telefono VARCHAR(20) DEFAULT '',
    email VARCHAR(50) DEFAULT '',
    clave VARCHAR(20) DEFAULT '',
    seccion VARCHAR(20) DEFAULT '',
    activo VARCHAR(2) DEFAULT 'SI',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TAREAS
CREATE TABLE IF NOT EXISTS tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_tarea VARCHAR(50) DEFAULT '',
    descripcion VARCHAR(150) DEFAULT '',
    seccion VARCHAR(50) DEFAULT '',
    familia VARCHAR(50) DEFAULT '',
    tiempo_previsto INT DEFAULT 0,
    activo VARCHAR(2) DEFAULT 'SI',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_seccion (seccion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- CITAS (Agenda)
-- =============================================
CREATE TABLE IF NOT EXISTS citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(20) NOT NULL,
    id_cliente INT DEFAULT NULL,
    id_vehiculo INT DEFAULT NULL,
    matricula VARCHAR(15) DEFAULT '',
    fecha_cita DATETIME NOT NULL,
    duracion_estimada INT DEFAULT 60,
    motivo TEXT,
    estado ENUM('pendiente','confirmada','en_curso','completada','cancelada') DEFAULT 'pendiente',
    id_operario INT DEFAULT NULL,
    notas TEXT,
    created_by INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_fecha (fecha_cita),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- DEPOSITO (DEP_CAB)
-- =============================================
CREATE TABLE IF NOT EXISTS dep_cab (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_deposito VARCHAR(50) NOT NULL,
    matricula VARCHAR(15) DEFAULT '',
    id_cliente INT DEFAULT NULL,
    kilometros INT DEFAULT 0,
    nivel_combustible VARCHAR(20) DEFAULT 'medio',
    fecha DATE DEFAULT NULL,
    hora TIME DEFAULT NULL,
    id_operario INT DEFAULT NULL,
    descripcion_trabajos VARCHAR(300) DEFAULT '',
    observaciones VARCHAR(300) DEFAULT '',
    acepta_presupuesto VARCHAR(2) DEFAULT 'SI',
    acepta_ocultos VARCHAR(2) DEFAULT 'NO',
    acepta_piezas VARCHAR(2) DEFAULT 'SI',
    acepta_conduccion VARCHAR(2) DEFAULT 'NO',
    acepta_piezas_usadas VARCHAR(2) DEFAULT 'NO',
    firma_resguardo TEXT,
    firma_presupuesto TEXT,
    activo VARCHAR(2) DEFAULT 'SI',
    created_by INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_matricula (matricula),
    INDEX idx_deposito (id_deposito)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- PRESUPUESTO / ORDEN (PRE_ORD_CAB)
-- Presupuesto y Orden comparten la misma tabla
-- =============================================
CREATE TABLE IF NOT EXISTS pre_ord_cab (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pre_ord VARCHAR(50) NOT NULL,
    tipo_doc ENUM('PRESUPUESTO','ORDEN') DEFAULT 'PRESUPUESTO',
    matricula VARCHAR(15) DEFAULT '',
    id_deposito VARCHAR(50) DEFAULT '',
    id_cliente INT DEFAULT NULL,
    importe DECIMAL(12,2) DEFAULT 0,
    firma TEXT,
    aceptado VARCHAR(2) DEFAULT 'NO',
    activo VARCHAR(2) DEFAULT 'SI',
    iva_porcentaje DECIMAL(5,2) DEFAULT 21.00,
    iva_importe DECIMAL(12,2) DEFAULT 0,
    descuento_porcentaje DECIMAL(5,2) DEFAULT 0,
    descuento_importe DECIMAL(12,2) DEFAULT 0,
    total DECIMAL(12,2) DEFAULT 0,
    condiciones TEXT,
    notas TEXT,
    fecha DATE DEFAULT NULL,
    created_by INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_pre_ord (id_pre_ord),
    INDEX idx_tipo (tipo_doc),
    INDEX idx_matricula (matricula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PRE_ORD_DET (Detail lines)
CREATE TABLE IF NOT EXISTS pre_ord_det (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pre_det INT DEFAULT NULL,
    id_pre_ord VARCHAR(50) NOT NULL,
    id_tarea INT DEFAULT NULL,
    id_tarticulo INT DEFAULT NULL,
    cantidad DECIMAL(10,2) DEFAULT 1,
    descripcion TEXT,
    precio DECIMAL(10,2) DEFAULT 0,
    importe DECIMAL(12,2) DEFAULT 0,
    precio_coste DECIMAL(10,2) DEFAULT 0,
    fecha DATE DEFAULT NULL,
    tiempo_asignado INT DEFAULT 0,
    tiempo_realizado INT DEFAULT 0,
    finalizado VARCHAR(2) DEFAULT 'NO',
    orden INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_pre_ord (id_pre_ord)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PRE_ORD_APU (Apuntes/notas por linea de detalle)
CREATE TABLE IF NOT EXISTS pre_ord_apu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_apunte INT DEFAULT NULL,
    id_pre_ord_det INT DEFAULT NULL,
    id_tarea INT DEFAULT NULL,
    descripcion TEXT,
    fecha DATE DEFAULT NULL,
    tiempo_asignado INT DEFAULT 0,
    tiempo_realizado INT DEFAULT 0,
    importe DECIMAL(12,2) DEFAULT 0,
    id_iniciales VARCHAR(10) DEFAULT '',
    incidencia VARCHAR(2) DEFAULT 'NO',
    finalizado VARCHAR(2) DEFAULT 'NO',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_det (id_pre_ord_det)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- ALBARAN (ALBARAN_CAB)
-- =============================================
CREATE TABLE IF NOT EXISTS albaran_cab (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_albaran VARCHAR(50) NOT NULL,
    tipo_doc ENUM('ORDEN','PRESUPUESTO') DEFAULT 'PRESUPUESTO',
    matricula VARCHAR(15) DEFAULT '',
    id_deposito VARCHAR(50) DEFAULT '',
    id_cliente INT DEFAULT NULL,
    importe DECIMAL(12,2) DEFAULT 0,
    firma TEXT,
    aceptado VARCHAR(2) DEFAULT 'NO',
    activo VARCHAR(2) DEFAULT 'SI',
    iva_porcentaje DECIMAL(5,2) DEFAULT 21.00,
    iva_importe DECIMAL(12,2) DEFAULT 0,
    descuento_porcentaje DECIMAL(5,2) DEFAULT 0,
    descuento_importe DECIMAL(12,2) DEFAULT 0,
    total DECIMAL(12,2) DEFAULT 0,
    notas TEXT,
    fecha DATE DEFAULT NULL,
    created_by INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_albaran (id_albaran),
    INDEX idx_matricula (matricula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ALBARAN_DET
CREATE TABLE IF NOT EXISTS albaran_det (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_ord_det INT DEFAULT NULL,
    id_pre_det INT DEFAULT NULL,
    id_tarea INT DEFAULT NULL,
    id_tarticulo INT DEFAULT NULL,
    id_albaran VARCHAR(50) NOT NULL,
    cantidad DECIMAL(10,2) DEFAULT 1,
    descripcion TEXT,
    precio DECIMAL(10,2) DEFAULT 0,
    importe DECIMAL(12,2) DEFAULT 0,
    precio_coste DECIMAL(10,2) DEFAULT 0,
    fecha DATE DEFAULT NULL,
    tiempo_asignado INT DEFAULT 0,
    tiempo_realizado INT DEFAULT 0,
    finalizado VARCHAR(2) DEFAULT 'NO',
    orden INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_albaran (id_albaran)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ALBARAN_APU
CREATE TABLE IF NOT EXISTS albaran_apu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_apunte INT DEFAULT NULL,
    id_albaran_det INT DEFAULT NULL,
    id_tarea INT DEFAULT NULL,
    descripcion TEXT,
    fecha DATE DEFAULT NULL,
    tiempo_asignado INT DEFAULT 0,
    tiempo_realizado INT DEFAULT 0,
    importe DECIMAL(12,2) DEFAULT 0,
    id_iniciales VARCHAR(10) DEFAULT '',
    incidencia VARCHAR(2) DEFAULT 'NO',
    finalizado VARCHAR(2) DEFAULT 'NO',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_det (id_albaran_det)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- FACTURA (FACTURA_CAB)
-- =============================================
CREATE TABLE IF NOT EXISTS factura_cab (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_factura VARCHAR(50) NOT NULL,
    tipo_doc ENUM('ORDEN','PRESUPUESTO') DEFAULT 'PRESUPUESTO',
    matricula VARCHAR(15) DEFAULT '',
    id_deposito VARCHAR(50) DEFAULT '',
    id_cliente INT DEFAULT NULL,
    importe DECIMAL(12,2) DEFAULT 0,
    firma TEXT,
    aceptado VARCHAR(2) DEFAULT 'NO',
    activo VARCHAR(2) DEFAULT 'SI',
    iva_porcentaje DECIMAL(5,2) DEFAULT 21.00,
    iva_importe DECIMAL(12,2) DEFAULT 0,
    descuento_porcentaje DECIMAL(5,2) DEFAULT 0,
    descuento_importe DECIMAL(12,2) DEFAULT 0,
    total DECIMAL(12,2) DEFAULT 0,
    forma_pago VARCHAR(50) DEFAULT 'efectivo',
    condiciones TEXT,
    notas TEXT,
    fecha DATE DEFAULT NULL,
    fecha_vencimiento DATE DEFAULT NULL,
    estado ENUM('borrador','enviada','pagada','vencida','anulada') DEFAULT 'borrador',
    created_by INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_factura (id_factura),
    INDEX idx_matricula (matricula),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- FACTURA_DET
CREATE TABLE IF NOT EXISTS factura_det (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_ord_det INT DEFAULT NULL,
    id_pre_det INT DEFAULT NULL,
    id_tarea INT DEFAULT NULL,
    id_tarticulo INT DEFAULT NULL,
    id_factura VARCHAR(50) NOT NULL,
    cantidad DECIMAL(10,2) DEFAULT 1,
    descripcion TEXT,
    precio DECIMAL(10,2) DEFAULT 0,
    importe DECIMAL(12,2) DEFAULT 0,
    precio_coste DECIMAL(10,2) DEFAULT 0,
    fecha DATE DEFAULT NULL,
    tiempo_asignado INT DEFAULT 0,
    tiempo_realizado INT DEFAULT 0,
    finalizado VARCHAR(2) DEFAULT 'NO',
    orden INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_factura (id_factura)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- FACTURA_APU
CREATE TABLE IF NOT EXISTS factura_apu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_apunte INT DEFAULT NULL,
    id_factura_det INT DEFAULT NULL,
    id_tarea INT DEFAULT NULL,
    descripcion TEXT,
    fecha DATE DEFAULT NULL,
    tiempo_asignado INT DEFAULT 0,
    tiempo_realizado INT DEFAULT 0,
    importe DECIMAL(12,2) DEFAULT 0,
    id_iniciales VARCHAR(10) DEFAULT '',
    incidencia VARCHAR(2) DEFAULT 'NO',
    finalizado VARCHAR(2) DEFAULT 'NO',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_det (id_factura_det)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- PROYECTO (PROYECTO_CAB)
-- =============================================
CREATE TABLE IF NOT EXISTS proyecto_cab (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_proyecto VARCHAR(50) NOT NULL,
    descripcion VARCHAR(75) DEFAULT '',
    matricula VARCHAR(15) DEFAULT '',
    num_doc_presupuesto VARCHAR(20) DEFAULT '',
    num_doc_factura VARCHAR(20) DEFAULT '',
    importe DECIMAL(12,2) DEFAULT 0,
    num_mto_mecanica INT DEFAULT 0,
    num_mto_chapa INT DEFAULT 0,
    num_mto_pintura INT DEFAULT 0,
    num_mto_tapiceria INT DEFAULT 0,
    num_mto_usadas_mecanica INT DEFAULT 0,
    num_mto_usadas_chapa INT DEFAULT 0,
    num_mto_usadas_pintura INT DEFAULT 0,
    num_mto_usadas_tapiceria INT DEFAULT 0,
    id_cliente INT DEFAULT NULL,
    estado ENUM('planificacion','en_curso','pausado','completado','cancelado') DEFAULT 'planificacion',
    progreso INT DEFAULT 0,
    activo VARCHAR(2) DEFAULT 'SI',
    iva_porcentaje DECIMAL(5,2) DEFAULT 21.00,
    iva_importe DECIMAL(12,2) DEFAULT 0,
    total DECIMAL(12,2) DEFAULT 0,
    fecha DATE DEFAULT NULL,
    notas TEXT,
    created_by INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_proyecto (id_proyecto),
    INDEX idx_matricula (matricula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PROYECTO_DET
CREATE TABLE IF NOT EXISTS proyecto_det (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_proyecto_det INT DEFAULT NULL,
    id_proyecto VARCHAR(50) NOT NULL,
    id_tarea INT DEFAULT NULL,
    descripcion TEXT,
    fecha DATE DEFAULT NULL,
    tiempo_asignado INT DEFAULT 0,
    tiempo_realizado INT DEFAULT 0,
    importe DECIMAL(12,2) DEFAULT 0,
    finalizado VARCHAR(2) DEFAULT 'NO',
    orden INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_proyecto (id_proyecto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PROYECTO_APU
CREATE TABLE IF NOT EXISTS proyecto_apu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_apunte INT DEFAULT NULL,
    id_proyecto_det INT DEFAULT NULL,
    id_tarea INT DEFAULT NULL,
    descripcion TEXT,
    fecha DATE DEFAULT NULL,
    tiempo_asignado INT DEFAULT 0,
    tiempo_realizado INT DEFAULT 0,
    importe DECIMAL(12,2) DEFAULT 0,
    id_iniciales VARCHAR(10) DEFAULT '',
    incidencia VARCHAR(2) DEFAULT 'NO',
    finalizado VARCHAR(2) DEFAULT 'NO',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_det (id_proyecto_det)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- FOTOS (vinculadas por matricula)
-- =============================================
CREATE TABLE IF NOT EXISTS fotos_cab (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_foto VARCHAR(50) DEFAULT '',
    matricula VARCHAR(15) NOT NULL,
    descripcion TEXT,
    fecha DATE DEFAULT NULL,
    hora TIME DEFAULT NULL,
    imagen VARCHAR(255) DEFAULT '',
    activo VARCHAR(2) DEFAULT 'SI',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_matricula (matricula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- DOCUMENTOS (vinculados por matricula)
-- =============================================
CREATE TABLE IF NOT EXISTS doc_cab (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_doc VARCHAR(50) DEFAULT '',
    matricula VARCHAR(15) NOT NULL,
    descripcion TEXT,
    fecha DATE DEFAULT NULL,
    hora TIME DEFAULT NULL,
    imagen VARCHAR(255) DEFAULT '',
    activo VARCHAR(2) DEFAULT 'SI',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_matricula (matricula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- SEED DATA
-- =============================================

-- Default admin user (password: admin123)
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador', 'admin@taller.com', '$2y$10$teKZZaPeFqBQ9z/nRO3SNecgAW/iOv6XzyyEaRDfZPwDpEjEIACLy', 'admin');

-- Default configuration
INSERT INTO configuracion (id, empresa_nombre, empresa_cif, empresa_direccion, empresa_telefono, empresa_email, iva_porcentaje, condiciones_presupuesto, condiciones_factura) VALUES
(1, 'Mi Taller Mecanico', 'B12345678', 'Calle Principal 1, 28001 Madrid', '912345678', 'info@mitaller.com', 21.00,
'Presupuesto valido durante 30 dias. Los precios incluyen mano de obra y materiales salvo indicacion contraria.',
'Forma de pago: efectivo, tarjeta o transferencia bancaria. Vencimiento a 30 dias.');

-- Sample tasks
INSERT INTO tareas (id_tarea, descripcion, seccion, familia, tiempo_previsto) VALUES
('REV-01', 'Revision general', 'mecanica', 'revision', 60),
('ACE-01', 'Cambio de aceite y filtro', 'mecanica', 'mantenimiento', 30),
('FRE-01', 'Cambio pastillas de freno (eje)', 'mecanica', 'frenos', 60),
('NEU-01', 'Cambio neumaticos (4 uds)', 'mecanica', 'neumaticos', 60),
('BAT-01', 'Cambio bateria', 'electricidad', 'electricidad', 15),
('DIS-01', 'Diagnosis electronica', 'mecanica', 'diagnosis', 30),
('EMB-01', 'Cambio embrague', 'mecanica', 'mecanica', 240),
('CHA-01', 'Reparacion chapa', 'chapa', 'chapa', 120),
('PIN-01', 'Pintura panel', 'pintura', 'pintura', 180),
('TAP-01', 'Tapizado asiento', 'tapiceria', 'tapiceria', 120);

-- Sample operario
INSERT INTO operarios (id_iniciales, nombre, apellidos, seccion) VALUES
('JG', 'Juan', 'Garcia Lopez', 'mecanica');

SET FOREIGN_KEY_CHECKS = 1;
