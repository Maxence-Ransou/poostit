<?php
include('database.php');
session_start();
require_once('vendor/stripe/stripe-php/init.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PromptVerse</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>

<body>
    <div class="navbar">
        <h1 class="title" onclick='location.href="index.php"'>PromptVerse</h1>
        <div class="auth">
            <?php if (!isset($_SESSION['user_id'])) { ?>
                <p onclick="location.href='login.php'" class="login navbar_home">Login</p>

                <input onclick="location.href='register.php'" class="btn navbar_home" type="button" value="Sign up">
            <?php } else {
                $stripe = new \Stripe\StripeClient('sk_test_51NGQUkCJYk0zeR2hjfGdeVOaVEYHj2hwApowtPOEQBFjHcafiTKPoKGB5TxvhmTv8zXHcBTKB7QpmWI1WPeyuDNC00ZEPFRp58');
                $y = $db->prepare('SELECT id_account FROM user WHERE UUID = :UUID');
                $y->execute([
                    'UUID' => $_SESSION['user_id']
                ]);
                $id_account = $y->fetch();
                try {
                    $account = $stripe->accounts->retrieve($id_account['id_account']);
                    // Utilisez les données du compte client récupéré
                    echo 'ID du compte client : ' . $account->id;
                    $link = $stripe->accountLinks->create([
                        'account' => $account->id,
                        'refresh_url' => 'https://poostit.xyz/index.php',
                        'return_url' => 'https://poostit.xyz/index.php',
                        'type' => 'account_onboarding',
                      ]);
                      echo $link->url;
                    // et ainsi de suite...
                } catch (\Stripe\Exception\ApiErrorException $e) {
                    // Gérez les erreurs d'API Stripe
                    echo 'Une erreur s\'est produite : ' . $e->getMessage();
                }
                ?>
                <p onclick="location.href='logout.php'" class="logout navbar_home">Logout</p>
            <?php } ?>  
        </div>
    </div>
    <div class="header">
        <div class="left header">
            <h2 class="slogan header_home">Develop all the capabilities of AI with<span> PromptVerse</span></h2>
            <div class="btn_container">
                <input onclick="location.href='prompt-buy.php'" id="buy_home" class="btn border-radius header_home"
                    type="button" value="Buy">
                <input onclick="location.href='choice_ia_prompt_sell.php'" id="sell_home"
                    class="btn border-radius header_home" type="button" value="Sell">
            </div>
        </div>
        <div class="right header">
            <img class="banner header_home" src="image/banner_infinite.png" alt="banner of Ai">
        </div>
    </div>
</body>

</html>