<?php
session_start();
require "api_client.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$result = api_request("posts.php", "GET", ["author_id" => $_SESSION['user_id']]);
$posts = $result["posts"] ?? [];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DemoniChoice</title>
        <link rel="stylesheet" href="style/blog.css" > 
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">    
        <link rel="manifest" href="site.webmanifest">
        <link rel="icon" type="image/png" sizes="32x32" href="style/res/Logo32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../style/res/Logo16.png">
        <link rel="shortcut icon" href="style/res/Logo.ico">
        <link rel="icon" type="image/png" sizes="32x32" href="style/res/Logo.png">
        <link rel="apple-touch-icon" sizes="180x180" href="style/res/Logo180.png">
        <link rel="apple-touch-icon" sizes="167x167" href="style/res/Logo167.png">
        <link rel="apple-touch-icon" sizes="152x152" href="style/res/Logo152.png">
    </head>
    <body>
        <?php foreach ($posts as $row): ?>
            <div class="container">
                <div class="title">
                    <h2 class="articleLink"><?php echo htmlspecialchars($row['title']); ?></h2>
                </div>
            </div>
        <?php endforeach; ?>     
    </body>
    <footer>
    </footer>
</html>
