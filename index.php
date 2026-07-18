<?php
session_start();
?>

<!DOCTYPE html>
<html>
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DemoniChoice</title>
        <link rel="stylesheet" href="style/style.css" > 
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
        <!-- manifest -->
        <link rel="manifest" href="/site.webmanifest">
        <!-- icones -->
        <link rel="icon" type="image/png" sizes="32x32" href="/Logo32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/Logo16.png">
        <link rel="shortcut icon" href="style/res/Logo.ico">
        <!-- fallback favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="style/res/Logo.png">
        <!-- iOS -->
        <link rel="apple-touch-icon" sizes="180x180" href="style/res/Logo180.png">
        <link rel="apple-touch-icon" sizes="167x167" href="style/res/Logo167.png">
        <link rel="apple-touch-icon" sizes="152x152" href="style/res/Logo152.png">
    </head>
    <body>
        <nav>
                <div class="navbar">
                        <div class="navbarMenu">
                                <img class="logo" src="style/res/Logo.png">
                                <ul>
                                        <li><a href="blog.php">Blog</a></li>
                                        <li><a href="https://minecraft.fandom.com/fr/wiki/Minecraft_Wiki">Wiki</a></li>                            
                                        <li><a href="#">Shop</a></li>
                                        <li><a href="#">Contact Us</a></li>                          
                                </ul>
                                <ul>
                                        <?php if (!isset($_SESSION["user_id"])): ?>
                                                <li><a class="btn" href="login.php">Connect <i class='bx bx-chevron-right i' ></i> </a></li>          
                                        <?php else: ?>
                                                <li><a href="dashboard.php"><?php echo htmlspecialchars($_SESSION["username"]); ?></a></li>
                                                <li><a class="btn o" href="logout.php">Logout<i class='bx bx-log-out'></i> </a></li>
                                        <?php endif; ?>
                                        <li><a class="playBtnNav btn" href="#Download">Play !</a></li>
                                </ul>
                        </div>
                        <div class="icon">
                                <i class="bx bx-menu openBtn Btn"></i>
                        </div>
                        <div class="logoDiv">
                                <img class="logo" src="style/res/Logo.png">
                        </div>
                        <div class="icon">
                        </div>
                        <div class="menu">
                                <div class="topMenu">
                                        <div class="icon">
                                                <i class="bx bx-x closeBtn Btn"></i>
                                        </div>
                                        <div class="logoDiv">
                                                <img class="logo" src="style/res/Logo.png">
                                        </div>
                                        <div class="icon">
                                        </div>
                                </div>
                                <ul>
                                        <li><a href="blog.php">Blog</a></li>
                                        <li><a href="https://minecraft.fandom.com/fr/wiki/Minecraft_Wiki">Wiki</a></li>                            
                                        <li><a href="#">Shop</a></li>
                                        <li><a href="#">Contact Us</a></li>
                                </ul>
                                <ul>
                                        <?php if (!isset($_SESSION["user_id"])): ?>
                                                <li><a class="btn connect-btn" href="login.php">Connect <i class='bx bx-chevron-right i' ></i> </a></li>
                                        <?php else: ?>
                                                <li><a href="dashboard.php"><?php echo htmlspecialchars($_SESSION["username"]); ?></a></li>
                                                <li><a class="btn" href="logout.php">Logout <i class='bx bx-log-out o' ></i> </a></li>
                                        <?php endif; ?>
                                                <li><a class="playBtnNav btn" href="#Download"> Play !</a></li>
                                 </ul>
                        </div>
                </div>
        </nav>
        <div class="HomeDiv">
            <section id="Home">
                <div class="gridContainer">
                    <div class="textFirst">
                        <h2 class="subtitle1" >Join DemoniChoice</h2>
                        <h1 class="sectionTitle">Start a hellish journey !</h1>
                        <p class="subtitle2"> DemoniChoice is a modded minecraft server based around the deal mecanic that allows players to deal with souls and own people.
                        </p>
                        <div class="buttonDiv">
                            <a href="#Download" class="playBtn btn"> <i class='bx bx-play-circle i'></i> Play</a>
                            <a href="#AboutS" class="btn">How it works?</a>
                        </div>
                        </div>
                    <img class="imgFirst" src="style/res/img1.png"/>
                </div>
            </section>
        </div>
        <div class="AboutSDiv">
            <section id="AboutS">
                <div class="tutoContainer">
                    <div class="tutoCard">
                        <h1>Download the Launcher <a href="#Download" class="linkTuto">here</a></h1>            
                        <img src="style/res/step1.gif" class="stepGif"/>
                    </div>
                    <div class="tutoCard">
                        <h1>Open it and let it download everything</h1>
                        <img src="style/res/step2.gif" class="stepGif"/>
                    </div>
                    <div class="tutoCard">
                        <h1>Connect to your account then launch the game !</h1>    
                        <img src="style/res/step3.gif" class="stepGif"/>
                    </div>
                </div>
            </section>
        </div>
        <div class="DownloadDiv">
            <section id="Download">
                <div class="links">
                    <ul>
                        <div class=linkDiv>
                            <li class="textLink"><i class='bx bx-chevrons-right'></i>Download launcher for Windows</li>
                            <a class="downloadLinks" href=#>here.</a>
                        </div>
                        <div class="linkDiv">
                            <li class="textLink"><i class='bx bx-chevrons-right'></i>Download launcher for MacOS</li>
                            <a class="downloadLinks" href=#>here.</a>
                        </div>
                        <div class="noteDiv">
                            <li>Note : you need Java installed on your computer, if you need help contact the</li>
                            <a class="supportLink" href=#>support</a>
                        </div>
                    </ul>
                </div>                                              
            </section>
        </div>
        <script src="scripts/nav.js"></script>
    </body>
    <footer>
    </footer>
</html>
