<?php
class Patient extends Person
{
    public string $patient_id;
    protected string $status;

    public function __construct(string $patient_id, string $firstname, string $lastname, string $status)
    {
        $this->patient_id = $patient_id;
        $this->status = $status;
        parent::__construct($firstname, $lastname);
    }

    public function getPatientId(): string
    {
        return $this->patient_id;
    }

    public function getStatus(): string
    {
        return ucfirst($this->status);
    }

    public function isPending(): bool{
    return strtolower($this->status) === 'pending';
    }

        public function completeName(): string
    {
        return $this->lastname === '' ? $this->firstname : parent::completeName();
    }


    public function statusColor(): string
    {
        return match (strtolower($this->status)) {
            'active'   => 'success',
            'inactive' => 'secondary',
            'pending'  => 'warning',
            default    => 'dark',
        };
    }
}