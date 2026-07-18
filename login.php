<?php
session_start();
require "api_client.php";
require "safe_redirect.php";

$location = safe_redirect_target($_GET['cameFrom'] ?? null);
$classeMatch = 'Taken';

if ($_POST) {
    $result = api_request("login.php", "POST", [
        "username" => $_POST["username"] ?? "",
        "password" => $_POST["password"] ?? "",
    ]);

    if (!empty($result["success"])) {
        session_regenerate_id(true);

        $_SESSION["user_id"] = $result["id"];
        $_SESSION["username"] = $result["username"];
        $_SESSION["user_status"] = $result["status"];

        header("Location: $location");
        exit;

    } elseif (($result["message"] ?? "") === "Too many attempts, try again later.") {
        $classeMatch = 'Taken active locked';
    } else {
        $classeMatch = 'Taken active';
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
                <h3>Log In</h3>
            </div>
            <form class="input-container" method="post">
                <input type="text" placeholder="username" name="username" required> 
                <input name="password" type="password" placeholder="password" required>   
                <div class="bottom">
                    <li class="<?php echo $classeMatch; ?>">Credentials don´t match our records.</li>
                    <button class="submit" type="submit">LOG IN</button> 
                </div>  
                <div class="link">
                    Don't Have An Account?
                    <a href="register.php?cameFrom=<?= htmlspecialchars($location) ?>">Sign up !</a>  
                </div>
            </form>
        </div>
    </body>
    <footer>
    </footer>
</html>
