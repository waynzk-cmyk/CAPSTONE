<?php include 'classes/page.php'; ?>
<?php include 'classes/person.php'; ?>
<?php include 'classes/patients.php'; ?>

<?php include 'repositories/Database.php'; ?>
<?php include 'repositories/patientRepository.php'; ?>
<?php include 'repositories/doctorRepository.php'; ?>
<?php include 'repositories/paymentRepository.php'; ?>

<?php
$id = $_GET['id'];
patientRepository::delete($id);
header("location: ../index.php?page=appointments");