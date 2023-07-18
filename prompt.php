<?php
include('database.php');
require_once('vendor/stripe/stripe-php/init.php');
$stripe = new \Stripe\StripeClient('sk_test_51NGQUkCJYk0zeR2hjfGdeVOaVEYHj2hwApowtPOEQBFjHcafiTKPoKGB5TxvhmTv8zXHcBTKB7QpmWI1WPeyuDNC00ZEPFRp58');
\Stripe\Stripe::setApiKey('sk_test_51NGQUkCJYk0zeR2hjfGdeVOaVEYHj2hwApowtPOEQBFjHcafiTKPoKGB5TxvhmTv8zXHcBTKB7QpmWI1WPeyuDNC00ZEPFRp58');
session_start();
$query = null;
$uuid = $_SESSION['user_id'];
$query_code = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

if ($query_code) {
    $query = explode('id=', $query_code)[1];
}

if (isset($query)) {
    $q = $db->prepare('SELECT * FROM midjourney WHERE id = :id');
    $q->execute([
        'id' => $query
    ]);
    $PromptDetails = $q->fetch();
    if ($PromptDetails !== false) {
        $email = $PromptDetails['email'];
        $img = $PromptDetails['image'];
        $title = $PromptDetails['title'];
        $price = $PromptDetails['price'];
        $desc = $PromptDetails['description'];
        $nom = $PromptDetails['nom'];
        $prenom = $PromptDetails['prenom'];
        $bio = $PromptDetails['bio'];
    } else {
        echo ("Erreur aucun fichier trouvé");
    }
    if ($PromptDetails !== false) {
        $product = $stripe->products->create([
            'name' => $PromptDetails['title'],
        ]);

        $price = $stripe->prices->create([
            'product' => $product->id,
            'unit_amount' => intval($PromptDetails['price'] * 100),
            'currency' => 'usd',
        ]);
        $session = $stripe->checkout->sessions->create([
            'success_url' => 'https://votre-site.com/success',
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $price->id,
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            // Ajouter les métadonnées à la session de paiement
            'metadata' => [
                'user_id' => $uuid,
                'article_id' => $product->id,
            ]
            
        ]);
        $session_id = $session->id;
        $urllong = $session->url;
        function raccourcirLien($url)
        {
            $api_url = "http://tinyurl.com/api-create.php?url=" . urlencode($url);
            $short_url = file_get_contents($api_url);

            return $short_url;
        }

        $sdk = $db->prepare("UPDATE `midjourney` SET `product_id` = :product_id WHERE id = :prompt_id");
        $sdk->execute([
            'product_id' => $product->id,
            'prompt_id' => $PromptDetails['id'],
        ]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title ?> PromptVerse
    </title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="prompt.css">
</head>

<body>
    <div class="navbar">
        <h1 class="title" onclick='location.href="index.php"'>PromptVerse</h1>
        <div class="auth">
            <?php if (!isset($_SESSION['user_id'])) {
                $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
                header("Location: login.php");
            } else { ?>
                <p onclick="location.href='logout.php'" class="logout navbar_home">Logout</p>
            <?php } ?>
        </div>
    </div>
    <div class="header">
        <div class="prompt_container">
            <div class="slider">
                <figure>
                    <?php
                    if (isset($result)) {
                        $liste_fichiers = $PromptDetails['image'];
                        $link_image = explode(',', $liste_fichiers);

                        $link_image = array_map(function ($item) {
                            return str_replace(['[', ']', ';', '"'], '', $item);
                        }, $link_image);
                        $number_image = count($link_image);
                        while ($number_image != 0) {
                            ?>
                            <img class="img number<?php echo $number_image ?>"
                                src="image\<?php echo $link_image[$number_image - 1] ?>" alt="<?php echo $title ?>">
                            <?php
                            $number_image--;
                        }
                    } ?>
                </figure>
            </div>
            <div class="info_prompt">
                <div class="info">
                    <h2 class="title_prompt">
                        <?php echo $title ?>
                    </h2>
                    <h3 class="prenom">
                        @
                        <?php echo $prenom ?>
                    </h3>
                </div>
            </div>
            <div class="desc">
                <h3>Description</h3>
                <p>
                    <?php echo $desc ?>
                </p>
            </div>
            <div class="buy_info">
                <h3 class="price">
                    <?php echo $price ?>$
                </h3>
                <a href="<?php echo $url_product ?>" checkout-button" class="buy" type="button">Get Prompt</a>

            </div>
            <p class="droit">However, prior access to Midjourney is necessary to make use of this prompt.</p>
        </div>
        <div class="popup_contaier">
            <div class="info_popup">
                <div class="info_user">
                    <h4>
                        <?php echo $prenom ?>
                        <?php echo $bio ?>
                    </h4>
                </div>
                <div class="info_post">

                </div>
            </div>
        </div>
    </div>
</body>

</html>