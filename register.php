<?php
require "config.php";

$classeTaken = 'Taken';
$classeRegistered = "notRegistered";

if ($_POST) {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        die("Please fill the necessary informations.");
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
    	$classeTaken = 'Taken active';
	} else {
    	$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    	$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
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
        <!-- manifest -->
        <link rel="manifest" href="site.webmanifest">
        <!-- icones -->
        <link rel="icon" type="image/png" sizes="32x32" href="style/res/Logo32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="style/res/Logo16.png">
        <link rel="shortcut icon" href="style/res/Logo.ico">
        <!-- fallback favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="style/res/Logo.png">
        <!-- iOS -->
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
                    <a href="login.php"> Log In !</a>
                </div>
            </form>
        </div>
    </body>
    <footer>
    </footer>
</html>