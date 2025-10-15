<?php
/*
Tout le code doit se faire dans ce fichier PHP

Réalisez un formulaire HTML contenant :
- firstname
- lastname
- email
- pwd
- pwdConfirm

Créer une table "user" dans la base de données, regardez le .env à la racine et faites un build de docker
si vous n'arrivez pas à les récupérer pour qu'il les prenne en compte

Lors de la validation du formulaire vous devez :
- Nettoyer les valeurs, exemple trim sur l'email et lowercase (5 points) FAIT
- Attention au mot de passe (3 points) FAIT
- Attention à l'unicité de l'email (4 points) FAIT
- Vérifier les champs sachant que le prénom et le nom sont facultatifs FAIT
- Insérer en BDD avec PDO et des requêtes préparées si tout est OK (4 points) FAIT
- Sinon afficher les erreurs et remettre les valeurs pertinantes dans les inputs (4 points) FAIT

Le design je m'en fiche mais pas la sécurité

Bonus de 3 points si vous arrivez à envoyer un mail via un compte SMTP de votre choix
pour valider l'adresse email en bdd

Pour le : 22 Octobre 2025 - 8h
M'envoyer un lien par mail de votre repo sur y.skrzypczyk@gmail.com
Objet du mail : TP1 - 2IW3 - Nom Prénom
Si vous ne savez pas mettre votre code sur un repo envoyez moi une archive
*/

use PHPMailer\PHPMailer\PHPMailer;

$db = new PDO("pgsql:host=pg-db dbname=devdb user=devuser password=devpass");
$message = "";
$classValue = "";

if (!empty($_POST)) {
    $firstName = ucfirst(trim($_POST['firstName']));
    $lastName = ucfirst(trim($_POST['lastName']));
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $clearEmail = strtolower(trim($_POST['email']));
    if (!$password || !$confirmPassword) {
        $message = "Erreur ajouter un mot de passe.";
        $classValue = 'error_message';
    } elseif (!$clearEmail) {
        $message =  "Email manquant.";
        $classValue = 'error_message';
    } else {
        $userMail = $db->prepare('SELECT email from "user" WHERE email = :email LIMIT 1');
        $userMail->execute(['email' => $clearEmail]);
        $confirmMail = $userMail->fetch(PDO::FETCH_ASSOC);
        if ($confirmMail) {
            $message = 'Votre email est déjà relié à un compte';
            $classValue = 'error_message';
        } elseif ($password !== $confirmPassword) {
            $message = "Erreur les mots de passe ne correspondent pas.";
            $classValue = 'error_message';
        } else {
            $activation_token = bin2hex(random_bytes(32));
            $activation_token_hash = hash("sha256", $activation_token);
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $res = $db->prepare('INSERT INTO "user" (firstname, lastname, email, password, account_activation_hash) VALUES (:firstname, :lastname, :email, :password, :account_activation_hash)');
            $res->execute([
                ':firstname' => $firstName,
                ':lastname' => $lastName,
                ':email' => $clearEmail,
                ':password' => $hashPassword,
                ':account_activation_hash' => $activation_token_hash
            ]);
            require_once './SendMailFunction.php';
            $mail = new PHPMailer(true);
            $result = sendAccountMail($mail, $clearEmail, $activation_token);
            $message = $result;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="./style.css" rel="stylesheet"/>
    <title>TP1</title>
</head>

<body>
    <section class="content">
        <form method="POST" class="post_container">
            <h1>Inscription</h1>
            <div>
                <label for="firstName">Entrer votre prénom:</label><br/>
                <input type="text" name="firstName" placeholder="Prénom" class="form_input" value="<?php if (isset($_POST['firstName'])) {
                    echo htmlspecialchars($_POST['firstName']);
                } ?>">
            </div>
            <div>
                <label for="lastName">Entrer votre nom:</label><br/>
                <input type="text" name="lastName" placeholder="Nom" class="form_input" value="<?php if (isset($_POST['lastName'])) {
                    echo htmlspecialchars($_POST['lastName']);
                } ?>">
            </div>
            <div>
                <label for="email">Entrer votre email:</label><br/>
                <input type="email" name="email" placeholder="Email" class="form_input" required value="<?php if (isset($_POST['email'])) {
                    echo htmlspecialchars($_POST['email']);
                } ?>">
            </div>
            <div>
                <label for="password">Entrer votre mot de passe:</label><br/>
                <input type="password" name="password" placeholder="Mot de passe" class="form_input" required>
            </div>
            <div>
                <label for="password">Confirmer le mot de passe:</label><br/>
                <input type="password" name="confirmPassword" placeholder="Confirmer le Mot de passe" class="form_input" required>
            </div>
            <div>
                <input type="submit" class="form_button">
            </div>
            <?php if ($message) :?>
                <p class=<?php echo $classValue; ?>><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            
           
        </form>
    </section>
</body>
</html>