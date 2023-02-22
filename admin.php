<!doctype html>
<html lang="fr">

<head>
    <?php include_once('headmeta.php'); ?>
    <title>ReSoC - Administration</title>
</head>

<body>

    <?php include_once('header.php'); ?>
    <?php include('connexion.php'); ?>
    <div class="alert"></div>

    <div id="wrapper" class='admin'>
        <aside>
            <h2>Mots-clés</h2>
            <?php
            /*Etape 2 : trouver tous les mots clés*/
            $laQuestionEnSql = "SELECT * FROM `tags` LIMIT 50";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            //Vérification
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
                exit();
            }

            while ($tag = $lesInformations->fetch_assoc()) {
                ?>
                <article>
                    <h3>
                        <?php echo $tag['label'] ?>
                    </h3>
                    <p>
                        <?php echo $tag['id'] ?>
                    </p>
                    <nav>
                        <a href="tags.php?tag_id=<?php echo $tag['id'] ?>">Liste des posts</a>
                    </nav>
                </article>
            <?php } ?>
        </aside>

        <main>
            <h2>Utilisatrices</h2>
            <?php
            //Etape 4 : trouver tous les mots clés
            

            $laQuestionEnSql = "SELECT * FROM `users` LIMIT 50";
            $lesInformations = $mysqli->query($laQuestionEnSql);

            // Vérification
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
                exit();
            }

            //Etape 5 : @todo : Afficher les utilisatrices en s'inspirant de ce qui a été fait dans news.php
            
            while ($tag = $lesInformations->fetch_assoc()) {

                ?>
                <article>
                    <h3>
                        <?php echo $tag['alias'] ?>
                    </h3>
                    <p>
                        <?php echo $tag['id'] ?>
                    </p>
                    <nav>
                        <a href="wall.php?user_id=<?php echo $tag['id'] ?>">Mur</a>
                        | <a href="feed.php?user_id=<?php echo $tag['id'] ?>">Flux</a>
                        | <a href="settings.php?user_id=<?php echo $tag['id'] ?>">Paramètres</a>
                        | <a href="followers.php?user_id=<?php echo $tag['id'] ?>">Suiveurs</a>
                        | <a href="subscriptions.php?user_id=<?php echo $tag['id'] ?>">Abonnements</a>
                    </nav>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>