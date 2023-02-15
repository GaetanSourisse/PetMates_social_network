<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Actualités</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    
    <body>

    <?php include_once('header.php'); ?>
    <?php include('connexion.php'); ?>

        <div id="wrapper">

            <aside>
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez les derniers messages de
                        toutes les utilisatrices du site.</p>
                </section>
            </aside>
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
                    LIMIT 5
                    ";

                $lesInformations = $mysqli->query($laQuestionEnSql);

                // Vérification
                if ( ! $lesInformations)
                {
                    echo "<article>";
                    echo("Échec de la requete : " . $mysqli->error);
                    echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                    exit();
                }

                //pour chaque post dispos dans la base de données...
                while ($post = $lesInformations->fetch_assoc())
                {

                    $idDUPost = $post['id'];

                ?>

                    <!-- on créé un article dans le html -->
                    <article>
                        <h3>
                            <time><?php echo $post['created'] ?></time>
                        </h3>

                        <address>par <a href="wall.php?user_id=<?php echo $post['user_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                        
                        <div>
                            <p><?php echo $post['content'] ?></p>
                        </div>

                        <footer>
                            <small><?php echo $post['like_number'] ?></small>
                            <?php 
                                
                                //Récupération des label des tags et tag_id sur les posts
                                $laQsurlesLabels = "
                                SELECT tags.label, posts_tags.tag_id 
                                FROM tags 
                                INNER JOIN posts_tags ON tags.id = posts_tags.tag_id 
                                WHERE post_id = $idDUPost" ; 

                                $listsTags = $mysqli->query($laQsurlesLabels);

                                while($tags = $listsTags->fetch_assoc()){?>
                                    <a href="tags.php?tag_id=<?php echo $tags['tag_id'] ?>">
                                    <?php echo "#" . $tags['label'] ?>
                                    </a>
                                <?php 
                                } ?>

                        </footer>

                    </article>
                    <?php
                }
                ?>

            </main>
        </div>
    </body>
</html>
