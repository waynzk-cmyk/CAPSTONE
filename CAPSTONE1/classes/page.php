<?php
class Page
{
    public static function display(string $page)
    {
        switch ($page) {
            case 'patients':
            case 'appointments':
                include 'modules/patient_display.php';
                break;
            case 'doctors':
                include 'modules/doctor_display.php';
                break;
            case 'payments':
                include 'modules/payment_display.php';
                break;
            default:
                include 'components/hero.php';
        }
    }
}