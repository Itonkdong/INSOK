<?php

enum Specialization
{
    case FAMILY_MEDICINE;
    case CARDIOLOGY;
    case NEUROLOGY;
    case RADIOLOGY;
}

trait Treatable
{
    function diagnose(Patient $patient, string $diagnosis): void
    {
        $patient->addDiagnose($diagnosis);
    }
}

class Patient
{
    public int $id;
    public string $name;
    public array $medicalHistory;
    private array $treatmentHistory;

    /**
     * @param string $name
     * @param int $id
     */
    public function __construct(int $id, string $name)
    {
        $this->name = $name;
        $this->id = $id;
        $this->medicalHistory = [];
        $this->treatmentHistory = [];
    }

    public function __toString(): string
    {
        return "($this->id) $this->name\n";
    }

    public function addDiagnose(string $diagnosis): void
    {
        $this->medicalHistory[] = $diagnosis;
    }

    public function addTreatment(string $treatment): void
    {
        $this->treatmentHistory[] = $treatment;
    }


}

abstract class Doctor
{

    protected string $id;
    public string $name;
    protected Specialization $specialization;
    protected int $experience;
    protected array $patients;

    /**
     * @param string $name
     * @param string $id
     * @param int $experience
     */
    public function __construct(string $id, string $name, int $experience)
    {
        $this->id = $id;
        $this->name = $name;
        $this->experience = $experience;
        $this->patients = [];
    }

    function addPatient(Patient $patient): void
    {
        $this->patients[$patient->id] = $patient;
    }

    protected function hasPatient(Patient $patient): bool
    {
        return isset($this->patients[$patient->id]);
    }

    protected function removePatient(Patient $patient): void
    {
        if (!$this->hasPatient($patient)) return;

        unset($this->patients[$patient->id]);
    }

    public function printPatients(): void
    {
        if (count($this->patients) === 0)
        {
            echo "Doctor has no patients\n";
            return;
        }

        foreach ($this->patients as $patient) {
            echo $patient;
        }

    }

    public function getSpecialization(): Specialization
    {
        return $this->specialization;
    }

    public function getExperience(): int
    {
        return $this->experience;
    }

}

class FamilyDoctor extends Doctor
{
    use Treatable;

    public function __construct(string $id, string $name, int $experience)
    {
        parent::__construct($id, $name, $experience);
        $this->specialization = Specialization::FAMILY_MEDICINE;
    }

    function refer(Patient $patient, array $doctors, Specialization $specialization): Doctor
    {
        /** @var Doctor[] $targetDoctors */
        $targetDoctors = array_filter($doctors, fn(Doctor $doctor) => $doctor->getSpecialization() === $specialization);

        usort($targetDoctors, fn(Doctor $doctor1, Doctor $doctor2) => $doctor1->getExperience() <=> $doctor2->getExperience());

        $targetDoctor = end($targetDoctors);

        if (!$targetDoctor->hasPatient($patient)) {
            $targetDoctor->addPatient($patient);
            return $targetDoctor;
        }

        echo "Doctor already has this patient\n";
        return $targetDoctor;

    }

}

class Specialist extends Doctor
{
    public function __construct(string $id, string $name, int $experience, Specialization $specialization)
    {
        parent::__construct($id, $name, $experience);
        $this->specialization = $specialization;
    }

    public function treatPatient(Patient $patient, string $treatment): void
    {
        $patient->addTreatment($treatment);
        $this->removePatient($patient);
    }

}

function main(): void
{
    // Create patients
    $john = new Patient(1, "John Doe");
    $jane = new Patient(2, "Jane Smith");

// Create doctors
    $familyDoctor = new FamilyDoctor("D001", "Dr. Brown", 12);
    $cardiologist1 = new Specialist("D002", "Dr. Heart", 8, Specialization::CARDIOLOGY);
    $cardiologist2 = new Specialist("D003", "Dr. Pulse", 15, Specialization::CARDIOLOGY);
    $neurologist = new Specialist("D004", "Dr. Brain", 10, Specialization::NEUROLOGY);

// Add patient to family doctor
    $familyDoctor->addPatient($john);
    $familyDoctor->diagnose($john, 'High blood pressure');
// Print before referral
    $familyDoctor->printPatients();

// Refer John to cardiologist (most experienced one)
    $treatingDoctor = $familyDoctor->refer($john, [$cardiologist1, $cardiologist2, $neurologist], Specialization::CARDIOLOGY);
    echo "Referred patient with id $john->id to doctor $treatingDoctor->name\n";

// Refer the same patient again (should return that patient is already referred)
    $treatingDoctor = $familyDoctor->refer($john, [$cardiologist1, $cardiologist2, $neurologist], Specialization::CARDIOLOGY);

    $treatingDoctor->printPatients();

    if ($treatingDoctor instanceof Specialist) {
        $treatingDoctor->treatPatient($john, 'Beta-blockers');
    }

// Print specialists’ patients after referral
    $treatingDoctor->printPatients();

// Show John’s medical history
    echo "\nMedical history of {$john->name}:\n";
    foreach ($john->medicalHistory as $record) {
        echo "- $record\n";
    }

}

main();