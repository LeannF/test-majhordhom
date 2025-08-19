<?php
    namespace App\Controllers;

    use App\Config\Database;
    use App\Models\FormModel;
    use Dotenv\Dotenv;

    class FormController {
        private $formModel;

        public function __construct() {
            $pdo = Database::getInstance();

            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->formModel = new FormModel($pdo);
        }

        public function addForm() {
            header('Content-Type: text/plain; charset=utf-8');

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo "Méthode non autorisée";
                return;
            }
            // --- Vérification reCAPTCHA ---
            $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
            $recaptchaSecretKey = $_ENV['RECAPTCHA_SECRET_KEY'] ?? '';

            if (empty($recaptchaResponse) || empty($recaptchaSecretKey)) {
                http_response_code(400);
                echo "Captcha non configuré ou non soumis";
                return;
            }

            $response = file_get_contents(
                "https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecretKey&response=$recaptchaResponse"
            );
            $result = json_decode($response, true);

            if (!$result['success']) {
                http_response_code(400);
                echo "Captcha non validé";
                return;
            }

            // --- Récupération et validation des données ---
            $lastname = trim($_POST['lastname'] ?? '');
            $firstname = trim($_POST['firstname'] ?? '');
            $mail = trim($_POST['mail'] ?? '');
            $phonenumber = trim($_POST['phonenumber'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $disposJson = $_POST['availability_date'] ?? '[]';
            $dispos = json_decode($disposJson, true);
            if (!is_array($dispos)) $dispos = [];

            // Email valide
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo "Email invalide.";
                return;
            }

            // Téléphone valide 
            if (!empty($phonenumber) && !preg_match('/^[0-9 +()-]+$/', $phonenumber)) {
                http_response_code(400);
                echo "Numéro de téléphone invalide.";
                return;
            }

            // Sécurisation des textes libres
            $lastname = htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8');
            $firstname = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');
            $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

            // Validation des dates
            foreach ($dispos as $date) {
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) { // format YYYY-MM-DD
                    http_response_code(400);
                    echo "Date invalide : $date";
                    return;
                }
            }

            // Récupère le JSON des dispos depuis le champ hidden
            $disposJson = $_POST['availability_date'] ?? '[]';
            $dispos = json_decode($disposJson, true);
            if (!is_array($dispos)) {
                $dispos = [];
            }

            // Données du formulaire
            $data = [
                'lastname' => $_POST['lastname'] ?? '',
                'firstname' => $_POST['firstname'] ?? '',
                'mail' => $_POST['mail'] ?? '',
                'phonenumber' => $_POST['phonenumber'] ?? '',
                'content' => $_POST['content'] ?? '',
                'visit_requested' => isset($_POST['visit_requested']) ? 1 : 0,
                'callback_requested' => isset($_POST['callback_requested']) ? 1 : 0,
                'more_pictures_requested' => isset($_POST['more_pictures_requested']) ? 1 : 0,
                // on passe le tableau PHP au modèle
                'availability_date' => $dispos
            ];

            $existing = $this->formModel->getByMail($data['mail']);
            if ($existing) {
                $existingDates = json_decode($existing['availability_date'], true);
                $newDates = array_unique(array_merge($existingDates, $data['availability_date']));
                $data['availability_date'] = json_encode($newDates);
                $this->formModel->updateFormByMail($data['mail'], $data);
                echo "Dates mises à jour pour l'utilisateur existant.";
            } else {
                $insertId = $this->formModel->addForm($data);
                echo "Nouvel enregistrement créé avec l'id {$insertId}.";
            }

            if ($insertId === false) {
                http_response_code(500);
                echo "INSERT OK";
                return;
            }

            echo "INSERT OK id={$insertId}";
        }
    }
?>