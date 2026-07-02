<?php
session_start();
include "config.php";

$author = $_GET['author'];

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

if ($_SESSION['user_status'] !== "author" && $_SESSION['user_status'] !== "admin") {
    header("Location: index.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];

    $content = $_POST['content'];
    
    $destination = $_POST['destination'];

    $author_id = $author;

    $name = $_FILES['image']['name'];

    $temp_location = $_FILES['image']['tmp_name'];

    $our_location = "../style/res/blog/posts/<?= $destination ?>/";

    if (!empty($name)) {

        move_uploaded_file($temp_location, $our_location . $name);

    }

    if (empty($title) || empty($content)) {

        die("Please fill the necessary informations.");

    }

    $sql = "INSERT INTO posts (title, content, author_id, image, blogDestination)

            VALUES ('$title', '$content', '$author_id', '$name', '$destination')";

    $result = mysqli_query($conn, $sql);

    if ($result) {     
        $message = "Article posted successfully";
        
    } else {                   
        $message = "Error: " . mysqli_error($conn);
        
    }
}
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
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="The title of your article">
            <textarea name="content" placeholder="Type your article here"></textarea>
            <input type="text" name="destination" placeholder="The name of the Blog you want to post your article">
            <input type="file" name="image">
            <input type="submit" name="submit" value="submit your article">
        </form>   
        <a href="dashboard.php">Back to the dashboard</a>
    </body>
    <footer>
    </footer>
</html>