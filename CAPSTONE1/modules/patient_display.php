<?php
$pendingPatients = PatientRepository::pending();
$isAppointments  = ($_GET['page'] ?? '') === 'appointments';
$base            = $isAppointments ? 'appointments' : 'patients';

$editId  = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$editing = $editId ? PatientRepository::find($editId) : null;
?>

<main class="patients-page">

<?php if ($editId) : ?>

    <div class="patients-heading">
        <span class="eyebrow">PATIENT RECORDS</span>
        <h1>Update Patient</h1>
        <p>Edit the patient's details and save your changes.</p>
    </div>

    <?php if (!empty($_GET['error'])) : ?>
        <p style="background:#fdeaea;color:#b42318;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </p>
    <?php endif; ?>

    <?php if (!$editing) : ?>
        <div class="patients-card">
            <p class="patients-empty">Patient not found.</p>
        </div>
    <?php else : ?>
        <form method="post" action="/CAPSTONE/index.php?page=<?php echo $base; ?>" class="patients-card edit-form">
            <input type="hidden" name="update_patient" value="1">
            <input type="hidden" name="patient_id" value="<?php echo (int) $editing['patient_id']; ?>">

            <div class="edit-grid">
                <label>Full name
                    <input type="text" name="full_name" required
                           value="<?php echo htmlspecialchars($editing['full_name']); ?>">
                </label>

                <label>Email
                    <input type="email" name="email" required
                           value="<?php echo htmlspecialchars($editing['email']); ?>">
                </label>

                <label>Phone
                    <input type="tel" name="phone"
                           value="<?php echo htmlspecialchars($editing['phone'] ?? ''); ?>">
                </label>

                <label>Date of birth
                    <input type="date" name="date_of_birth"
                           value="<?php echo htmlspecialchars($editing['date_of_birth'] ?? ''); ?>">
                </label>

                <label>Gender
                    <select name="gender">
                        <option value="">Select</option>
                        <?php foreach (['Male', 'Female', 'Other'] as $g) : ?>
                            <option <?php echo ($editing['gender'] ?? '') === $g ? 'selected' : ''; ?>>
                                <?php echo $g; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label class="edit-full">Address
                    <textarea name="address" rows="3"><?php echo htmlspecialchars($editing['address'] ?? ''); ?></textarea>
                </label>
            </div>

            <div class="edit-actions">
                <button type="submit" class="pay-btn">Save changes</button>
                <a href="/CAPSTONE/index.php?page=<?php echo $base; ?>" class="edit-cancel">Cancel</a>
            </div>
        </form>
    <?php endif; ?>

<?php else : ?>

    <div class="patients-heading">
        <span class="eyebrow"><?php echo $isAppointments ? 'APPOINTMENTS' : 'PATIENT RECORDS'; ?></span>
        <h1><?php echo $isAppointments ? 'Pending Appointments' : 'Pending Patients'; ?></h1>
        <p>Patients who are still waiting for approval or confirmation.</p>
    </div>

    <?php if (isset($_GET['updated'])) : ?>
        <p style="background:#e6f7ee;color:#1a7f4b;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
            Patient details updated.
        </p>
    <?php endif; ?>

    <?php if (isset($_GET['cancelled'])) : ?>
        <p style="background:#e6f7ee;color:#1a7f4b;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
            Appointment cancelled.
        </p>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])) : ?>
        <p style="background:#e6f7ee;color:#1a7f4b;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
            Appointment deleted.
        </p>
    <?php endif; ?>

    <div class="patients-card">
        <div class="patients-card-top">
            <h2>Pending list</h2>
            <span class="patients-count"><?php echo count($pendingPatients); ?> pending</span>
        </div>

        <?php if (empty($pendingPatients)) : ?>
            <p class="patients-empty">There are no pending patients right now.</p>
        <?php else : ?>
            <table class="patients-table">
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Complete Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingPatients as $patient) : ?>
                        <?php $rawId = (int) substr($patient->getPatientId(), 2); // "P-0003" becomes 3 ?>
                        <tr>
                            <td><?php echo htmlspecialchars($patient->getPatientId()); ?></td>
                            <td><?php echo htmlspecialchars($patient->completeName()); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($patient->getStatus()); ?>">
                                    <?php echo htmlspecialchars($patient->getStatus()); ?>
                                </span>

                                <a class="btn-update"
                                   href="/CAPSTONE/index.php?page=<?php echo $base; ?>&edit=<?php echo $rawId; ?>">
                                    Update
                                </a>

                                <form method="post" action="/CAPSTONE/index.php?page=<?php echo $base; ?>"
                                      class="inline-form" onsubmit="return confirm('Cancel this appointment?');">
                                    <input type="hidden" name="patient_id" value="<?php echo $rawId; ?>">
                                    <button type="submit" name="cancel_patient" value="1" class="btn-cancel">Cancel</button>
                                </form>

                                <form method="post" action="/CAPSTONE/index.php?page=<?php echo $base; ?>"
                                      class="inline-form" onsubmit="return confirm('Delete this appointment permanently?');">
                                    <input type="hidden" name="patient_id" value="<?php echo $rawId; ?>">
                                    <button type="submit" name="delete_patient" value="1" class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

<?php endif; ?>

</main>