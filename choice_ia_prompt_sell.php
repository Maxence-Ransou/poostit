<?php session_start();
if (isset($_POST)) {
    extract($_POST);
    if (isset($submit)) {
        if ($inteligence_artificielle == "midjourney") {
            header('Location:sell-midjourney.php');
        }
        if ($inteligence_artificielle == "chatgpt") {
            header('Location:sell-chatgpt.php');
        }
        if ($inteligence_artificielle == "dalle") {
            header('Location:sell-dalle.php');
        }
        if ($inteligence_artificielle == "stable") {
            header('Location:sell-stable.php');
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
    <title>Selection de l'ia</title>
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
    <div class="header">
        <form class="form" method="POST">
            <div class="select_form">
                <span class="myarrow"></span>
                <select class="select" name="inteligence_artificielle" id="select ia">
                    <option class="option" value="none">Choose Ai</option>
                    <option class="option" value="midjourney">Midjourney</option>
                    <option class="option" value="chatgpt">ChatGPT</option>
                    <option class="option" value="dalle">DALL·E</option>
                    <option class="option" value="stable">Stable Diffusion</option>
                </select>
            </div>
            <input id="midj_next" class="btn border form" type="submit" name="submit" value="Next">
        </form>
</body>
</div>

</html>