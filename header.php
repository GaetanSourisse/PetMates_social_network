<header>
    <img src="resoc.jpg" alt="Logo de notre réseau social" />
    <nav id="menu">
        <a href="news.php">Actualités</a>
        <a href="feed.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Flux</a>
    </nav>    
    <nav id="tags">
        <a href="#">Rechercher un tag</a>
        <ul>
            <?php
            //connexion à la base de données
            $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
            //verification
            if ($mysqli->connect_errno) {
                echo ("Échec de la connexion : " . $mysqli->connect_error);
            exit();
            }
            //création de la requête pour aller chercher tous les tags disponibles
            $questionTagsSql = "SELECT * FROM tags" ;
            $infosTags = $mysqli->query($questionTagsSql);
            while ($tagInfo = $infosTags->fetch_assoc()) {
                $tagLabel = $tagInfo['label'];
                $tagId = $tagInfo['id'];
                ?>
                <li class="deroulant">
                    <a href="tags.php?tag_id=<?php echo $tagId ?>"><?php echo $tagLabel ?></a>
                </li>
            <?php 
            }
            ?>
        </ul>

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