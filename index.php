<?php
require_once('config/sql_server.php');
require_once('models/patient_model.php');
require_once('controllers/patient_controller.php');

$sqlServer = new SqlServer();
$patientModel = new PatientModel($sqlServer);
$patientController = new PatientController($patientModel);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Technical Test</title>
    <style>
        .wrapper {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 5px;
        }

        .error {
            color: red;
        }

        .dummy {
            color: blue;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h1>Technical Test - Patients: LEONARDO ANDRES OSPINA RAMIREZ</h1>
    <h2>Patients</h2>
    <div>
        <p class="dummy">
            Color azul: Datos de prueba
        </p>
    </div>
    <table class="card">
        <thead>
        <tr>
            <th>PacienteID</th>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Género</th>
            <th>Departamento</th>
            <th>Municipio</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody id="tbody-patients">
        <?php foreach ($patientController->getPatients() as $patient) { ?>
            <tr>
                <td><?php echo $patient['PacienteID']; ?></td>
                <td><?php echo $patient['Nombre']; ?></td>
                <td><?php echo $patient['Edad']; ?></td>
                <td><?php echo $patient['Genero']; ?></td>
                <td><?php echo $patient['Departamento']; ?></td>
                <td><?php echo $patient['Municipio']; ?></td>
                <td>
                    <button onclick='setUpdatePatientForm(<?php echo json_encode($patient, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'>
                        Update
                    </button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div class="container">
        <div class="card">
            <h2>Crear Paciente</h2>
            <form id="create_patient_form" method="POST" onsubmit="createPatient(event)">
                <div>
                    <label for="create_name">Name:</label>
                    <input type="text" id="create_name" name="name">
                    <span id="create_nameError" class="error"></span>
                </div>
                <div>
                    <label for="birthDate">Birth Date:</label>
                    <input type="date" id="birthDate" name="birthDate" onchange="setBirthDateChange()">
                    <span id="create_birthDateError" class="error"></span>
                </div>
                <div>
                    Current Age: <span id="currentAge"></span>
                </div>
                <div>
                    <label for="create_age">Age:</label>
                    <input type="number" id="create_age" name="age">
                    <span id="create_ageError" class="error"></span>
                </div>
                <div>
                    <label for="create_gender">
                        Choose Gender:
                    </label>
                    <select id="create_gender">
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                    <span id="create_genderError" class="error"></span>
                </div>
                <div>
                    <label for="create_department">Department:</label>
                    <select id="create_department">
                        <option value="">Select Department</option>
                    </select>
                    <span id="create_departmentError" class="error"></span>
                </div>
                <div>
                    <label for="create_municipality">Municipality:</label>
                    <select id="create_municipality">
                        <option value="">Select Municipality</option>
                    </select>
                    <span id="create_municipalityError" class="error"></span>
                </div>
                <div>
                    <button type="submit" form="create_patient_form">Register</button>
                </div>
            </form>
        </div>
        <div class="card">
            <h2>Update Paciente</h2>
            <form id="update_patient_form" method="POST" onsubmit="updatePatient(event)">
                <div>
                    <label for="update_id" hidden>Id:</label>
                    <input type="text" id="update_id" name="id" hidden>
                    <span id="update_idError" class="error"></span>
                </div>
                <div>
                    <label for="update_name">Name:</label>
                    <input type="text" id="update_name" name="name">
                    <span id="update_nameError" class="error"></span>
                </div>
                <div>
                    <label for="update_age">Age:</label>
                    <input type="number" id="update_age" name="age">
                    <span id="update_ageError" class="error"></span>
                </div>
                <div>
                    <label for="update_gender">
                        Choose Gender:
                    </label>
                    <select id="update_gender">
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                    <span id="update_genderError" class="error"></span>
                </div>
                <div>
                    <label for="update_department">Department:</label>
                    <select id="update_department">
                        <option value="">Select Department</option>
                    </select>
                    <span id="update_departmentError" class="error"></span>
                </div>
                <div>
                    <label for="update_municipality">Municipality:</label>
                    <select id="update_municipality">
                        <option value="">Select Municipality</option>
                    </select>
                    <span id="update_municipalityError" class="error"></span>
                </div>
                <div>
                    <button type="submit" form="update_patient_form">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        setDepartments();
        setDepartmentChange();
        getPatients();
    });

    function getPatients() {
        const tbody = document.getElementById('tbody-patients');

        const patients = [
            {
                PacienteID: 100,
                Nombre: 'Juan',
                Edad: 25,
                Genero: 'M',
                Departamento: 'Antioquia',
                Municipio: 'Medellín'
            },
            {
                PacienteID: 101,
                Nombre: 'Ana',
                Edad: 30,
                Genero: 'F',
                Departamento: 'Valle del Cauca',
                Municipio: 'Cali'
            },
            {
                PacienteID: 102,
                Nombre: 'Pedro',
                Edad: 35,
                Genero: 'M',
                Departamento: 'Cundinamarca',
                Municipio: 'Bogotá'
            },
            {
                PacienteID: 103,
                Nombre: 'María',
                Edad: 40,
                Genero: 'F',
                Departamento: 'Cundinamarca',
                Municipio: 'Bogotá'
            },
            {
                PacienteID: 104,
                Nombre: 'Carlos',
                Edad: 45,
                Genero: 'M',
                Departamento: 'Santander',
                Municipio: 'Bucaramanga'
            }
        ]

        patients.forEach(patient => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="dummy">${patient.PacienteID}</td>
                <td class="dummy">${patient.Nombre}</td>
                <td class="dummy">${patient.Edad}</td>
                <td class="dummy">${patient.Genero}</td>
                <td class="dummy">${patient.Departamento}</td>
                <td class="dummy">${patient.Municipio}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    function createPatient(event) {
        event.preventDefault();

        const name = event.target.create_name.value;
        const age = event.target.create_age.value;
        const gender = event.target.create_gender.value;
        const department = event.target.create_department.value;
        const municipality = event.target.create_municipality.value;

        const patient = {
            name: name,
            age: age,
            gender: gender,
            department: department,
            municipality: municipality
        };

        if (!validateCreatePatient()) {
            console.log('Invalid patient data');
            return;
        }


        fetch('ajax_handler.php?action=create_patient', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(patient)
        }).then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Request failed!');
        }).then(data => {
            console.log('Patient created successfully!' + data);
            alert('Patient created successfully!');
            event.target.reset();
        }).catch(error => {
            console.error(error);
        });

        console.log('Patient data: ' + JSON.stringify(patient));
        console.log('Creating patient...');
    }

    function updatePatient(event) {
        event.preventDefault();
        const id = event.target.update_id.value;
        const name = event.target.update_name.value;
        const age = event.target.update_age.value;
        const gender = event.target.update_gender.value;
        const department = event.target.update_department.value;
        const municipality = event.target.update_municipality.value;

        const patient = {
            id: id,
            name: name,
            age: age,
            gender: gender,
            department: department,
            municipality: municipality
        };

        if (!validateUpdatePatient()) {
            console.log('Invalid patient data');
            return;
        }

        fetch('ajax_handler.php?action=update_patient&id=' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(patient)
        }).then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Request failed!');
        }).then(data => {
            console.log('Patient updated successfully!' + data);
            alert('Patient updated successfully');
            event.target.reset();
        }).catch(error => {
            console.error(error);
        });

        console.log('Patient data: ' + JSON.stringify(patient));
        console.log('Updating patient...');
    }

    function setUpdatePatientForm(patient) {
        document.getElementById('update_id').value = patient.PacienteID;
        document.getElementById('update_name').value = patient.Nombre;
        document.getElementById('update_age').value = patient.Edad;
        document.getElementById('update_gender').value = patient.Genero;
        document.getElementById('update_department').value = patient.Departamento;
        document.getElementById('update_municipality').value = patient.Municipio;
        console.log('Setting patient data to update form...' + JSON.stringify(patient));
    }

    function setDepartmentChange() {
        const createDepartmentSelect = document.getElementById('create_department');
        const updateDepartmentSelect = document.getElementById('update_department');
        createDepartmentSelect.addEventListener('change', getMunicipalitiesByDepartment);
        updateDepartmentSelect.addEventListener('change', getMunicipalitiesByDepartment);
    }

    function setDepartments() {
        const createDepartmentSelect = document.getElementById('create_department');
        const updateDepartmentSelect = document.getElementById('update_department');

        const departments = ['Tolima', 'Cundinamarca', 'Antioquia'];

        departments.forEach(department => {
            const option = document.createElement("option");
            option.value = department;
            option.text = department;
            updateDepartmentSelect.appendChild(option);
        });

        departments.forEach(department => {
            const option = document.createElement("option");
            option.value = department;
            option.text = department;
            createDepartmentSelect.appendChild(option);
        });
    }

    function getMunicipalitiesByDepartment() {
        const createDepartment = document.getElementById('create_department').value;
        const updateDepartment = document.getElementById('update_department').value;
        const createMunicipalitySelect = document.getElementById('create_municipality');
        const updateMunicipalitySelect = document.getElementById('update_municipality');

        const municipalities = {
            Tolima: ['Ibague', 'Espinal', 'Mariquita'],
            Cundinamarca: ['Bogota', 'Soacha', 'Zipaquira'],
            Antioquia: ['Medellín', 'Envigado', 'Itaguí']
        };

        const createDepartmentList = municipalities[createDepartment] || [];
        const updateDepartmentList = municipalities[updateDepartment] || [];

        // Set the municipalities based on the department
        createMunicipalitySelect.innerHTML = "<option value=''>Select Municipality</option>";
        updateMunicipalitySelect.innerHTML = "<option value=''>Select Municipality</option>";

        createDepartmentList.forEach(municipality => {
            const option = document.createElement("option");
            option.value = municipality;
            option.text = municipality;
            createMunicipalitySelect.appendChild(option);
        });

        updateDepartmentList.forEach(municipality => {
            const option = document.createElement("option");
            option.value = municipality;
            option.text = municipality;
            updateMunicipalitySelect.appendChild(option);
        });
    }

    function validateCreatePatient() {
        let isValid = true;
        const create_name = document.getElementById('create_name').value.trim();
        const create_age = document.getElementById('create_age').value.trim();
        const create_gender = document.getElementById('create_gender').value;
        const create_department = document.getElementById('create_department').value.trim();
        const create_municipality = document.getElementById('create_municipality').value.trim();

        // Set Error Messages to empty
        document.getElementById('create_nameError').textContent = "";
        document.getElementById('create_ageError').textContent = "";
        document.getElementById('create_departmentError').textContent = "";
        document.getElementById('create_municipalityError').textContent = "";

        if (create_name === "" || !/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/.test(create_name)) {
            document.getElementById('create_nameError').textContent = "Please enter a valid name.";
            isValid = false;
        }

        if (create_age === "" || isNaN(create_age) || create_age < 0 || create_age > 120) {
            document.getElementById('create_ageError').textContent = "Please enter a valid age between 0 and 120.";
            isValid = false;
        }

        if (create_gender === "") {
            document.getElementById('create_genderError').textContent = "Please select your gender";
        }

        if (create_department === "") {
            document.getElementById('create_departmentError').textContent = "Please enter a department.";
            isValid = false;
        }

        if (create_municipality === "") {
            document.getElementById('create_municipalityError').textContent = "Please enter a municipality.";
            isValid = false;
        }

        return isValid;
    }

    function validateUpdatePatient() {
        let isValid = true;
        const update_name = document.getElementById('update_name').value.trim();
        const update_age = document.getElementById('update_age').value.trim();
        const update_gender = document.getElementById('update_gender').value.trim();
        const update_department = document.getElementById('update_department').value.trim();
        const update_municipality = document.getElementById('update_municipality').value.trim();

        // Set Error Messages to empty
        document.getElementById('update_nameError').textContent = "";
        document.getElementById('update_ageError').textContent = "";
        document.getElementById('update_genderError').textContent = "";
        document.getElementById('update_departmentError').textContent = "";
        document.getElementById('update_municipalityError').textContent = "";

        if (update_name === "" || !/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/.test(update_name)) {
            document.getElementById('update_nameError').textContent = "Please enter a valid name.";
            isValid = false;
        }

        if (update_age === "" || isNaN(update_age) || update_age < 0 || update_age > 120) {
            document.getElementById('update_ageError').textContent = "Please enter a valid age between 0 and 120.";
            isValid = false;
        }

        if (update_gender === "") {
            document.getElementById('update_genderError').textContent = "Please select your gender";
        }

        if (update_department === "") {
            document.getElementById('update_departmentError').textContent = "Please enter a department.";
            isValid = false;
        }

        if (update_municipality === "") {
            document.getElementById('update_municipalityError').textContent = "Please enter a municipality.";
            isValid = false;
        }

        return isValid;
    }

    function setBirthDateChange() {
        const birthDate = document.getElementById('birthDate').value;
        document.getElementById('currentAge').textContent = birthDate ? getAgeFromBirthDate(birthDate) : '';
    }

    function getAgeFromBirthDate(birthDate) {
        const today = new Date();
        const birth = new Date(birthDate);
        let age = today.getFullYear() - birth.getFullYear();
        const m = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        return age;
    }

</script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
</body>
</html>




