<?php
require "api_client.php";
require "safe_redirect.php";

$location = safe_redirect_target($_GET['cameFrom'] ?? null);
$classeTaken = 'Taken';
$classeRegistered = "notRegistered";
$errorMessage = "";

if ($_POST) {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $result = api_request("register.php", "POST", [
        "username" => $username,
        "password" => $password,
    ]);

    if (!empty($result["success"])) {
        $classeRegistered = "registered";
    } elseif (($result["message"] ?? "") === "username_taken") {
        $classeTaken = 'Taken active';
    } elseif (($result["message"] ?? "") === "invalid_username") {
        $errorMessage = "The username must contain 9 to 20 characters (letters, numbers, _ or -).";
    } elseif (($result["message"] ?? "") === "weak_password") {
        $errorMessage = "The password must contain at least 8 characters.";
    } else {
        $errorMessage = "An error occured, try later.";
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
                    <?php if ($errorMessage): ?><li class="Taken active"><?= htmlspecialchars($errorMessage) ?></li><?php endif; ?>
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
