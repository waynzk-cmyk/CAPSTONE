
<header class="top-header">
    <div class="header-inner">
        <a href="/CAPSTONE/index.php" class="brand">
            <span class="brand-icon">✚</span>
            <span class="brand-text">
                <strong>WELLNESS<span>CLINIC</span></strong>
                <small>HEALTH · BALANCE · BETTER YOU</small>
            </span>
        </a>

        <div class="header-contact">
            <div class="contact-item">
                <span class="contact-icon">☎</span>
                <div>
                    <strong>Call Us</strong>
                    <small>0912 345 6789</small>
                </div>
            </div>

            <div class="contact-item">
                <span class="contact-icon">✉</span>
                <div>
                    <strong>Email Us</strong>
                    <small>wellness@gmail.com</small>
                </div>
            </div>

            <a href="/CAPSTONE/modules/appointment.php"
               class="header-book">▦ Book Appointment</a>
        </div>
    </div>
</header>

<?php $currentPage = $_GET['page'] ?? 'home'; ?>
<nav class="main-nav">
    <div class="nav-inner">
        <div class="nav-links">
            <a class="<?php echo $currentPage === 'home' ? 'active' : ''; ?>" href="/CAPSTONE/index.php">Home</a>
            <a href="/CAPSTONE/index.php#about">About</a>
            <a href="/CAPSTONE/index.php#services">Services</a>
            <a class="<?php echo $currentPage === 'doctors' ? 'active' : ''; ?>" href="/CAPSTONE/index.php?page=doctors">Doctors</a>
            <a class="<?php echo $currentPage === 'patients' ? 'active' : ''; ?>" href="/CAPSTONE/index.php?page=patients">Patients</a>
            <a class="<?php echo $currentPage === 'appointments' ? 'active' : ''; ?>" href="/CAPSTONE/index.php?page=appointments">Appointments</a>
            <a class="<?php echo $currentPage === 'payments' ? 'active' : ''; ?>" href="/CAPSTONE/index.php?page=payments">Payments</a>
            <a href="/CAPSTONE/index.php#contact">Contact</a>
        </div>

        <a href="/CAPSTONE/modules/appointment.php"
           class="nav-appointment">Book Now →</a>
    </div>
</nav>
