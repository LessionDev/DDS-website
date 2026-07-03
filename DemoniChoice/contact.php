<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DemoniChoice</title>
        <link rel="stylesheet" href="../style/demonichoice/contact.css" > 
        <link rel="stylesheet" href="../style/demonichoice/style.css" > 
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
                                        <li><a href="blog.php">Blog</a></li>
                    <li><a href="https://minecraft.fandom.com/fr/wiki/Minecraft_Wiki">Wiki</a></li>                            
                    <li><a href="shop.php">Shop</a></li>                          
                                </ul>
                                <ul>
                                        <?php if (!isset($_SESSION["user_id"])): ?>
                        <li><a class="btn" href="../login.php?cameFrom=/DemoniChoice/contact.php">Connect <i class='bx bx-chevron-right i' ></i> </a></li>          
                    <?php else: ?>
                        <li><a href="../dashboard.php"><?php echo htmlspecialchars($_SESSION["username"]); ?></a></li>
                        <li><a class="btn o" href="../logout.php?cameFrom=/DemoniChoice/contact.php">Logout<i class='bx bx-log-out'></i> </a></li>
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
                        <li><a href="blog.php">Blog</a></li>
                        <li><a href="https://minecraft.fandom.com/fr/wiki/Minecraft_Wiki">Wiki</a></li>                            
                        <li><a href="shop.php">Shop</a></li>
                    </ul>
                                        <ul>
                                                <?php if (!isset($_SESSION["user_id"])): ?>
                            <li><a class="btn connect-btn" href="../login.php?cameFrom=/DemoniChoice/contact.php">Connect <i class='bx bx-chevron-right i' ></i> </a></li>
                        <?php else: ?>
                            <li><a href="../dashboard.php"><?php echo htmlspecialchars($_SESSION["username"]); ?></a></li>
                            <li><a class="btn" href="../logout.php?cameFrom=/DemoniChoice/contact.php">Logout <i class='bx bx-log-out o' ></i> </a></li>
                        <?php endif; ?>
                                        </ul>
                                </div>
            </div>
        </nav>
        <script src="../scripts/nav.js"></script>
    </body>
    <footer>
    </footer>
</html>
