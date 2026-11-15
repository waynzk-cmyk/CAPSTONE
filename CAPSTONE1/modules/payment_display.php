<?php
$payments = PaymentRepository::all();

$totalPaid = 0;
foreach ($payments as $p) {
    if (($p['payment_status'] ?? 'Unpaid') === 'Paid') {
        $totalPaid += $p['price'];
    }
}
?>

<main class="patients-page">
    <div class="patients-heading">
        <span class="eyebrow">BILLING</span>
        <h1>Payments</h1>
        <p>Payments are collected in person at the clinic after the appointment.</p>
    </div>

    <?php if (isset($_GET['paid'])) : ?>
        <p style="background:#e6f7ee;color:#1a7f4b;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
            Payment recorded.
        </p>
    <?php endif; ?>

    <div class="patients-card">
        <div class="patients-card-top">
            <h2>All appointments</h2>
            <span class="patients-count">Collected: ₱<?php echo number_format($totalPaid, 2); ?></span>
        </div>

        <?php if (empty($payments)) : ?>
            <p class="patients-empty">There are no appointments yet.</p>
        <?php else : ?>
            <table class="patients-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $p) : ?>
                        <?php $paymentStatus = $p['payment_status'] ?? 'Unpaid'; ?>
                        <tr>
                            <td><?php echo (int) $p['appointment_id']; ?></td>
                            <td><?php echo htmlspecialchars($p['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($p['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($p['appointment_date']); ?></td>
                            <td>₱<?php echo number_format($p['price'], 2); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($paymentStatus); ?>">
                                    <?php echo htmlspecialchars($paymentStatus); ?>
                                </span>
                                <?php if ($paymentStatus === 'Paid') : ?>
                                    <br><small><?php echo htmlspecialchars($p['payment_method']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($paymentStatus !== 'Paid' && $p['appointment_status'] !== 'Cancelled') : ?>
                                    <form method="post" action="/CAPSTONE/index.php?page=payments" class="pay-form">
                                        <input type="hidden" name="appointment_id" value="<?php echo (int) $p['appointment_id']; ?>">
                                        <select name="payment_method">
                                            <option>Cash</option>
                                            <option>Card</option>
                                            <option>Other</option>
                                        </select>
                                        <input type="text" name="received_by" placeholder="Received by" required>
                                        <button type="submit" class="pay-btn">Mark as paid</button>
                                    </form>
                                <?php elseif ($paymentStatus === 'Paid') : ?>
                                    <small>Received by <?php echo htmlspecialchars($p['received_by']); ?></small>
                                <?php else : ?>
                                    <small>Cancelled</small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>