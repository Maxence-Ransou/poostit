<?php
require_once('vendor/stripe/stripe-php/init.php');
if (isset($_POST['formsend'])) {
    extract($_POST);
    include 'database.php';
    $options = [
        'cost' => 12,
    ];
    $hashpass = password_hash($password, PASSWORD_BCRYPT, $options);
    global $db;
    $y = $db->prepare("SELECT email FROM user WHERE email = :email");
    $y->execute(
        [
            'email' => $email
        ]
    );
    $result = $y->rowCount();
    if ($result == 0) {
        $stripe = new \Stripe\StripeClient('sk_test_51NGQUkCJYk0zeR2hjfGdeVOaVEYHj2hwApowtPOEQBFjHcafiTKPoKGB5TxvhmTv8zXHcBTKB7QpmWI1WPeyuDNC00ZEPFRp58');
        $stripe->accounts->create([
            'type' => 'express'
        ]);
        $response = $stripe->accounts->all(['limit' => 3]);
        $id_account = $response['data'][0]['id'];
            $q = $db->prepare("INSERT INTO user(nom, prenom, email, tel, password, UUID, id_account) VALUES(:nom, :prenom, :email, :tel, :password, uuid(), :id_account)");
            $q->execute([
                'nom' => $lastname,
                'prenom' => $surname,
                'email' => $email,
                'tel' => $tel,
                'password' => $hashpass,
                'id_account' => $id_account
            ]);
    } else {
        echo ("Email déjà utilisé");
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
            <h2 id="slogan">Inscription</h2>
            <div class="input_form_container">
                <label for="surname">surname <span>*</span></label>
                <input class="input" id="surname" type="text" value="" name="surname" placeholder="Prenom" required>
                <label for="lastname">Last Name <span>*</span></label>
                <input class="input lastname" id="lastname" type="text" name="lastname" value="" placeholder="nom">
                <label for="email">Email <span>*</span></label>
                <input class="input email" id="email" type="email" value="" name="email" placeholder="Hello@copry.com">
                <label for="tel">Tel <span>*</span></label>
                <input class="input tel" id="tel" type="tel" value="" name="tel" placeholder="0798485568">
                <label for="password">Password <span>*</span></label>
                <input class="input" id="password" type="password" value="" name="password" placeholder="Password">
                <input class="btn" name="formsend" id="formsend" type="submit" value="S'inscrire">
            </div>
            <div class="forgot_connexion_container">
                <p>Déjà membre ? <a href="login.php">cliquer ici</a></p>
            </div>
    </div>
    </form>
</body>

</html>

</html>