<?php $doctors = DoctorRepository::all(); ?>

<main class="patients-page">
    <div class="patients-heading">
        <span class="eyebrow">OUR TEAM</span>
        <h1>Our Doctors</h1>
        <p>Meet our doctors and check when they are available.</p>
    </div>

    <?php if (empty($doctors)) : ?>
        <p class="patients-empty">No doctors have been added yet.</p>
    <?php else : ?>
        <div class="doctor-grid">
            <?php foreach ($doctors as $doctor) : ?>
                <article class="doctor-card">
                    <h3><?php echo htmlspecialchars($doctor['full_name']); ?></h3>
                    <p class="doctor-spec">
                        <?php echo htmlspecialchars($doctor['specialization'] ?? 'General'); ?>
                    </p>
                    <p class="doctor-contact">
                        <?php echo htmlspecialchars($doctor['phone'] ?? ''); ?><br>
                        <?php echo htmlspecialchars($doctor['email'] ?? ''); ?>
                    </p>

                    <span class="status-badge status-<?php echo strtolower($doctor['status']); ?>">
                        <?php echo htmlspecialchars($doctor['status']); ?>
                    </span>

                    <h4 class="schedule-title">Weekly availability</h4>
                    <?php if (empty($doctor['schedule'])) : ?>
                        <p class="doctor-contact">No schedule set yet.</p>
                    <?php else : ?>
                        <ul class="schedule-list">
                            <?php foreach ($doctor['schedule'] as $slot) : ?>
                                <li>
                                    <span><?php echo htmlspecialchars($slot['day_of_week']); ?></span>
                                    <span>
                                        <?php echo date('g:i A', strtotime($slot['start_time'])); ?>
                                        –
                                        <?php echo date('g:i A', strtotime($slot['end_time'])); ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>