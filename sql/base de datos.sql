CREATE DATABASE IF NOT EXISTS alquiler;
USE alquiler;

-- =========================================================
-- 1. TABLA DE USUARIOS (CLIENTES)
-- =========================================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    tipo_documento VARCHAR(30) NOT NULL,
    numero_documento VARCHAR(50) NOT NULL UNIQUE,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- 2. TABLA DE VEHICULOS
-- =========================================================
CREATE TABLE vehiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('Carro','Moto') NOT NULL,
    marca VARCHAR(100) NOT NULL,
    modelo VARCHAR(100) NOT NULL,
    anio INT,
    color VARCHAR(50),
    placa VARCHAR(20) NOT NULL UNIQUE,
    numero_motor VARCHAR(100),
    numero_chasis VARCHAR(100),
    combustible ENUM('Gasolina','Diesel','Eléctrico','Híbrido'),
    transmision ENUM('Manual','Automática'),
    kilometraje INT DEFAULT 0,
    capacidad INT,
    valor_dia DECIMAL(12,2) NOT NULL,
    estado ENUM(
        'Disponible',
        'Alquilado',
        'Mantenimiento',
        'Fuera de servicio'
    ) DEFAULT 'Disponible',
    foto VARCHAR(255),
    observaciones TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- 3. TABLA DE ALQUILERES
-- =========================================================
CREATE TABLE alquileres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    vehiculo_id INT NOT NULL,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
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
    
    CONSTRAINT fk_alquiler_usuario FOREIGN KEY (cliente_id) 
        REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_alquiler_vehiculo FOREIGN KEY (vehiculo_id) 
        REFERENCES vehiculos(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- 4. TABLA DE PAGOS
-- =========================================================
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alquiler_id INT NOT NULL,
    fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
    metodo ENUM(
        'Efectivo',
        'Tarjeta',
        'Transferencia',
        'Nequi',
        'Daviplata'
    ) NOT NULL,
    valor DECIMAL(12,2) NOT NULL,
    placa VARCHAR(20),
    comprobante_pago VARCHAR(255),
    
    CONSTRAINT fk_pago_alquiler FOREIGN KEY (alquiler_id) 
        REFERENCES alquileres(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- 5. TABLA DE MANTENIMIENTOS
-- =========================================================
CREATE TABLE mantenimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehiculo_id INT NOT NULL,
    fecha DATE NOT NULL,
    tipo VARCHAR(100),
    descripcion TEXT,
    costo DECIMAL(12,2),
    kilometraje INT,
    taller VARCHAR(150),
    
    CONSTRAINT fk_mantenimiento_vehiculo FOREIGN KEY (vehiculo_id) 
        REFERENCES vehiculos(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- 6. TABLA DE SEGUROS
-- =========================================================
CREATE TABLE seguros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehiculo_id INT NOT NULL,
    aseguradora VARCHAR(150) NOT NULL,
    numero_poliza VARCHAR(100) NOT NULL,
    fecha_inicio DATE,
    fecha_fin DATE,
    cobertura TEXT,
    
    CONSTRAINT fk_seguro_vehiculo FOREIGN KEY (vehiculo_id) 
        REFERENCES vehiculos(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- 7. TABLA DE MULTAS
-- =========================================================
CREATE TABLE multas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alquiler_id INT NOT NULL,
    fecha DATE NOT NULL,
    descripcion TEXT,
    valor DECIMAL(12,2) NOT NULL,
    estado ENUM(
        'Pendiente',
        'Pagada'
    ) DEFAULT 'Pendiente',
    
    CONSTRAINT fk_multa_alquiler FOREIGN KEY (alquiler_id) 
        REFERENCES alquileres(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- 8. TABLA DE ADMINISTRADORES (USUARIOS DEL SISTEMA)
-- =========================================================
CREATE TABLE administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    correo VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;