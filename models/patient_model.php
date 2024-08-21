<?php

class PatientModel
{
    private $sqlServer;
    private $tableName = 'Pacientes';

    public function __construct($sqlServer)
    {
        $this->sqlServer = $sqlServer->GetConnection();
    }

    public function GetPatients()
    {
        $query = "SELECT PacienteID, Nombre, Edad, Genero, Departamentos.Descripcion AS Departamento, Municipios.Descripcion AS Municipio
                FROM Pacientes
                JOIN Departamentos ON Pacientes.DepartamentoID = Departamentos.DepartamentoID
                JOIN Municipios ON Pacientes.MunicipioID = Municipios.MunicipioID";
        $stmt = $this->sqlServer->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetPatient($id)
    {
        $query = "SELECT PacienteID, Nombre, Edad, Genero, Departamentos.Descripcion AS Departamento, Municipios.Descripcion AS Municipio
                FROM Pacientes
                JOIN Departamentos ON Pacientes.DepartamentoID = Departamentos.DepartamentoID
                JOIN Municipios ON Pacientes.MunicipioID = Municipios.MunicipioID
                WHERE PacienteID = :PacienteID";
        $stmt = $this->sqlServer->prepare($query);
        $stmt->bindParam(':PacienteID', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function CreatePatient($patient)
    {

        $query = "SELECT DepartamentoID FROM Departamentos WHERE Descripcion = :Departamento";
        $stmt = $this->sqlServer->prepare($query);
        $stmt->bindParam(':Departamento', $patient['department']);
        $stmt->execute();
        $departmentId = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "SELECT MunicipioID FROM Municipios WHERE DepartamentoID = :DepartamentoID AND Descripcion = :Municipio";
        $stmt = $this->sqlServer->prepare($query);
        $stmt->bindParam(':DepartamentoID', $departmentId['DepartamentoID']);
        $stmt->bindParam(':Municipio', $patient['municipality']);
        $stmt->execute();
        $municipalityId = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "INSERT INTO Pacientes (Nombre, Edad, Genero, DepartamentoID, MunicipioID) VALUES (:Nombre, :Edad, :Genero, :DepartamentoID, :MunicipioID)";
        $stmt = $this->sqlServer->prepare($query);
        $stmt->bindParam(':Nombre', $patient['name']);
        $stmt->bindParam(':Edad', $patient['age']);
        $stmt->bindParam(':Genero', $patient['gender']);
        $stmt->bindParam(':DepartamentoID', $departmentId['DepartamentoID']);
        $stmt->bindParam(':MunicipioID', $municipalityId['MunicipioID']);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function UpdatePatient($id, $patient)
    {

        $query = "SELECT DepartamentoID FROM Departamentos WHERE Descripcion = :Departamento";
        $stmt = $this->sqlServer->prepare($query);
        $stmt->bindParam(':Departamento', $patient['department']);
        $stmt->execute();
        $departmentId = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "SELECT MunicipioID FROM Municipios WHERE DepartamentoID = :DepartamentoID AND Descripcion = :Municipio";
        $stmt = $this->sqlServer->prepare($query);
        $stmt->bindParam(':DepartamentoID', $departmentId['DepartamentoID']);
        $stmt->bindParam(':Municipio', $patient['municipality']);
        $stmt->execute();
        $municipalityId = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "UPDATE Pacientes SET Nombre = :Nombre, Edad = :Edad, Genero = :Genero, DepartamentoID = :DepartamentoID, MunicipioID = :MunicipioID WHERE PacienteID = :PacienteID";
        $stmt = $this->sqlServer->prepare($query);
        $stmt->bindParam(':PacienteID', $id);
        $stmt->bindParam(':Nombre', $patient['name']);
        $stmt->bindParam(':Edad', $patient['age']);
        $stmt->bindParam(':Genero', $patient['gender']);
        $stmt->bindParam(':DepartamentoID', $departmentId['DepartamentoID']);
        $stmt->bindParam(':MunicipioID', $municipalityId['MunicipioID']);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}