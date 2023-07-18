<?php
session_start();
include("database.php");
require_once('vendor/stripe/stripe-php/init.php');
if (!isset($_SESSION['user_id'])) {
    $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit();
} else {
    $uuid = $_SESSION['user_id'];
    $verify_user = $db->prepare("SELECT * FROM user WHERE UUID = :uuid");
    $verify_user->execute([
        'uuid' => $uuid
    ]);
    if ($verify_user->rowCount() > 0) {
        $userData = $verify_user->fetch();
        $email = $userData['email'];
        $nom = $userData['nom'];
        $prenom = $userData['prenom'];
        $uuid = $userData['UUID'];
    } else {
        exit("Erreur: Utilisateur introuvable");
    }
}
if (isset($_POST['formsend'])) {
    extract($_POST);

    if (!empty($title) && !empty($description) && !empty($prompt) && !empty($prompt_expl) && !empty($prompt_exemple)) {
        $images = $_FILES['image']['name']; // Récupère les noms des images
        $image_array = json_encode($images);
        $minFiles = 5;
        $maxFiles = 5;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $fileCount = count($images);

            if ($fileCount < $minFiles || $fileCount > $maxFiles) {
                ?>
                <div class="notif">
                    <h3>Please select
                        <?php echo $maxFiles ?> files
                    </h3>
                </div>
                <?php
            } else {
                $send = $db->prepare("INSERT INTO midjourney (email, nom, prenom, title, description, prompt, prompt_expl, image, price, id) VALUES (:email, :nom, :prenom, :title, :description, :prompt, :prompt_expl, :image, :price, uuid())");
                $send->execute([
                    'email' => $email,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'title' => $title,
                    'description' => $description,
                    'prompt' => $prompt,
                    'prompt_expl' => $prompt_expl,
                    'image' => $image_array,
                    'price' => $price,
                ]);

                if (isset($_FILES['image']['name']) && is_array($_FILES['image']['name'])) {
                    $file_count = count($_FILES['image']['name']); // Compte le nombre d'images envoyées

                    for ($i = 0; $i < $file_count; $i++) {
                        $file_name = $_FILES['image']['name'][$i];
                        $file_tmp = $_FILES['image']['tmp_name'][$i];
                        $file_path = "image/" . $file_name;

                        if (move_uploaded_file($file_tmp, $file_path)) {
                            // L'image a été enregistrée avec succès
                        }
                    }
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
    <title>Vends ton prompt Gratuitement</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="sell.css">
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
    <div class="header_sell">
        <h2 class="slogan sell" id="midjourney">Informations du Prompt</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="rank">
                <label for="title">Title</label>
                <input type="text" name="title" placeholder="High Quality Object Renders">
            </div>
            <div class="rank">
                <label for="description">Description</label>
                <textarea type="text" name="description" placeholder="Create High Quality Object..."></textarea>
            </div>
            <div class="rank">
                <label for="prompt">Prompt</label>
                <input type="text" name="prompt" placeholder="Generate a 3d render of [object], 4k...">
            </div>
            <div class="rank">
                <label for="prompt_exemple">Prompt Exemple</label>
                <input type="text" name="prompt_exemple" placeholder="Generate a 3d render of pen, 4k...">
            </div>
            <div class="rank">
                <label for="prompt_expl">Explication prompt</label>
                <input type="text" name="prompt_expl" placeholder="Replace [object] with the object of your choice"
                    required>
            </div>
            <div class="preview_container" id="previewContainer"
                style="display:none align-items:center; justify-content: center;"></div>

            <div class="drag_drop_container">
                <input type="file" name="image[]" id="files" accept="image/*" onchange="previewImages()" multiple
                    required>
                <div class="drag_drop_element">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-drag-drop" width="50" height="50"
                        viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M19 11v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path>
                        <path d="M13 13l9 3l-4 2l-2 4l-3 -9"></path>
                        <path d="M3 3l0 .01"></path>
                        <path d="M7 3l0 .01"></path>
                        <path d="M11 3l0 .01"></path>
                        <path d="M15 3l0 .01"></path>
                        <path d="M3 7l0 .01"></path>
                        <path d="M3 11l0 .01"></path>
                        <path d="M3 15l0 .01"></path>
                    </svg>
                    <label class="label_drag_drop" for="files" maxFiles=5>Import 5 files</label>
                    <p class="p_drag_drop">or Drag and Drop</p>
                </div>
            </div>
            <div class="bottom_form">
                <select class="select" name="price" id="select ia">
                    <option value="1.99">1.99$</option>
                    <option value="2.99">2.99$</option>
                    <option value="3.99">3.99$</option>
                    <option value="4.99">4.99$</option>
                    <option value="5.99">5.99$</option>
                    <option value="6.99">6.99$</option>
                    <option value="7.99">7.99$</option>
                    <option value="8.99">8.99$</option>
                    <option value="9.99">9.99$</option>
                </select>
                <input class="btn border form" name="formsend" type="submit" value="Envoyer">
            </div>
    </div>
    </form>
    <script src="previewImage.js"></script>
</body>

</html>