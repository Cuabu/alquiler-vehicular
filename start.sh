#!/bin/bash

echo "======================================"
echo " CREANDO ESTRUCTURA DEL PROYECTO"
echo " Sistema de Alquiler de Vehículos"
echo "======================================"

PROYECTO="alquiler_vehiculos"

mkdir -p "$PROYECTO"

cd "$PROYECTO" || exit

# Archivos principales
touch index.php
touch dashboard.php
touch login.php
touch logout.php

# Configuración
mkdir -p config
touch config/conexion.php

# Clientes
mkdir -p clientes
touch clientes/agregar.php
touch clientes/listar.php
touch clientes/editar.php
touch clientes/eliminar.php

# Vehículos
mkdir -p vehiculos
touch vehiculos/agregar.php
touch vehiculos/listar.php
touch vehiculos/editar.php
touch vehiculos/eliminar.php

# Alquileres
mkdir -p alquileres
touch alquileres/nuevo.php
touch alquileres/listar.php
touch alquileres/editar.php
touch alquileres/devolver.php
touch alquileres/eliminar.php

# Pagos
mkdir -p pagos
touch pagos/registrar.php
touch pagos/historial.php
touch pagos/eliminar.php

# Seguros
mkdir -p seguros
touch seguros/agregar.php
touch seguros/listar.php
touch seguros/editar.php
touch seguros/eliminar.php

# Mantenimiento
mkdir -p mantenimiento
touch mantenimiento/agregar.php
touch mantenimiento/listar.php
touch mantenimiento/editar.php
touch mantenimiento/eliminar.php

# Multas
mkdir -p multas
touch multas/agregar.php
touch multas/listar.php
touch multas/editar.php
touch multas/eliminar.php

# Administradores
mkdir -p administradores
touch administradores/agregar.php
touch administradores/listar.php
touch administradores/editar.php
touch administradores/eliminar.php

# Reportes
mkdir -p reportes
touch reportes/clientes.php
touch reportes/vehiculos.php
touch reportes/alquileres.php
touch reportes/pagos.php

# API
mkdir -p api

# Recursos
mkdir -p assets/css
mkdir -p assets/js
mkdir -p assets/img
mkdir -p assets/icons

# Subidas
mkdir -p uploads/clientes
mkdir -p uploads/vehiculos
mkdir -p uploads/documentos
mkdir -p uploads/licencias
mkdir -p uploads/contratos

echo
echo "======================================"
echo " Proyecto creado correctamente"
echo "======================================"

tree . 2>/dev/null || find .
