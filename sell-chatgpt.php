<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prompt for Chat gpt</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="sell.css">
</head>

<body>
    <div class="navbar">
        <h1 class="title" onclick='location.href="index.php"'>PromptVerse</h1>
        <div class="auth">
            <p class="login navbar_home">Login</p>
            <input class="btn navbar_home" type="button" value="Sign up">
        </div>
    </div>
    <div class="header_sell">
        <h2 class="slogan sell" id="midjourney">Informations du Prompt</h2>
        <form method="post">
            <div class="rank">
                <label for="title">Title</label>
                <input type="text" name="title" placeholder="High Quality Object Renders">
            </div>
            <div class="rank">
                <label for="description">Description</label>
                <textarea type="text" placeholder="Create High Quality Object..."></textarea>
            </div>
            <div class="rank">
                <label for="description">Prompt</label>
                <input type="text" placeholder="Generate a 3d render of [object], 4k...">
            </div>
            <div class="rank">
                <label for="description">Prompt Exemple</label>
                <input type="text" placeholder="Generate a 3d render of pen, 4k...">
            </div>
            <div class="rank">
                <label for="description">Explication prompt</label>
                <input type="text" placeholder="Replace [object] with the object of your choice">
            </div>
            <div class="bottom_form">
                <select class="select" name="inteligence_artificielle" id="select ia">
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
                <input class="btn border form" type="submit" value="Envoyer">
            </div>
    </div>
    </form>
</body>

</html>