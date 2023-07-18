<?php
if (isset($_POST['formsend'])) {
    extract($_POST);
    if (!empty($email) && !empty($password)) {
        include 'database.php';
        //récuperer value des input
        if (!empty($email) && !empty($password)) {

            //selectionner dans la db l'email
            $q = $db->prepare("SELECT * FROM user WHERE email = :email");
            $q->execute([
                'email' => $email,
            ]);
            //récuperer infos db
            session_start();
            $result = $q->fetch();
            //si $result renvoie true alors verifier que $hashpassword est le bon
            if ($result == true) {
                $hashpassword = $result['password'];
                //si $hashpassword est correcte alors intialiser les variables
                if (password_verify($password, $hashpassword)) {
                    $UUID = $result['UUID'];
                    $_SESSION['user_id'] = $UUID;
                    header('Location: index.php');
                    exit();
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page inscription Copry</title>
    <link rel="stylesheet" href="auth.css" />
    <link rel="stylesheet" href="style.css">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-T8YHSVH3RQ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'G-T8YHSVH3RQ');
    </script>
</head>

<body>
    <!-- Formulaire-->
    <div class="container_form inscription">
        <form method="post" class="orm">
            <h2 id="slogan">Connexion</h2>
            <div class="input_form_container">
                <label for="email">Email <span>*</span></label>
                <input class="input email" id="email" type="email" value="" name="email" placeholder="Hello@copry.com">
                <label for="password">Password <span>*</span></label>
                <input class="input" id="password" type="password" value="" name="password" placeholder="Password">
                <input class="btn" name="formsend" id="formsend" type="submit" value="Connexion">
            </div>
            <div class="forgot_connexion_container">
                <p>Pas encore membre? <a href="register.php">cliquer ici</a></p>
            </div>
    </div>
    </form>
</body>

</html>

</html>