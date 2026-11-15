<?php include 'classes/page.php'; ?>
<?php include 'classes/person.php'; ?>
<?php include 'classes/patients.php'; ?>

<?php include 'repositories/Database.php'; ?>
<?php include 'repositories/patientRepository.php'; ?>
<?php include 'repositories/doctorRepository.php'; ?>
<?php include 'repositories/paymentRepository.php'; ?>


<?php 
$id= $_GET['id'];
$patient_number = $_POST['patient_number'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$dob = $_POST['dob'];


patientRepository::update([
    'patient_number' => $patient_number,
    'firstname' => $firstname,
    'lastname' => $lastname,
    'date_of_birth' => $date_of_birth,
], $id);

header("location: ../index.php?page=Patients");
?>