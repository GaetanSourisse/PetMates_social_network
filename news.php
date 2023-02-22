<?php
session_start();
?>
<!doctype html>
<html lang="fr">

<head>
    <?php include_once('headmeta.php'); ?>
    <title>ReSoC - Actualités</title>
</head>

<body>

    <?php include_once('header.php'); ?>
    <?php include('connexion.php'); ?>
    <div class="alert">
        <!-- Cas où la personne n'est pas loggée -->
        <!-- On lui propose de se logger ou de créer son compte -->
        <?php
        if (empty($_SESSION['connected_id'])) {
        ?>
            <article class="card_gestion_connexion">
                <p>
                    <?php echo "Veuillez vous "; ?>
                    <a href="login.php">connecter</a>
                    <?php echo "ou "; ?>
                    <a href="registration.php">créer votre compte.</a>
                </p>
            </article>

        <?php
        }?>
    </div>
    <div id="wrapper">

        <main>
            <?php
            $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    posts.id,
                    posts.user_id,
                    users.alias as author_name,  
                    count(likes.id) as like_number,
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";

            $lesInformations = $mysqli->query($laQuestionEnSql);

            // Vérification
            if (!$lesInformations) {
                echo "<article>";
                echo ("Échec de la requete : " . $mysqli->error);
                echo ("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                exit();
            }

            //pour chaque post dispos dans la base de données...
            while ($post = $lesInformations->fetch_assoc()) {

                $idDuPost = $post['id'];
                //si le bouton like est cliqué
                if (isset($_POST['like']) && $_POST['like'] == $idDuPost) {

                    // requête pour chercher si le like existe
                    $questionSqlIsLiked = "SELECT * FROM likes WHERE post_id='$idDuPost' AND user_id='" . $_SESSION['connected_id'] . "';";
                    $infoLiked = $mysqli->query($questionSqlIsLiked);
                    // si la requête échoue, message échec
                    if (!$infoLiked) {
                        echo "échec" . $mysqli->error;
                    } else {
                        // si la requête réussit, si le like est déjà présent alors le count like désincrémente, sinon il s'incrémente 
                        if ($infoLiked->fetch_assoc()) {
                            $deleteLike = "DELETE FROM likes WHERE post_id ='$idDuPost' AND user_id ='" . $_SESSION['connected_id'] . "';";
                            $mysqli->query($deleteLike);
                            $post['like_number']--;
                        } else {
                            $questionSqlNewLike = "INSERT INTO likes (id, post_id, user_id) VALUES (NULL, '$idDuPost', '" . $_SESSION['connected_id'] . "');";
                            $mysqli->query($questionSqlNewLike);
                            $post['like_number']++;
                        }
                    };
                }
                ?>

                <!-- on créé un article dans le html -->
                <article>
                    <h3>
                    <time>
                        <?php
                        //formatage de la date
                        $stringDate = $post['created'];
                        $dateJourTiret = substr($stringDate, 0, 9);
                        $heureTiret = substr($stringDate, 11, -1);
                        list($year, $day, $month) = explode("-",$dateJourTiret);
                        list($hour, $minuts, $seconds) = explode(":", $heureTiret);
                        ?>
                        Publié le <?php echo $day."/".$month."/".$year ?> à <?php echo $hour ?> h <?php echo $minuts ?>
                    </time>
                    </h3>

                    <address>par <a href="wall.php?user_id=<?php echo $post['user_id'] ?>"><?php echo $post['author_name'] ?></a></address>

                    <div>
                        <p>
                            <?php echo $post['content'] ?>
                        </p>
                    </div>

                    <div class="tags">
                        <?php
                        //Récupération des label des tags et tag_id sur les posts
                        $laQsurlesLabels = "
                            SELECT tags.label, posts_tags.tag_id 
                            FROM tags 
                            INNER JOIN posts_tags ON tags.id = posts_tags.tag_id 
                            WHERE post_id = $idDuPost";

                        $listsTags = $mysqli->query($laQsurlesLabels);

                        while ($tags = $listsTags->fetch_assoc()) { ?>
                            <a href="tags.php?tag_id=<?php echo $tags['tag_id'] ?>">
                                <?php echo "#".$tags['label'] ?>
                            </a>
                        <?php
                        } ?>
                    </div>

                    <footer>
                        <form action="" method="post">
                            <button type='submit' name='like' value='<?php echo $idDuPost ?>'
                                <?php if (!empty($_SESSION['connected_id'])) {
                                if ($post['user_id'] == $_SESSION['connected_id']) {
                                    echo "disabled";}} ?>>
                                            <div class="likePlace">
                                                ♥
                                                <?php echo $post['like_number'] ?>
                                            </div>
                            </button>
                        </form>
                            <?php
                            while ($tags = $listsTags->fetch_assoc()) { ?>
                                <a href="tags.php?tag_id=<?php echo $tags['tag_id'] ?>">
                                    <?php echo "#" . $tags['label'] ?>
                                </a>
                            <?php
                            } ?>
                    </footer>

                    <div id="allcomments">
                        <?php
                        if (!empty($_POST['commentaire'])){
                            //envoi du commentaire dans la bdd
                            $userId = $_SESSION['connected_id'];
                            $commentContent = $_POST['commentaire'];
                            $commentContent = $mysqli->real_escape_string($commentContent);
                            $postComment = $_POST['postcomment'];
                            $rqtComment = "INSERT INTO comments(id, id_post, content, user_id, created) VALUES (NULL,'$idDuPost','$commentContent','$userId',NOW());";

                            if (isset($commentContent) && isset($postComment) && $postComment == $idDuPost) {
                                $infoPostComment = $mysqli->query($rqtComment);
                            }
                        };
                        //affichage des commentaires
                        $requeteComment = "SELECT * FROM comments WHERE id_post = '$idDuPost';";
                        $infoComment = $mysqli->query($requeteComment);

                        while ($comment = $infoComment->fetch_assoc()) {
                            //récupération de l'alias correspondant au commentaire
                            $requeteAlias = "SELECT alias FROM users WHERE id=".$comment['user_id'].";";
                            $infoAlias = $mysqli->query($requeteAlias);
                            $alias = $infoAlias->fetch_assoc();
                            ?>
                            <div id="wrappercomment">
                                <div id="begin">
                                    <h3>
                                    <time>
                                        <?php
                                        //formatage de la date
                                        $stringDate = $comment['created'];
                                        $dateJourTiret = substr($stringDate, 0, 9);
                                        $heureTiret = substr($stringDate, 11, -1);
                                        list($year, $day, $month) = explode("-",$dateJourTiret);
                                        list($hour, $minuts, $seconds) = explode(":", $heureTiret);
                                        ?>
                                        Publié le <?php echo $day."/".$month."/".$year ?> à <?php echo $hour ?> h <?php echo $minuts ?>
                                    </time>
                                    </h3>
                                    <adress>par <a
                                            href="wall.php?user_id=<?php echo $comment['user_id'] ?>"><?php
                                               echo $alias['alias'] ?></a>
                                    </adress>
                                </div>
                                <div>
                                    <p>
                                        <?php echo $comment['content'] ?>
                                    </p>
                                </div>
                            </div>
                        <?php }
                        ?>
                    </div>
                    <form action="" method="post">
                        <dl>
                            <dt><label for='commentaire'>Commentaire</label></dt>
                            <dd><textarea name='commentaire'></textarea></dd>
                        </dl>
                        <button type='submit' name='postcomment' value='<?php echo $idDuPost ?>'>Envoyer le
                            commentaire</button>
                    </form>
                </article>
            <?php
            }
            ?>
        </main>
    </div>
</body>

</html>