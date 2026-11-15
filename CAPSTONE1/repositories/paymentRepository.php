<?php
class PaymentRepository extends DBRepository
{
   
    public static function all(): array
    {
        return self::db()->query(
            "SELECT a.appointment_id,
                    u.full_name,
                    s.service_name,
                    s.price,
                    a.appointment_date,
                    a.status AS appointment_status,
                    p.payment_method,
                    p.payment_status,
                    p.payment_date,
                    p.received_by
             FROM appointments a
             JOIN patients pt ON pt.patient_id = a.patient_id
             JOIN users u     ON u.user_id     = pt.user_id
             JOIN services s  ON s.service_id  = a.service_id
             LEFT JOIN payments p ON p.appointment_id = a.appointment_id
             ORDER BY a.appointment_date DESC, a.appointment_id DESC"
        )->fetchAll();
    }

    public static function markPaid(int $appointmentId, string $method, string $receivedBy): void
    {
        if (!in_array($method, ['Cash', 'Card', 'Other'], true)) {
            $method = 'Cash';
        }

        $pdo = self::db();

        
        $stmt = $pdo->prepare(
            "SELECT s.price
             FROM appointments a
             JOIN services s ON s.service_id = a.service_id
             WHERE a.appointment_id = :id"
        );
        $stmt->execute(['id' => $appointmentId]);
        $amount = $stmt->fetchColumn();
        if ($amount === false) {
            return;
        }

        $stmt = $pdo->prepare(
            "INSERT INTO payments
                (appointment_id, amount, payment_method, payment_status, payment_date, received_by)
             VALUES
                (:id, :amount, :method, 'Paid', NOW(), :by)
             ON DUPLICATE KEY UPDATE
                payment_status = 'Paid',
                payment_method = VALUES(payment_method),
                payment_date   = NOW(),
                received_by    = VALUES(received_by)"
        );
        $stmt->execute([
            'id'     => $appointmentId,
            'amount' => $amount,
            'method' => $method,
            'by'     => $receivedBy,
        ]);
    }
}