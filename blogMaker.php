<?php
session_start();
require "api_client.php";

$blogs = api_request("getValues.php", "POST", [
        "value" => "blogDestination" ?? "",
        "table" => "posts" ?? "",
        "extra" => "isEnum" ?? ""
    ]);

if ($blogs["success"]) {
    $blogs = $blogs["values"];
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SESSION['user_status'] !== "author" && $_SESSION['user_status'] !== "admin") {
    header("Location: index.php");
    exit;
}

$author_id = $_SESSION['user_id'];

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $destinationRaw = trim($_POST['destination'] ?? '');

    if (empty($title) || empty($content) || empty($destinationRaw)) {
        die("Please fill the necessary informations.");
    }

    if (!preg_match('/^[a-zA-Z0-9_-]{1,50}$/', $destinationRaw)) {
        die("Invalid destination.");
    }
    $destination = $destinationRaw;

    $imageName = null;

    if (!empty($_FILES['image']['name'])) {
        $tmpPath = $_FILES['image']['tmp_name'];
        $originalName = $_FILES['image']['name'];
        $allowedMime = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
        ];

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmpPath);

        if (!isset($allowedMime[$mime])) {
            die("Only image files (jpg, png, gif, webp) are allowed.");
        }

        $imageName = bin2hex(random_bytes(16)) . '.' . $allowedMime[$mime];

        $destinationDir = __DIR__ . '/style/res/blog/posts/' . $destination;
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $imagePath = $destinationDir . '/' . $imageName;

        if (!move_uploaded_file($tmpPath, $imagePath)) {
            die("Upload failed");
        }
    }
    
    $result = api_request("posts_create.php", "POST", [
        "title" => $title,
        "content" => $content,
        "destination" => $destination,
        "author_id" => $author_id,
        "image" => $imageName,
    ], true);
    
    if (!empty($result["success"])) {
        $message = "Article posted successfully";
    } else {
        error_log("blogMaker: API insert failed: " . json_encode($result));
        $message = "Something went wrong, please try again.";
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
        <?php if ($message): ?><p><?= htmlspecialchars($message) ?></p><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="The title of your article">
            <textarea name="content" placeholder="Type your article here"></textarea>
            <label for="blogDestination">Choose the blog to posts: </label>
            <select name="destination" id="blogDestination">
                <?php foreach($blogs as $blog): ?>
                    <option value="<?= htmlspecialchars($blog) ?>">
                        <?= htmlspecialchars($blog) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="file" name="image">
            <input type="submit" name="submit" value="submit your article">
        </form>   
        <a href="dashboard.php">Back to the dashboard</a>
    </body>
    <footer>
    </footer>
</html>
