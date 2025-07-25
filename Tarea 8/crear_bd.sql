CREATE DATABASE IF NOT EXISTS ORGANIZACION;
USE ORGANIZACION;

CREATE TABLE PROYECTO (
    id_proyecto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion TEXT,
    presupuesto DECIMAL(10,2),
    fecha_inicio DATE,
    fecha_fin DATE
);

CREATE TABLE DONANTE (
    id_donante INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    email VARCHAR(100),
    direccion VARCHAR(255),
    telefono VARCHAR(20)
);

CREATE TABLE DONACION (
    id_donacion INT AUTO_INCREMENT PRIMARY KEY,
    monto DECIMAL(10,2),
    fecha DATE,
    id_proyecto INT,
    id_donante INT,
    FOREIGN KEY (id_proyecto) REFERENCES PROYECTO(id_proyecto),
    FOREIGN KEY (id_donante) REFERENCES DONANTE(id_donante)
);
