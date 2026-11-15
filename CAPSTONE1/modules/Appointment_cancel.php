<?php
session_start();
include_once '../repositories/Database.php';
include_once '../repositories/apppointmentRepository.php';

$error = '';
$self  = '/CAPSTONE/modules/my_appointments.php';

// "Not you?" clears the lookup
if (isset($_GET['logout'])) {
    unset($_SESSION['patient_id']);
    header('Location: ' . $self);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'lookup') {
            $pid = appointmentRepository::findPatientByContact(
                $_POST['email'] ?? '',
                $_POST['phone'] ?? ''
            );
            if ($pid === null) {
                $error = 'We could not find a patient with that email and contact number.';
            } else {
                session_regenerate_id(true);
                $_SESSION['patient_id'] = $pid;
                header('Location: ' . $self);
                exit;
            }
        } elseif (isset($_SESSION['patient_id'])) {
            $pid = (int) $_SESSION['patient_id'];
            $aid = (int) ($_POST['appointment_id'] ?? 0);

            if ($action === 'cancel') {
                $ok = AppointmentRepository::cancel($pid, $aid);
                header('Location: ' . $self . '?msg=' . ($ok ? 'cancelled' : 'cannot_cancel'));
                exit;
            }
            if ($action === 'delete') {
                $ok = AppointmentRepository::remove($pid, $aid);
                header('Location: ' . $self . '?msg=' . ($ok ? 'deleted' : 'cannot_delete'));
                exit;
            }
        }
    } catch (Throwable $e) {
        $error = 'Something went wrong. Please try again.';
    }
}

$patientId    = $_SESSION['patient_id'] ?? null;
$appointments = $patientId ? AppointmentRepository::forPatient((int) $patientId) : [];

$messages = [
    'cancelled'     => ['ok',  'Your appointment was cancelled.'],
    'deleted'       => ['ok',  'The appointment was removed from your list.'],
    'cannot_cancel' => ['bad', 'That appointment can no longer be cancelled (it may already be paid, completed or cancelled).'],
    'cannot_delete' => ['bad', 'Only cancelled appointments without a payment record can be deleted.'],
];
$flash = $messages[$_GET['msg'] ?? ''] ?? null;
?>
<?php include '../components/head.php'; ?>
<?php include '../components/navbar.php'; ?>

<main class="patients-page">
    <div class="patients-heading">
        <span class="eyebrow">YOUR VISITS</span>
        <h1>My Appointments</h1>
        <p>View your appointments and cancel or remove the ones you no longer need.</p>
    </div>

    <?php if ($flash) : ?>
        <p class="flash flash-<?php echo $flash[0]; ?>"><?php echo htmlspecialchars($flash[1]); ?></p>
    <?php endif; ?>

    <?php if ($error !== '') : ?>
        <p class="flash flash-bad"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if (!$patientId) : ?>

        <form method="post" class="patients-card edit-form">
            <input type="hidden" name="action" value="lookup">
            <p class="form-description">
                Enter the email and contact number you used when you booked.
            </p>
            <div class="edit-grid">
                <label>Email address
                    <input type="email" name="email" required>
                </label>
                <label>Contact number
                    <input type="tel" name="phone" required>
                </label>
            </div>
            <div class="edit-actions">
                <button type="submit" class="pay-btn">Find my appointments</button>
            </div>
        </form>

    <?php else : ?>

        <div class="patients-card">
            <div class="patients-card-top">
                <h2>Your appointments</h2>
                <a class="edit-cancel" href="<?php echo $self; ?>?logout=1">Not you? Look up another</a>
            </div>

            <?php if (empty($appointments)) : ?>
                <p class="patients-empty">You have no appointments.</p>
            <?php else : ?>
                <table class="patients-table">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Doctor</th>
                            <th>Date &amp; time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($appointments as $a) : ?>
                        <?php
                            $status    = $a['status'];
                            $paid      = ($a['payment_status'] ?? '') === 'Paid';
                            $canCancel = in_array($status, ['Pending', 'Confirmed'], true) && !$paid;
                            $canDelete = $status === 'Cancelled' && $a['payment_status'] === null;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($a['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($a['doctor_name'] ?? '—'); ?></td>
                            <td>
                                <?php echo htmlspecialchars($a['appointment_date']); ?>,
                                <?php echo date('g:i A', strtotime($a['start_time'])); ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($status); ?>">
                                    <?php echo htmlspecialchars($status); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($canCancel) : ?>
                                    <form method="post" class="inline-form"
                                          onsubmit="return confirm('Cancel this appointment?');">
                                        <input type="hidden" name="action" value="cancel">
                                        <input type="hidden" name="appointment_id" value="<?php echo (int) $a['appointment_id']; ?>">
                                        <button type="submit" class="btn-cancel">Cancel</button>
                                    </form>
                                <?php elseif ($canDelete) : ?>
                                    <form method="post" class="inline-form"
                                          onsubmit="return confirm('Delete this appointment permanently?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="appointment_id" value="<?php echo (int) $a['appointment_id']; ?>">
                                        <button type="submit" class="btn-delete">Delete</button>
                                    </form>
                                <?php else : ?>
                                    <small><?php echo $paid ? 'Paid' : '—'; ?></small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    <?php endif; ?>
</main>

<?php include '../components/footer.php'; ?>