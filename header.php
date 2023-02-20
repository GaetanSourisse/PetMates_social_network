<header>
    <img src="resoc.jpg" alt="Logo de notre réseau social" />
    <nav id="menu">
        <a href="news.php">Actualités</a>
        <a href="feed.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Flux</a>
        <a href="tags.php">Mots-clés</a>
    </nav>
    <nav id="user">
        <a href="#">Profil</a>
        <ul>
            <li><a href="settings.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Paramètres</a></li>
            <li><a href="wall.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Mon profil</a></li>
            <li>
                <form action="login.php" method="post"><button name="déconnexion">Se déconnecter</button></form>
            </li>
        </ul>

    </nav>
    <?php
    if (isset($_POST['déconnexion'])) {
       session_unset();
    }
    ;

    ?>
</header>