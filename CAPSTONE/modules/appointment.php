
<?php include '../components/head.php'; ?>
<?php include '../components/navbar.php'; ?>

<main class="booking-page">
    <div class="booking-intro">
        <span class="eyebrow">WE'RE HERE FOR YOUR WELL-BEING</span>
        <h1>Book an Appointment</h1>
        <p>Choose your preferred service and schedule your visit.</p>
    </div>

    <form class="booking-form" action="#" method="post">
        <h2>Appointment Details</h2>
        <p class="form-description">
            Please complete the details below.
        </p>

        <div class="form-grid">
            <div class="form-group">
                <label for="patient_name">Full Name</label>
                <input type="text" id="patient_name"
                       name="patient_name" placeholder="Enter your full name"
                       required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       placeholder="you@example.com" required>
            </div>

            <div class="form-group">
                <label for="phone">Contact Number</label>
                <input type="tel" id="phone" name="phone"
                       placeholder="09XX XXX XXXX" required>
            </div>

            <div class="form-group">
                <label for="service">Wellness Service</label>
                <select id="service" name="service" required>
                    <option value="">Select a service</option>
                    <option>Wellness Consultation</option>
                    <option>Massage Therapy</option>
                    <option>Preventive Care</option>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Preferred Date</label>
                <input type="date" id="date" name="date" required>
            </div>

            <div class="form-group">
                <label for="time">Preferred Time</label>
                <select id="time" name="time" required>
                    <option value="">Select a time</option>
                    <option>09:00 AM</option>
                    <option>10:00 AM</option>
                    <option>11:00 AM</option>
                    <option>01:00 PM</option>
                    <option>02:00 PM</option>
                    <option>03:00 PM</option>
                </select>
            </div>

            <div class="form-group full-width">
                <label for="notes">Additional Notes (Optional)</label>
                <textarea id="notes" name="notes" rows="4"
                    placeholder="Tell us anything we should know"></textarea>
            </div>
        </div>

        <div class="payment-note">
            <strong>Payment information</strong>
            <p>
                Payment will be made in person at the clinic after
                your appointment is completed.
            </p>
        </div>

        <button type="submit" class="btn-primary form-submit">
            Request Appointment →
        </button>

        <p class="form-disclaimer">
            izza prank wala pa database AHHAHAHAHA. Booking is not yet submitted
            or stored in a database.
        </p>
    </form>
</main>

<?php include '../components/footer.php'; ?>
