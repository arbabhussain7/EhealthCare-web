<?php
namespace care\models;
use care\core\Model;
use Exception;
use PDO;

class DoctorModel extends Model{
    /**
     * @throws Exception
     */
    public function __construct() {
        parent::__construct();
    }


    public function registerDoctor($data) {
        if ($this->isDuplicateEmail($data['email'])) {
            throw new Exception('Email already exists.');
        }

        if ($this->isDuplicatePmcNo($data['pmc_no'])) {
            throw new Exception('PMC No. already exists.');
        }

        // Start a transaction
        $this->db->beginTransaction();

        try {
            // Insert into tbl_doctor
            $sql = "INSERT INTO tbl_doctor (full_name, phone_no, email, city, pmc_no, specialty, gender)
                VALUES (:full_name, :phone_no, :email, :city, :pmc_no, :specialty, :gender)";

            $params = [
                ':full_name' => $data['full_name'],
                ':phone_no' => $data['phone_no'],
                ':email' => $data['email'],
                ':city' => $data['city'],
                ':pmc_no' => $data['pmc_no'],
                ':specialty' => $data['specialty'],
                ':gender' => $data['gender'],
            ];

            $this->db->query($sql, $params);
            $doctor_id = $this->db->lastInsertId();

            // Insert initial education record
            $sqlEducation = "INSERT INTO tbl_education (doctor_id, institution, subject, starting_date, complete_date, degree, grade)
                         VALUES (:doctor_id, NULL, NULL, NULL, NULL, NULL, NULL)";

            $paramsEducation = [':doctor_id' => $doctor_id];

            $this->db->query($sqlEducation, $paramsEducation);

            // Insert initial experience record
            $sqlExperience = "INSERT INTO tbl_experience (doctor_id, company_name, location, job_position, period_from, period_to)
                          VALUES (:doctor_id, NULL, NULL, NULL, NULL, NULL)";

            $paramsExperience = [':doctor_id' => $doctor_id];

            $this->db->query($sqlExperience, $paramsExperience);

            // Commit the transaction
            $this->db->commit();

            return $doctor_id;
        } catch (Exception $e) {
            // Rollback the transaction if there’s an error
            $this->db->rollBack();
            error_log("Error in DoctorModel::registerDoctor - " . $e->getMessage());
            return false;
        }
    }
    private function isDuplicateEmail($email): bool
    {
        $sql = "SELECT COUNT(*) FROM tbl_doctor WHERE email = :email";
        $params = [':email' => $email];
        $count = $this->db->fetchOne($sql, $params)['COUNT(*)'];
        return $count > 0;
    }

    private function isDuplicatePmcNo($pmc_no): bool
    {
        $sql = "SELECT COUNT(*) FROM tbl_doctor WHERE pmc_no = :pmc_no";
        $params = [':pmc_no' => $pmc_no];
        $count = $this->db->fetchOne($sql, $params)['COUNT(*)'];
        return $count > 0;
    }

    public function authenticDoctor($email, $password)
    {
        $stmt = $this->db->prepare('SELECT * FROM tbl_doctor WHERE email = ?');
        $stmt->execute([$email]);
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if ($doctor && password_verify($password, $doctor['password'])) {
            return $doctor;
        }

        return false;

    }
    /**
     * Retrieves the patient name by patient ID.
     *
     * @param int $patientId Patient ID
     * @return string|null Patient name or null if not found
     */
    public function getPatientNameById(int $patientId): ?string {
        $sql = "SELECT name FROM tbl_patient WHERE patient_id = :patient_id";
        $params = [':patient_id' => $patientId];
        $result = $this->db->fetchOne($sql, $params);
        return $result ? $result['name'] : null;
    }

    /**
     * Retrieves all appointments for a specific doctor.
     *
     * @param int $doctorId Doctor ID
     * @return array List of appointments
     */
    public function getAppointmentsByDoctorId(int $doctorId): array {
        $sql = "SELECT a.id, p.name AS patient_name, d.full_name AS doctor_name, 
                a.status, a.start_time, a.end_time, a.meeting_link, a.appointment_status
                FROM tbl_appointment AS a
                JOIN tbl_patient AS p ON a.patient_id = p.id
                JOIN tbl_doctor AS d ON a.doctor_id = d.id
                WHERE a.doctor_id = :doctor_id";
        $params = [':doctor_id' => $doctorId];
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * @throws Exception
     */
    public function getAppointmentById($id)
    {
        $sql = "SELECT * FROM tbl_appointment WHERE id = :id";
        $params = [':id' => $id];

        try {
            $result = $this->db->fetchOne($sql, $params);
            return $result ? $result : null; // Return result or null if not found
        } catch (Exception $e) {
            error_log("Error in DoctorModel::getAppointmentById - " . $e->getMessage());
            throw new Exception("Error retrieving appointment.");
        }
    }

    // In DoctorModel.php

    /**
     * @throws Exception
     */
    /**
     * @throws Exception
     */
    public function updateAppointmentStatus($id, $status) {
        $sql = "UPDATE tbl_appointment SET appointment_status = :status WHERE id = :id ";
        $params = [
            ':status' => $status,
            ':id' => $id
        ];

        try {
            // Execute the query and get the number of affected rows
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $rowsAffected = $stmt->rowCount();
            return $rowsAffected > 0; // Return true if at least one row was updated
        } catch (Exception $e) {
            error_log("Error in DoctorModel::updateAppointmentStatus - " . $e->getMessage());
            throw new Exception("Error updating appointment status.");
        }
    }

}
