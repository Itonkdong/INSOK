<?php
//
//trait Treatable
//{
//    public function diagnose(Patient $patient, string $diagnose): void
//    {
//        $patient->addDiagnose($diagnose);
//    }
//
//}
//
//enum Specialization: string
//{
//    case FAMILY_MEDICINE = "FAMILY_MEDICINE";
//    case CARDIOLOGY = "CARDIOLOGY";
//    case NEUROLOGY = "NEUROLOGY";
//    case RADIOLOGY = "RADIOLOGY";
//}
//
//class Patient
//{
//    public int $id;
//    public string $name;
//    private array $medicalHistory = [];
//    private array $treatmentHistory = [];
//
//    /**
//     * @param int $id
//     * @param string $name
//     * @param array $medicalHistory
//     * @param array $treatmentHistory
//     */
//    public function __construct(int $id, string $name)
//    {
//        $this->id = $id;
//        $this->name = $name;
//        $this->medicalHistory = [];
//        $this->treatmentHistory = [];
//    }
//
//    public function getId(): int
//    {
//        return $this->id;
//    }
//
//    public function getName(): string
//    {
//        return $this->name;
//    }
//
//    public function getMedicalHistory(): array
//    {
//        return $this->medicalHistory;
//    }
//
//    public function getTreatmentHistory(): array
//    {
//        return $this->treatmentHistory;
//    }
//
//    public function addDiagnose($diagnose): void
//    {
//        $this->medicalHistory[] = $diagnose;
//    }
//
//    public function __toString(): string
//    {
//        return "$this->name (id: $this->id)\n";
//    }
//
//    public function addTreatment($treatment): void
//    {
//        $this->treatmentHistory[] = $treatment;
//    }
//
//}
//
//
//class Doctor
//{
//    use Treatable;
//
//    protected string $id;
//    public string $name;
//    protected Specialization $specialization;
//    protected array $patients = [];
//    protected int $experience;
//
//    /**
//     * @param string $id
//     * @param string $name
//     * @param Specialization $specialization
//     * @param int $experience
//     */
//    public function __construct(string $id, string $name, int $experience, Specialization $specialization)
//    {
//        $this->id = $id;
//        $this->name = $name;
//        $this->specialization = $specialization;
//        $this->experience = $experience;
//        $this->patients = [];
//    }
//
//    public function addPatient(Patient $patient): void
//    {
//        $this->patients[$patient->getId()] = $patient;
//    }
//
//    public function printPatients(): void
//    {
//        foreach ($this->patients as $patient) {
//            echo $patient;
//        }
//    }
//
//    public function getId(): string
//    {
//        return $this->id;
//    }
//
//    public function getName(): string
//    {
//        return $this->name;
//    }
//
//    public function getSpecialization(): Specialization
//    {
//        return $this->specialization;
//    }
//
//    public function getPatients(): array
//    {
//        return $this->patients;
//    }
//
//    public function getExperience(): int
//    {
//        return $this->experience;
//    }
//
//    public function hasPatient(Patient $patient): bool
//    {
//        return isset($this->patients[$patient . $this->getId()]);
//    }
//
//
//}
//
//
//class FamilyDoctor extends Doctor
//{
//    public function __construct(string $id, string $name, int $experience)
//    {
//        parent::__construct($id, $name, $experience, Specialization::FAMILY_MEDICINE);
//    }
//
//
//    function refer(Patient $patient, array $doctors, Specialization $specialization): ?Doctor
//    {
//        $filtered = array_filter($doctors, fn(Doctor $doctor) => $doctor->getSpecialization() === $specialization);
//
//        /** @var Doctor $maxDoctor */
//        $maxDoctor = $filtered[0];
//
//        foreach ($filtered as $doctor) {
//            if ($doctor->getExperience() > $maxDoctor->getExperience()) {
//                $maxDoctor = $doctor;
//            }
//        }
//
//        if ($maxDoctor->hasPatient($patient)) {
//            echo "Patient already assigned";
//        } else {
//            $maxDoctor->addPatient($patient);
//        }
//        return $maxDoctor;
//    }
//}
//
//class Specialist extends Doctor
//{
//    public function __construct(string $id, string $name, int $experience, Specialization $specialization)
//    {
//        parent::__construct($id, $name, $experience, $specialization);
//    }
//
//    public function treatPatient(Patient $patient, string $treatment)
//    {
//        $patient->addTreatment($treatment);
//        unset($this->patients[$patient->getId()]);
//    }
//}
//
//
//// Create patients
//$john = new Patient(1, "John Doe");
//$jane = new Patient(2, "Jane Smith");
//
//// Create doctors
//$familyDoctor = new FamilyDoctor("D001", "Dr. Brown", 12);
//$cardiologist1 = new Specialist("D002", "Dr. Heart", 8, Specialization::CARDIOLOGY);
//$cardiologist2 = new Specialist("D003", "Dr. Pulse", 15, Specialization::CARDIOLOGY);
//$neurologist = new Specialist("D004", "Dr. Brain", 10, Specialization::NEUROLOGY);
//
//// Add patient to family doctor
//$familyDoctor->addPatient($john);
//$familyDoctor->diagnose($john, 'High blood pressure');
//// Print before referral
//$familyDoctor->printPatients();
//
//// Refer John to cardiologist (most experienced one)
//$treatingDoctor = $familyDoctor->refer($john, [$cardiologist1, $cardiologist2, $neurologist], Specialization::CARDIOLOGY);
//echo "Referred patient with id $john->id to doctor $treatingDoctor->name\n";
//
//// Refer the same patient again (should return that patient is already referred)
//$treatingDoctor = $familyDoctor->refer($john, [$cardiologist1, $cardiologist2, $neurologist], Specialization::CARDIOLOGY);
//
//$treatingDoctor->printPatients();
//
//if ($treatingDoctor instanceof Specialist) {
//    $treatingDoctor->treatPatient($john, 'Beta-blockers');
//}
//
//// Print specialists’ patients after referral
//$treatingDoctor->printPatients();
//
//// Show John’s medical history
//echo "\nMedical history of {$john->name}:\n";
//foreach ($john->getMedicalHistory() as $record) {
//    echo "- $record\n";
//}
