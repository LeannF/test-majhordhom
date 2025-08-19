<?php
    
    require __DIR__ . '/vendor/autoload.php';
    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(__DIR__); // __DIR__ = www/
    $dotenv->safeLoad(); // safeLoad évite les erreurs si le .env n'existe pas


    use App\Controllers\FormController;

    $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    if ($uri === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        (new FormController())->addForm();
        exit;
    }
    // route par défaut
    echo "404 - route inconnue : $uri";
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Formulaire de contact</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/main.css" rel="stylesheet">
    </head>
    <body>
        <!-- CONTACT SECTION --> 
         <form action="/add" method="POST" class="row" >
            <h1 class="my-5">CONTACTEZ L'AGENCE</h1>
            <section class="col-md-6 mb-3">
                <h2>Vos coordonnées</h2>
                <div class="d-row">
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="radioDefault" id="radioDefault1">
                    <label class="form-check-label" for="radioDefault1">
                        Mme
                    </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioDefault" id="radioDefault2">
                        <label class="form-check-label" for="radioDefault2">
                            M
                        </label>
                    </div>
                </div>
                <div class="d-row mb-3">
                    <div class="col-md-6 ">
                        <input type="text" class="form-control" name="lastname" placeholder="Nom" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="firstname" placeholder="Prénom" required>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <input type="email" class="form-control" name="mail" placeholder="Adresse mail" required>
                </div>
                <div class="col-12 mb-3">
                    <input type="tel" class="form-control" name="phonenumber" pattern="(?:\+33|0)\s?[1-9](?:\s?\d{2}){4}" placeholder="Téléphone" required>
                </div>
            </section>
            <!-- MESSAGE SECTION --> 
            <section class="col-md-6 mb-3">
                <h2>Votre message</h2>
                <div class="d-row mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="visit_requested" id="visit_requested" value="1">
                        <label class="form-check-label" for="visit_requested">
                            Demande de visite
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="callback_requested" id="callback_requested" value="1">
                        <label class="form-check-label" for="callback_requested">
                            Etre rappelé.e
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="more_pictures_requested" id="more_pictures_requested" value="1">
                        <label class="form-check-label" for="more_pictures_requested">
                            Plus de photos
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <textarea class="form-control-textarea" name="content" rows="5" placeholder="Votre message"></textarea>
                </div>
            </section>
            <!-- DISPONIBILITY SECTION --> 
            <section class="col-md-7">
                <h2>Disponibilités pour une visite</h2>
                <div class="d-row">
                    <select id="jour" class="form-select form-select-md mb-3" aria-label="Large select example" required>
                        <option>Jour</option>
                        <?php 
                            $jours=['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
                            foreach ($jours as $jour) {
                                echo "<option value=\"$jour\">".ucfirst($jour)."</option>";
                            }
                        ?>

                    </select>
                    <select id="heure" class="form-select form-select-md mb-3" aria-label="Large select example" required>
                        <option>Heure</option>
                        <?php 
                            for ($h = 7; $h <= 17; $h++) {
                                $heure = str_pad($h, 2, "0", STR_PAD_LEFT); // ajoute un 0 devant si besoin
                                echo "<option value=\"$heure\">$heure</option>";
                            }
                        ?>

                    </select>
                        <select id="minute" class="form-select form-select-sm mb-3" aria-label="Large select example" required>
                        <option>Minute</option>
                        <?php 
                            $minutes=[0,10,20,30,40,50];
                            foreach ($minutes as $m) {
                                echo "<option value=\"$m\">".ucfirst($m)."</option>";
                            }
                        ?>
                    </select>
                    <div class="col-3">
                        <button type="button" id="ajouter" class="btn btn-primary">Ajouter dispo</button>
                    </div>
                </div>
            </section>
           

            <div class="col-5">
                <button type="submit" class="btn btn-warning">Envoyer</button>
                <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars($_ENV['RECAPTCHA_SITE_KEY']) ?>"></div>
            </div>
            <div class="col-12" id="dispo-list"></div>
            <input type="hidden" name="availability_date" id="dispo-hidden">
            
        </form>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script src="js/script.js"></script>
    </body>
</html>