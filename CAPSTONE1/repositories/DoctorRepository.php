<?php
class DoctorRepository extends DBRepository
{
    public static function all(): array
    {
        $pdo = self::db();

        $doctors = $pdo->query(
            "SELECT doctor_id, full_name, specialization, phone, email, status
             FROM doctors
             ORDER BY full_name"
        )->fetchAll();

        $rows = $pdo->query(
            "SELECT doctor_id, day_of_week, start_time, end_time
             FROM doctor_availability
             ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'),
                      start_time"
        )->fetchAll();

        $schedule = [];
        foreach ($rows as $r) {
            $schedule[$r['doctor_id']][] = $r;
        }

        foreach ($doctors as &$doctor) {
            $doctor['schedule'] = $schedule[$doctor['doctor_id']] ?? [];
        }
        return $doctors;
    }
}