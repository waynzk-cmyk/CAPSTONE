<?php
class AppointmentRepository extends DBRepository
{
    public static function book(array $data): void
    {
        $pdo = self::db();
        $pdo->beginTransaction();

        try {
            $name  = trim($data['patient_name']);
            $email = trim($data['email']);
            $phone = trim($data['phone']);

            
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $userId = $stmt->fetchColumn();

            if (!$userId) {
                $stmt = $pdo->prepare(
                    "INSERT INTO users (full_name, email, password, role)
                     VALUES (:name, :email, :password, 'patient')"
                );
                $stmt->execute([
                    'name'     => $name,
                    'email'    => $email,
                    
                    'password' => password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT),
                ]);
                $userId = $pdo->lastInsertId();
            }

            
            $stmt = $pdo->prepare("SELECT patient_id FROM patients WHERE user_id = :user_id LIMIT 1");
            $stmt->execute(['user_id' => $userId]);
            $patientId = $stmt->fetchColumn();

            if (!$patientId) {
                $stmt = $pdo->prepare("INSERT INTO patients (user_id, phone) VALUES (:user_id, :phone)");
                $stmt->execute(['user_id' => $userId, 'phone' => $phone]);
                $patientId = $pdo->lastInsertId();
            }

            
            $stmt = $pdo->prepare("SELECT service_id FROM services WHERE service_name = :name LIMIT 1");
            $stmt->execute(['name' => $data['service']]);
            $serviceId = $stmt->fetchColumn();
            if (!$serviceId) {
                throw new Exception('That service is not in the database yet.');
            }

            
            $doctorId = $pdo->query(
                "SELECT doctor_id FROM doctors WHERE status = 'Active' ORDER BY doctor_id LIMIT 1"
            )->fetchColumn();
            if (!$doctorId) {
                throw new Exception('No active doctor is available yet.');
            }

            
            $start = DateTime::createFromFormat('h:i A', $data['time']);
            $end   = (clone $start)->modify('+1 hour');

            
            $stmt = $pdo->prepare(
                "INSERT INTO appointments
                    (patient_id, doctor_id, service_id, appointment_date, start_time, end_time, status, notes)
                 VALUES
                    (:patient_id, :doctor_id, :service_id, :date, :start, :end, 'Pending', :notes)"
            );
            $stmt->execute([
                'patient_id' => $patientId,
                'doctor_id'  => $doctorId,
                'service_id' => $serviceId,
                'date'       => $data['date'],
                'start'      => $start->format('H:i:s'),
                'end'        => $end->format('H:i:s'),
                'notes'      => $data['notes'] ?? null,
            ]);

            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}