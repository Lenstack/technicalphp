-- Create database
CREATE DATABASE ClinicaDB;
GO

-- Use the created database
USE ClinicaDB;
GO

-- Create table Departamentos
CREATE TABLE Departamentos
(
    DepartamentoID INT PRIMARY KEY IDENTITY(1,1),
    Descripcion VARCHAR(100) NOT NULL
);

-- Create table Municipios
CREATE TABLE Municipios
(
    MunicipioID INT PRIMARY KEY IDENTITY(1,1),
    Descripcion VARCHAR(100) NOT NULL,
    DepartamentoID INT NOT NULL,
    CONSTRAINT FK_Municipios_Departamentos FOREIGN KEY (DepartamentoID) REFERENCES Departamentos(DepartamentoID)
);

-- Create table Pacientes
CREATE TABLE Pacientes
(
    PacienteID INT PRIMARY KEY IDENTITY(1,1),
    Nombre VARCHAR(100) NOT NULL,
    Edad VARCHAR(10) NOT NULL,
    Genero VARCHAR(10) NOT NULL,
    DepartamentoID INT NOT NULL,
    MunicipioID INT NOT NULL,
    CONSTRAINT FK_Pacientes_Departamentos FOREIGN KEY (DepartamentoID) REFERENCES Departamentos(DepartamentoID),
    CONSTRAINT FK_Pacientes_Municipios FOREIGN KEY (MunicipioID) REFERENCES Municipios(MunicipioID)
);


--Insert data into Departamentos
INSERT INTO Departamentos (Descripcion) VALUES ('TOLIMA');

--Insert data into Municipios
INSERT INTO Municipios (Descripcion, DepartamentoID) VALUES ('IBAGUE', 1);

--Insert data into Pacientes
INSERT INTO Pacientes (Nombre, Edad, Genero, DepartamentoID, MunicipioID) VALUES ('Juan', '25', 'Masculino', 1, 1);

