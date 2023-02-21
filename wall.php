<?php
session_start();
include('forbidenpage.php');
?>
<!doctype html>
<html lang="fr">

<head>
    <?php include_once('headmeta.php'); ?>
    <title>ReSoC - Mur</title>
</head>

<body>

    <?php include_once('header.php'); ?>
    <?php include('connexion.php'); ?>

    <div id="wrapper">
        <?php
        //on récupère le user_id depuis l'url du mur où on se trouve
        $userId = intval($_GET['user_id']);
        ?>
        <aside>

            <?php

            // vérifier si le user loggé est déja abonné au wall visité, requête pour chercher si l'abonnement existe
            $questionSqlIsFollowed = "SELECT * FROM followers WHERE followed_user_id='$userId' AND following_user_id='" . $_SESSION['connected_id'] . "';";
            $infoFollowed = $mysqli->query($questionSqlIsFollowed);

            // si la requête échoue, message échec
            if (!$infoFollowed) {
                echo "échec" . $mysqli->error;
            } else {
                // si la requête réussit, si l'abonnement est déjà présent alors bouton = désabonnement sinon bouton= s'abonner
                if ($infoFollowed->fetch_assoc()) {
                    $valueButton = "désabonnement";
                } else {
                    $valueButton = "s'abonner";
                }
            }
            ;


            // on récupère les infos du user
            $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();

            // ?>
            <img src="user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <?php

                // on stocke le click du bouton
                $enCoursDabonnement = isset($_POST['abonnement']);
                //on prépare les requêtes pour ajouter ou supprimer un abonnement
                $questionSqlNewFollower = "INSERT INTO followers (id, followed_user_id, following_user_id) VALUES (NULL, '$userId', '" . $_SESSION['connected_id'] . "');";
                $deleteFollower = "DELETE FROM followers WHERE followed_user_id ='$userId' AND following_user_id ='" . $_SESSION['connected_id'] . "';";

                //si le bouton "abonné" est cliqué, ajoute l'abonnement à la BD
                if ($enCoursDabonnement && $valueButton == "s'abonner") {
                    $mysqli->query($questionSqlNewFollower);
                    echo "Vous êtes abonné à " . $user['alias'];
                    $valueButton = "désabonnement";
                    // si le bouton "désabonnement" est cliqué, supprime l'abonnement
                } elseif ($enCoursDabonnement && $valueButton == "désabonnement") {
                    $mysqli->query($deleteFollower);
                    echo "Vous êtes désabonné de " . $user['alias'];
                    $valueButton = "s'abonner";
                }
                ;
                ?>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les message de l'utilisatrice :
                    <?php echo $user['alias'] ?>
                    (n°
                    <?php echo $userId ?>)
                </p>
                <?php 
                //si on est sur son propre mur, on ajoute la possibilité d'afficher les abonnements et abonnés
                if ($userId == intval($_SESSION['connected_id'])) {
                    ?>
                    <div class="info-followers">
                        <li><a href="followers.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Mes suiveurs</a></li>
                        <li><a href="subscriptions.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Mes abonnements</a></li>
                    </div>
                <?php 
                }
                ?>
                
                <?php
                if ($userId !== intval($_SESSION['connected_id'])) {
                    ?>
                    <form action="" method="post">
                        <input type='submit' name='abonnement' value=<?php echo $valueButton ?>>
                    </form>
                <?php
                }
                ?>
            </section>
        </aside>
        <main>

            <?php
            //on déclare la variable explanation pour s'en servir après si jamais il y en a besoin
            $explanation = "";
            // on ajoute le formulaire "message" pour poster des messages sur son propre mur
            if ($userId == intval($_SESSION['connected_id'])) {
                $enCoursDeTraitement = isset($_POST['message']);
                if ($enCoursDeTraitement) {

                    $postContent = $_POST['message'];

                    // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                    $postContent = $mysqli->real_escape_string($postContent);
                    // construction de la requete
                    $lInstructionSql = "INSERT INTO posts (id, user_id, content, created, parent_id) "
                        . "VALUES (NULL, "
                        . "'" . $_SESSION['connected_id'] . "', "
                        . "'" . $postContent . "', "
                        . "NOW(), "
                        . "NULL);"
                    ;

                    $ok = $mysqli->query($lInstructionSql);
                    if (!$ok) {
                        $explanation = "Impossible d'ajouter le message: " . $mysqli->error ;
                    } else {
                        $explanation = "Votre message a bien été posté.";
                    }
                }
                ?>

                <article>
                    <h2>Poster un message</h2>
                    <div name="explanation">
                        <?php echo $explanation ?>
                    </div>
                    <form action="" method="post">
                    <dl>
                        <dt><label for='message'>Message</label></dt>
                        <dd><textarea name='message'></textarea></dd>
                    </dl>
                    <input type='submit'>
                    </form>
                </article>

            <?php } ?>

            <?php
            //récupérer tous les posts du user visité
            $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name, 
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }

            while ($post = $lesInformations->fetch_assoc()) {
                ?>
                <article>
                    <h3>
                        <time>
                            <?php echo $post['created'] ?>
                        </time>
                    </h3>
                    <address>par
                        <?php echo $post['author_name'] ?>
                    </address>
                    <div>
                        <?php echo $post['content'] ?>
                    </div>
                    <footer>
                        <small>♥
                            <?php echo $post['like_number'] ?>
                        </small>
                        <a href="">#
                            <?php echo $post['taglist'] ?>
                        </a>
                    </footer>
                </article>
            <?php } ?>


        </main>
    </div>
</body>

</html>