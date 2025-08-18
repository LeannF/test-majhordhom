<?php

    namespace App\Controllers;
    use App\Config\Database;
    use App\Models\FormModel;
    use App\helper\Flash;

    class FormController{
        private $formModel;

        public function __construct() {
            $pdo = Database::getInstance();
            $this->formModel = new FormModel($pdo);
        }

        public function addForm(){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'lastname' => $_POST['lastname'],
                    'firstname' => $_POST['firstname'],
                    'email' => $_POST['email'],
                    'phonenumber' => $_POST['phonenumber'],
                    'content' => $_POST['content'],
                    'visit_requested' => $_POST['visit_requested'],
                    'callback_requested' => $_POST['callback_requested'],
                    'more_pictures_requested' => $_POST['more_pictures_requested'],
                    'availability_date' => $_POST['availability_date'],
                    'availability_hour' => $_POST['availability_hour'],
                    'availability_minute' => $_POST['availability_minute']

                ];

                if (!$data) {
                    echo "Aucun formulaire reçu";
                    return;
                }

                $success = $this->formModel->addForm($data);
                if ($success) {
                    header("Location: /");
                    Flash::set('success', 'Formulaire envoyé !');
                    exit;
                } else {
                    Flash::set('fail', "Erreur lors de l'envoie du formulaire !");
                }               
            }     
        }                  
    }
?>