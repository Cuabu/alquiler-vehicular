CREATE DATABASE alquiler;
USE alquiler;

-- ===========================
-- TABLA DE USUARIOS    
-- ===========================

CREATE TABLE usuarios (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,

    tipo_documento VARCHAR(30),
    numero_documento VARCHAR(50) UNIQUE,

    fecha_nacimiento DATE,

    telefono VARCHAR(30),
    correo VARCHAR(150),

    direccion TEXT,
    ciudad VARCHAR(100),

    numero_licencia VARCHAR(50),
    vencimiento_licencia DATE,

    foto_documento VARCHAR(255),
    foto_cliente VARCHAR(255),

    estado ENUM('Activo','Inactivo','Bloqueado') DEFAULT 'Activo',

    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);

-- ===========================
-- TABLA DE VEHICULOS
-- ===========================

CREATE TABLE vehiculos (

    id INT AUTO_INCREMENT PRIMARY KEY,

    tipo ENUM('Carro','Moto') NOT NULL,

    marca VARCHAR(100),
    modelo VARCHAR(100),

    anio INT,

    color VARCHAR(50),

    placa VARCHAR(20) UNIQUE,

    numero_motor VARCHAR(100),
    numero_chasis VARCHAR(100),

    combustible ENUM('Gasolina','Diesel','Eléctrico','Híbrido'),

    transmision ENUM('Manual','Automática'),

    kilometraje INT DEFAULT 0,

    capacidad INT,

    valor_dia DECIMAL(12,2),

    estado ENUM(
        'Disponible',
        'Alquilado',
        'Mantenimiento',
        'Fuera de servicio'
    ) DEFAULT 'Disponible',

    foto VARCHAR(255),

    observaciones TEXT,

    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);

-- ===========================
-- TABLA DE ALQUILERES
-- ===========================

CREATE TABLE alquileres (

    id INT AUTO_INCREMENT PRIMARY KEY,

    cliente_id INT NOT NULL,

    vehiculo_id INT NOT NULL,

    fecha_inicio DATETIME,

    fecha_fin DATETIME,

    dias INT,

    valor_dia DECIMAL(12,2),

    deposito DECIMAL(12,2),

    total DECIMAL(12,2),

    estado ENUM(
        'Reservado',
        'Activo',
        'Finalizado',
        'Cancelado'
    ) DEFAULT 'Reservado',

    observaciones TEXT,

    FOREIGN KEY(cliente_id)
    REFERENCES clientes(id),

    FOREIGN KEY(vehiculo_id)
    REFERENCES vehiculos(id)

);

-- ===========================
-- TABLA DE PAGOS
-- ===========================

CREATE TABLE pagos (

    id INT AUTO_INCREMENT PRIMARY KEY,

    alquiler_id INT,

    fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,

    metodo ENUM(
        'Efectivo',
        'Tarjeta',
        'Transferencia',
        'Nequi',
        'Daviplata'
    ),

    valor DECIMAL(12,2),

    placa VARCHAR(20),

    comprobante_pago VARCHAR(50),

    FOREIGN KEY(alquiler_id)
    REFERENCES alquileres(id)

);

-- ===========================
-- MANTENIMIENTOS
-- ===========================

CREATE TABLE mantenimientos (

    id INT AUTO_INCREMENT PRIMARY KEY,

    vehiculo_id INT,

    fecha DATE,

    tipo VARCHAR(100),

    descripcion TEXT,

    costo DECIMAL(12,2),

    kilometraje INT,

    taller VARCHAR(150),

    FOREIGN KEY(vehiculo_id)
    REFERENCES vehiculos(id)

);

-- ===========================
-- SEGUROS
-- ===========================

CREATE TABLE seguros (

    id INT AUTO_INCREMENT PRIMARY KEY,

    vehiculo_id INT,

    aseguradora VARCHAR(150),

    numero_poliza VARCHAR(100),

    fecha_inicio DATE,

    fecha_fin DATE,

    cobertura TEXT,

    FOREIGN KEY(vehiculo_id)
    REFERENCES vehiculos(id)

);

-- ===========================
-- MULTAS
-- ===========================

CREATE TABLE multas (

    id INT AUTO_INCREMENT PRIMARY KEY,

    alquiler_id INT,

    fecha DATE,

    descripcion TEXT,

    valor DECIMAL(12,2),

    estado ENUM(
        'Pendiente',
        'Pagada'
    ) DEFAULT 'Pendiente',

    FOREIGN KEY(alquiler_id)
    REFERENCES alquileres(id)

);

-- ===========================
-- USUARIOS DEL SISTEMA
-- ===========================

CREATE TABLE administradores (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nombre VARCHAR(100),

    apellido VARCHAR(100),

    usuario VARCHAR(50) UNIQUE,

    correo VARCHAR(150),

    password VARCHAR(255),

    rol ENUM(
        'Administrador',
        'Empleado'
    ) DEFAULT 'Empleado',

    estado ENUM(
        'Activo',
        'Inactivo'
    ) DEFAULT 'Activo',

    ultimo_acceso DATETIME,

    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);