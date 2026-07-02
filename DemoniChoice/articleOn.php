<?php
session_start();
include "../config.php";

$post_id = intval($_GET['post_id']);
$sql = "SELECT * FROM posts WHERE id = $post_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DemoniChoice</title>
        <link rel="stylesheet" href="../style/demonichoice/style.css" > 
        <link rel="stylesheet" href="../style/demonichoice/blog.css" > 
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">    
        <!-- manifest -->
        <link rel="manifest" href="../site.webmanifest">
        <!-- icones -->
        <link rel="icon" type="image/png" sizes="32x32" href="../style/res/demonichoice/Logo32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../style/res/demonichoice/Logo16.png">
        <link rel="shortcut icon" href="../style/res/demonichoice/Logo.ico">
        <!-- fallback favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="../style/res/demonichoice/Logo.png">
        <!-- iOS -->
        <link rel="apple-touch-icon" sizes="180x180" href="../style/res/demonichoice/Logo180.png">
        <link rel="apple-touch-icon" sizes="167x167" href="../style/res/demonichoice/Logo167.png">
        <link rel="apple-touch-icon" sizes="152x152" href="../style/res/demonichoice/Logo152.png">
    </head>
    <body>
        <nav>
        	<div class="navbar">
			<div class="navbarMenu">
				<img class="logo" src="../style/res/demonichoice/Logo.png">
				<ul>
					<li><a href="home.php">Home</a></li>
                    <li><a href="https://minecraft.fandom.com/fr/wiki/Minecraft_Wiki">Wiki</a></li>                            
                    <li><a href="shop.php">Shop</a></li>
                	<li><a href="contact.php">Contact Us</a></li>                          
				</ul>
				<ul>
					<?php if (!isset($_SESSION["user_id"])): ?>
                        <li><a class="btn" href="../login.php?cameFrom=DemoniChoice/articleOn.php?post_id=<?= $post_id ?>">Connect <i class='bx bx-chevron-right i' ></i> </a></li>          
                    <?php else: ?>
                        <li><a href="../dashboard.php"><?php echo $_SESSION["username"]; ?></a></li>
                        <li><a class="btn o" href="../logout.php?cameFrom=DemoniChoice/articleOn.php?post_id=<?= $post_id ?>">Logout<i class='bx bx-log-out'></i> </a></li>
                    <?php endif; ?>
				</ul>
			</div>
			<div class="icon">
				<i class="bx bx-menu openBtn Btn"></i>
			</div>
            <div class="logoDiv">
                <img class="logo" src="../style/res/demonichoice/Logo.png">
            </div>
            <div class="icon">
            </div>
            <div class="menu">
                <div class="topMenu">
                	<div class="icon">
						<i class="bx bx-x closeBtn Btn"></i>
					</div>
                    <div class="logoDiv">
                        <img class="logo" src="../style/res/demonichoice/Logo.png">
                    </div>
                    <div class="icon">
                    </div>
            	</div>
                	<ul>
                    	<li><a href="home.php">Home</a></li>
                        <li><a href="https://minecraft.fandom.com/fr/wiki/Minecraft_Wiki">Wiki</a></li>                            
                        <li><a href="shop.php">Shop</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </ul>
					<ul>
						<?php if (!isset($_SESSION["user_id"])): ?>
                            <li><a class="btn connect-btn" href="../login.php?cameFrom=DemoniChoice/articleOn.php?post_id=<?= $post_id ?>">Connect <i class='bx bx-chevron-right i' ></i> </a></li>
                        <?php else: ?>
                            <li><a href="../dashboard.php"><?php echo $_SESSION["username"]; ?></a></li>
                            <li><a class="btn" href="../logout.php?cameFrom=DemoniChoice/articleOn.php?post_id=<?= $post_id ?>">Logout <i class='bx bx-log-out o' ></i> </a></li>
                        <?php endif; ?>
					</ul>
				</div>
            </div>
        </nav>
        <div class="backBlog">
            <a class="blogBack" href="blog.php"><i class='bx bx-left-arrow-alt'></i>Back to the blog section</a>
        </div>
        <div class="displayArticle">
        	<img class="articleImg" src="../style/res/blog/posts/demonichoice/<?php echo $row['image']; ?>">
            <div class="articleText">
        		<h1><?php echo $row['title']; ?></h1>
        		<p><?php echo $row['content']; ?></p>  
            </div>
        </div>
        <div class="buttons">
        <?php

        $prev_id = $post_id - 1;
        $check_prev = mysqli_query($conn, "SELECT id FROM posts WHERE id = $prev_id");

        if (mysqli_num_rows($check_prev) > 0) {
            echo '<a class="previous" href="articleOn.php?post_id=' . $prev_id . '" class="previous"><i class="bx bx-arrow-back"></i>Previous article</a>';
        }

        $next_id = $post_id + 1;
        $check_next = mysqli_query($conn, "SELECT id FROM posts WHERE id = $next_id");

        if (mysqli_num_rows($check_next) > 0) {
            echo '<a class="next" href="articleOn.php?post_id=' . $next_id . '" class="next">Next article<i class="bx bx-right-arrow-alt"></i></a>';
        }

?>
</div>
        <script src="../scripts/nav.js"></script>
    </body>
    <footer>
    </footer>
</html>
