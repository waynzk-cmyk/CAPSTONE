
<?php include 'components/head.php'; ?>
<?php include 'components/navbar.php'; ?>

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
        Our practitioner directory will be available here.
        Contact the clinic for more information about our team.
    </p>
</section>

<?php include 'components/footer.php'; ?>
