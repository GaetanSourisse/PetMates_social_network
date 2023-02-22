<?php
session_start();
include('forbidenpage.php');
?>
<!doctype html>
<html lang="fr">

<head>
    <?php include_once('headmeta.php'); ?>
    <title>ReSoC - Flux</title>
</head>

<body>

    <?php include_once('header.php'); ?>
    <?php include('connexion.php'); ?>
    <div class="alert"></div>

    <div id="wrapper">

        <aside>
            <?php
            $userId = intval($_GET['user_id']);
            /**
             * Etape 3: récupérer le nom de l'utilisateur
             */
            $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
            //echo "<pre>" . print_r($user, 1) . "</pre>";
            ?>
        </aside>
        <main>
            <?php
            /**
             * Etape 3: récupérer tous les messages des abonnements
             */
            $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    posts.id,
                    posts.user_id,
                    users.alias as author_name,  
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM followers 
                    JOIN users ON users.id=followers.followed_user_id
                    JOIN posts ON posts.user_id=users.id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE followers.following_user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }

            /**
             * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
             * A vous de retrouver comment faire la boucle while de parcours...
             */
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
                    }
                    ;
                }
                ?>
                <article>
                    <h3>
                        <time>
                            Publié le <?php echo $post['created'] ?>
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
                                <?php echo "#" . $tags['label'] ?>
                            </a>
                        <?php
                        } ?>
                    </div>
                    <footer>
                        <form action="" method="post">
                            <button type='submit' name='like' value='<?php echo $idDuPost ?>'>
                                <small>
                                    <div class="likePlace">
                                    ♥
                                    <?php echo $post['like_number'] ?>
                                    </div>
                                </small>
                            </button>
                        </form>
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
                                            Publié le
                                            <?php echo $comment['created'] ?>
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
            <?php }
            ?>


        </main>
    </div>
</body>

</html>