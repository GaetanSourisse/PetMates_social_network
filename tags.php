<?php
session_start();
include('forbidenpage.php');
?>
<!doctype html>
<html lang="fr">

<head>
    <?php include_once('headmeta.php'); ?>
    <title>ReSoC - Les message par mot-clé</title>
</head>

<body>

    <?php include_once('header.php'); ?>
    <?php include('connexion.php'); ?>

    <div id="wrapper">
        <?php

        $tagId = intval($_GET['tag_id']);
        ?>

        <aside>
            <?php

            $laQuestionEnSql = "SELECT * FROM tags WHERE id= '$tagId' ";

            $lesInformations = $mysqli->query($laQuestionEnSql);

            $tag = $lesInformations->fetch_assoc();
            ?>

            <img src="user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez les derniers messages comportant
                    le mot-clé #
                    <?php echo $tag['label'] ?>
                    (n°
                    <?php echo $tag['id'] ?>)
                </p>
            </section>
        </aside>
        <main>
            <?php

            $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    posts.user_id,
                    posts.id,
                    users.alias as author_name,  
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts_tags as filter 
                    JOIN posts ON posts.id=filter.post_id
                    JOIN users ON users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE filter.tag_id = '$tagId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";

            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }

            /* Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php */
            while ($post = $lesInformations->fetch_assoc()) {
                //echo "<pre>" . print_r($post, 1) . "</pre>";
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
                            <?php echo $post['created'] ?>
                        </time>
                    </h3>
                    <address>par <a href="wall.php?user_id=<?php echo $post['user_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                    <div>
                        <?php echo $post['content'] ?>
                    </div>
                    <footer>
                        <?php
                        if ($post['user_id'] !== $_SESSION['connected_id']) {
                            ?>
                            <form action="" method="post">
                                <button type='submit' name='like' value='<?php echo $idDuPost ?>'>
                                    <small>
                                        ♥
                                        <?php echo $post['like_number'] ?>
                                    </small>
                                </button>
                            </form>

                        <?php
                        }



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


                    </footer>
                </article>
            <?php } ?>


        </main>
    </div>
</body>

</html>