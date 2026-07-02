<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit; // AVANT : pas de exit -> la page continuait de s'afficher pour un visiteur non connecté.
}

$id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DemoniChoice</title>
        <link rel="stylesheet" href="style/dashboard.css" > 
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">    
        <link rel="manifest" href="/site.webmanifest">
        <link rel="icon" type="image/png" sizes="32x32" href="/Logo32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/Logo16.png">
        <link rel="shortcut icon" href="style/res/Logo.ico">
        <link rel="icon" type="image/png" sizes="32x32" href="style/res/Logo.png">
        <link rel="apple-touch-icon" sizes="180x180" href="style/res/Logo180.png">
        <link rel="apple-touch-icon" sizes="167x167" href="style/res/Logo167.png">
        <link rel="apple-touch-icon" sizes="152x152" href="style/res/Logo152.png">
    </head>
    <body>
        <h1>
             <!-- AVANT : le pseudo était affiché sans échappement -> XSS stocké
                  possible via un pseudo malveillant créé à l'inscription. -->
             Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>
        </h1>             
        <?php if($_SESSION['user_status'] == 'admin' || $_SESSION['user_status'] == 'author'): ?>
    <a href="blogMaker.php">Need to write an article ?</a>    
<?php endif; ?>
        <?php if($_SESSION['user_status'] == 'admin' || $_SESSION['user_status'] == 'author'): ?>
    <a href="blogViewer.php">Your list of articles</a>    
<?php endif; ?>

    </body>
    <footer>
    </footer>
</html>
