<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PromptVerse</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="buy.css">
</head>

<body>
    <div class="navbar">
        <h1 class="title" onclick='location.href="index.php"'>PromptVerse</h1>
        <div class="auth">
            <?php if (!isset($_SESSION['user_id'])) { ?>
                <p onclick="location.href='login.php'" class="login navbar_home">Login</p>
                <input onclick="location.href='register.php'" class="btn navbar_home" type="button" value="Sign up">
            <?php } else { ?>
                <p onclick="location.href='logout.php'" class="logout navbar_home">Logout</p>
            <?php } ?>
        </div>
    </div>
    <?php
    include("database.php");
    $select = $db->prepare("SELECT * FROM midjourney WHERE verification = :bool");
    $select->execute([
        'bool' => "false"
    ]);
    if ($select->rowCount() > 0) {
        ?>
        <div class="container_post">

            <?php
            while ($result = $select->fetch()) {
                $liste_fichiers = $result['image'];
                $link_image = explode(',', $liste_fichiers);

                $link_image = array_map(function ($item) {
                    return str_replace(['[', ']', ';', '"'], '', $item);
                }, $link_image);
                ?>
                <div onclick="location.href='prompt.php?id=<?php echo $result['id'] ?>'" class="post_element">
                    <img id="img_post" src="image\<?php echo $link_image[0]; ?>" alt="Image of <?php echo $result['title'] ?>">
                    <div class="post_info">
                        <h4>
                            <?php echo $result['title'] ?>
                        </h4>
                        <p class="price">
                            <?php echo $result['price'] ?>$
                        </p>
                    </div>
                </div>
                <?php
            }
    }
    ?>
    </div>

</body>

</html>