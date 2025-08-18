<?php
    namespace App\Models;
    use PDO;

    class FormModel {
        private $db;
        
        public function __construct(PDO $db) {
            $this->db = $db;
        }

        public function addForm(array $data): bool{
            $stmt = $this->db->prepare(
                "INSERT INTO request (
                    lastname, firstname, email, phonenumber,
                    content, visit_requested, callback_requested, more_pictures_requested,
                    availability_date, availability_hour, availability_minute
                ) VALUES (
                    :lastname, :firstname, :email, :phonenumber,
                    :content, :visit_requested, :callback_requested, :more_pictures_requested
                    :availability_date, :availability_hour, :availability_minute
                )
            ");

            return $stmt->execute([
                ':lastname' => $data['lastname'],
                ':firstname' => $data['firstname'],
                ':email' => $data['email'],
                ':phonenumber' => $data['phonenumber'],
                ':content' => $data['content'],
                ':visit_requested' => $data['visit_requested'],
                ':callback_requested' => $data['callback_requested'],
                ':more_pictures_requested' => $data['more_pictures_requested'],
                ':availability_date' => $data['availability_date'],
                ':availability_hour' => $data['availability_hour'],
                ':availability_minute' => $data['availability_minute']          
            ]);
        }
    }
?>