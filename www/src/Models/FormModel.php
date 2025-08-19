<?php
    namespace App\Models;
    use PDO;

    class FormModel {
        private $db;
        
        public function __construct(PDO $db) {
            $this->db = $db;
        }

        public function addForm(array $data) {
            $sql = "
                INSERT INTO form (
                    lastname, firstname, mail, phonenumber,
                    content, visit_requested, callback_requested, more_pictures_requested,
                    availability_date
                ) VALUES (
                    :lastname, :firstname, :mail, :phonenumber,
                    :content, :visit_requested, :callback_requested, :more_pictures_requested,
                    :availability_date
                )
            ";
            $stmt = $this->db->prepare($sql);

            try {
                $stmt->execute([
                    ':lastname' => $data['lastname'],
                    ':firstname' => $data['firstname'],
                    ':mail' => $data['mail'],
                    ':phonenumber' => $data['phonenumber'],
                    ':content' => $data['content'],
                    ':visit_requested' => $data['visit_requested'],
                    ':callback_requested' => $data['callback_requested'],
                    ':more_pictures_requested' => $data['more_pictures_requested'],
                    // On stocke le tableau de dispos en JSON
                    ':availability_date' => $data['availability_date']
                ]);
                return (int) $this->db->lastInsertId();
            } catch (\PDOException $e) {
                // log + echo pour voir l’erreur SQL exacte (ex: duplicate)
                file_put_contents('/tmp/sql_error.log', $e->getMessage()."\n", FILE_APPEND);
                echo "Erreur SQL : ".$e->getMessage();
                return false;
            }
        }

        public function getByMail(string $mail): ?array{
            $sql = "SELECT * FROM form WHERE mail = :mail LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null; // retourne null si aucun résultat
        }

        public function updateFormByMail(string $mail, array $data): bool
        {
            $sql = "UPDATE form SET 
                        lastname = :lastname,
                        firstname = :firstname,
                        phonenumber = :phonenumber,
                        content = :content,
                        visit_requested = :visit_requested,
                        callback_requested = :callback_requested,
                        more_pictures_requested = :more_pictures_requested,
                        availability_date = :availability_date
                    WHERE mail = :mail";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':lastname', $data['lastname'], \PDO::PARAM_STR);
            $stmt->bindValue(':firstname', $data['firstname'], \PDO::PARAM_STR);
            $stmt->bindValue(':phonenumber', $data['phonenumber'], \PDO::PARAM_STR);
            $stmt->bindValue(':content', $data['content'], \PDO::PARAM_STR);
            $stmt->bindValue(':visit_requested', $data['visit_requested'], \PDO::PARAM_INT);
            $stmt->bindValue(':callback_requested', $data['callback_requested'], \PDO::PARAM_INT);
            $stmt->bindValue(':more_pictures_requested', $data['more_pictures_requested'], \PDO::PARAM_INT);
            $stmt->bindValue(':availability_date', $data['availability_date'], \PDO::PARAM_STR);
            $stmt->bindValue(':mail', $mail, \PDO::PARAM_STR);

            return $stmt->execute();
        }
    }
?>