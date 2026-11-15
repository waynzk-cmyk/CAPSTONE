<?php include 'classes/page.php'; ?>
<?php include 'classes/person.php'; ?>
<?php include 'classes/patients.php'; ?>

<?php include 'repositories/Database.php'; ?>
<?php include 'repositories/patientRepository.php'; ?>
<?php include 'repositories/doctorRepository.php'; ?>
<?php include 'repositories/paymentRepository.php'; ?>


<?php 
$page = $_GET['page'] ?? 'home';
$modulePages = ['patients', 'doctors', 'appointments', 'payments'];

if ($page === 'payments' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    PaymentRepository::markPaid(
        (int) ($_POST['appointment_id'] ?? 0),
        $_POST['payment_method'] ?? 'Cash',
        trim($_POST['received_by'] ?? '')
    );
    header('Location: /CAPSTONE/index.php?page=payments&paid=1');
    exit;
}

// Handle "Cancel" / "Delete" appointment buttons
// Handle "Cancel" and "Delete" buttons
if (in_array($page, ['patients', 'appointments'], true)
    && $_SERVER['REQUEST_METHOD'] === 'POST'
    && (isset($_POST['cancel_patient']) || isset($_POST['delete_patient']))) {

    $patientId = (int) ($_POST['patient_id'] ?? 0);

    try {
        if (isset($_POST['cancel_patient'])) {
            PatientRepository::cancelPending($patientId);
            header('Location: /CAPSTONE/index.php?page=' . $page . '&cancelled=1');
        } else {
            PatientRepository::deletePatient($patientId);
            header('Location: /CAPSTONE/index.php?page=' . $page . '&deleted=1');
        }
    } catch (Throwable $e) {
        header('Location: /CAPSTONE/index.php?page=' . $page . '&error=' . urlencode('Could not complete that action.'));
    }
    exit;
}

// Handle "Cancel" appointment button
if (in_array($page, ['patients', 'appointments'], true)
    && $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['cancel_patient'])) {

    PatientRepository::cancelPending((int) ($_POST['patient_id'] ?? 0));
    header('Location: /CAPSTONE/index.php?page=' . $page . '&cancelled=1');
    exit;
}

// Handle "Update patient" before any HTML is sent
// Handle "Cancel" and "Delete" buttons
if (in_array($page, ['patients', 'appointments'], true)
    && $_SERVER['REQUEST_METHOD'] === 'POST'
    && (isset($_POST['cancel_patient']) || isset($_POST['delete_patient']))) {

    $patientId = (int) ($_POST['patient_id'] ?? 0);

    try {
        if (isset($_POST['cancel_patient'])) {
            patientRepository::cancelPending($patientId);
            header('Location: /CAPSTONE/index.php?page=' . $page . '&cancelled=1');
        } else {
            patientRepository::deletePatient($patientId);
            header('Location: /CAPSTONE/index.php?page=' . $page . '&deleted=1');
        }
    } catch (Throwable $e) {
        header('Location: /CAPSTONE/index.php?page=' . $page . '&error=' . urlencode('Could not complete that action.'));
    }
    exit;
}
?>

<?php include 'components/head.php'; ?>
<?php include 'components/navbar.php'; ?>

<?php if (in_array($page, $modulePages, true)) : ?>

    <?php Page::display($page); ?>

<?php else : ?>

<main>
    <?php include 'components/hero.php'; ?>
    <?php include 'components/appointment_banner.php'; ?>
</main>

<section class="services-section" id="services">
    <div class="section-heading">
        <span class="eyebrow">CARE FOR EVERY PART OF YOU</span>
        <h2>Our Wellness Services</h2>
        <p>Personalized care to help you feel your best.</p>
    </div>

    <div class="service-grid">
        <article class="service-card">
            <div class="service-icon">♡</div>
            <h3>Wellness Consultation</h3>
            <p>Personalized guidance for your health and well-being.</p>
            <a href="/CAPSTONE/modules/appointment.php">Book a visit →</a>
        </article>

        <article class="service-card">
            <div class="service-icon">✳</div>
            <h3>Massage Therapy</h3>
            <p>Relax, release tension, and support your recovery.</p>
            <a href="/CAPSTONE/modules/appointment.php">Book a visit →</a>
        </article>

        <article class="service-card">
            <div class="service-icon">♡</div>
            <h3>Preventive Care</h3>
            <p>Take steps toward a healthier and more balanced life.</p>
            <a href="/CAPSTONE/modules/appointment.php">Book a visit →</a>
        </article>
    </div>
</section>

<section class="about-section" id="about">
    <div>
        <span class="eyebrow">ABOUT OUR CLINIC</span>
        <h2>Your Health Matters to Us</h2>
        <p>
            We aim to provide a welcoming environment where every
            patient can access personalized wellness care.
        </p>
    </div>
    <a href="/CAPSTONE/modules/appointment.php" class="btn-primary">
        Schedule Your Visit →
    </a>
</section>

<section class="doctors-section" id="doctors">
    <span class="eyebrow">OUR TEAM</span>
    <h2>Professional Care, Personal Attention</h2>
    <p>
        Contact the clinic for more information about our team.
    </p>
</section>

<?php endif; ?>




<?php include 'components/footer.php'; ?>
