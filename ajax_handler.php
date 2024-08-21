<?php
require_once('config/sql_server.php');
require_once('models/patient_model.php');
require_once('controllers/patient_controller.php');

$sqlServer = new SqlServer();
$patientModel = new PatientModel($sqlServer);
$patientController = new PatientController($patientModel);

$action = isset($_GET['action']) ? $_GET['action'] : null;

$response = [];

try {
    switch ($action) {
        case 'get_patients':
            $response = $patientController->GetPatients();
            break;
        case 'get_patient':
            $patientId = $_GET['id'];
            $response = $patientController->GetPatient($patientId);
            break;
        case 'create_patient':
            $data = json_decode(file_get_contents('php://input'), true);
            var_dump($data);
            $response = $patientController->CreatePatient($data);
            return $response;
            break;
        case 'update_patient':
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'];
            var_dump($data);
            var_dump($id);
            $response = $patientController->UpdatePatient($id, $data);
            break;
        default:
            $response = ['error' => 'Invalid action'];
            break;
    }
} catch (Exception $e) {
    $response = ['error' => $e->getMessage()];
}

header('Content-Type: application/json');
$jsonResponse = json_encode($response);
var_dump($jsonResponse);
echo $jsonResponse;