<?php

class PatientController
{
    private $patientModel;

    public function __construct($patientModel)
    {
        $this->patientModel = $patientModel;
    }

    public function GetPatients()
    {
        return $this->patientModel->GetPatients();
    }

    public function GetPatient($id)
    {
        return $this->patientModel->GetPatient($id);
    }

    public function CreatePatient($request)
    {
        return $this->patientModel->CreatePatient($request);
    }

    public function UpdatePatient($id, $request)
    {
        return $this->patientModel->UpdatePatient($id, $request);
    }
}