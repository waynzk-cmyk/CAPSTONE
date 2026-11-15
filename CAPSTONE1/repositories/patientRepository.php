<?php
class PatientRepository extends DBRepository
{
    public static function all(): array
    {
        $rows = self::db()->query(
            "SELECT p.patient_id, u.full_name
             FROM patients p
             JOIN users u ON u.user_id = p.user_id
             ORDER BY p.patient_id"
        )->fetchAll();

        return self::toPatients($rows, 'active');
    }

    // Patients that have at least one Pending appointment
    public static function pending(): array
    {
        $rows = self::db()->query(
            "SELECT DISTINCT p.patient_id, u.full_name
             FROM patients p
             JOIN users u        ON u.user_id = p.user_id
             JOIN appointments a ON a.patient_id = p.patient_id
             WHERE a.status = 'Pending'
             ORDER BY p.patient_id"
        )->fetchAll();

        return self::toPatients($rows, 'pending');
    }

    // One patient with all editable details
    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare(
            "SELECT p.patient_id, u.full_name, u.email,
                    p.phone, p.date_of_birth, p.gender, p.address
             FROM patients p
             JOIN users u ON u.user_id = p.user_id
             WHERE p.patient_id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    // Name and email live in "users", the rest in "patients"
    public static function update(int $id, array $d): void
    {
        $pdo = self::db();
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("SELECT user_id FROM patients WHERE patient_id = :id");
            $stmt->execute(['id' => $id]);
            $userId = $stmt->fetchColumn();
            if (!$userId) {
                throw new Exception('Patient not found.');
            }

            $stmt = $pdo->prepare(
                "UPDATE users SET full_name = :name, email = :email WHERE user_id = :uid"
            );
            $stmt->execute([
                'name'  => $d['full_name'],
                'email' => $d['email'],
                'uid'   => $userId,
            ]);

            $stmt = $pdo->prepare(
                "UPDATE patients
                 SET phone = :phone, date_of_birth = :dob, gender = :gender, address = :address
                 WHERE patient_id = :id"
            );
            $stmt->execute([
                'phone'   => $d['phone'],
                'dob'     => $d['date_of_birth'] !== '' ? $d['date_of_birth'] : null,
                'gender'  => $d['gender'],
                'address' => $d['address'],
                'id'      => $id,
            ]);

            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

        // Cancel = keep the record, set status to Cancelled
    public static function cancelPending(int $id): void
    {
        $stmt = self::db()->prepare(
            "UPDATE appointments
             SET status = 'Cancelled'
             WHERE patient_id = :id AND status = 'Pending'"
        );
        $stmt->execute(['id' => $id]);
    }

    // Delete = remove the pending appointment rows (skips any that have a payment)
        // Delete a patient completely: payments, appointments, patient record and user account
    public static function deletePatient(int $id): void
    {
        $pdo = self::db();
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("SELECT user_id FROM patients WHERE patient_id = :id");
            $stmt->execute(['id' => $id]);
            $userId = $stmt->fetchColumn();

            // 1. payments of this patient's appointments
            $stmt = $pdo->prepare(
                "DELETE pay
                 FROM payments pay
                 JOIN appointments a ON a.appointment_id = pay.appointment_id
                 WHERE a.patient_id = :id"
            );
            $stmt->execute(['id' => $id]);

            // 2. the appointments
            $stmt = $pdo->prepare("DELETE FROM appointments WHERE patient_id = :id");
            $stmt->execute(['id' => $id]);

            // 3. the patient record
            $stmt = $pdo->prepare("DELETE FROM patients WHERE patient_id = :id");
            $stmt->execute(['id' => $id]);

            // 4. the user account (only if it is a patient account)
            if ($userId) {
                $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = :uid AND role = 'patient'");
                $stmt->execute(['uid' => $userId]);
            }

            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    private static function toPatients(array $rows, string $status): array
    {
        return array_map(
            fn($r) => new Patient(
                sprintf('P-%04d', $r['patient_id']),  // 1 becomes P-0001
                $r['full_name'],
                '',                                    // the database stores one full name
                $status
            ),
            $rows
        );
    }
}

