<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "config.php";
$id = $_GET['id'];
$sql = "SELECT * FROM posts WHERE author_id = '$id'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DemoniChoice</title>
        <link rel="stylesheet" href="style/blog.css" > 
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">    
        <!-- manifest -->
        <link rel="manifest" href="site.webmanifest">
        <!-- icones -->
        <link rel="icon" type="image/png" sizes="32x32" href="style/res/Logo32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../style/res/Logo16.png">
        <link rel="shortcut icon" href="style/res/Logo.ico">
        <!-- fallback favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="style/res/Logo.png">
        <!-- iOS -->
        <link rel="apple-touch-icon" sizes="180x180" href="style/res/Logo180.png">
        <link rel="apple-touch-icon" sizes="167x167" href="style/res/Logo167.png">
        <link rel="apple-touch-icon" sizes="152x152" href="style/res/Logo152.png">
    </head>
    <body>
        <?php
            while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="container">
            <div class="title">
                <h2 class="articleLink"> <?php echo $row['title'];?> </h2>
        	</div>
        </div>
        <?php
        }
        ?>     
    </body>
    <footer>
    </footer>
</html>