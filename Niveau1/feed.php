<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Flux</title>         
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>

    <body>

    <?php include_once('header.php'); ?>
    <?php include('connexion.php'); ?>
    
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
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les message des utilisatrices
                        auxquel est abonnée l'utilisatrice 
                        <a href="wall.php?user_id=<?php echo $userId ?>"><?php echo $user['alias'] ?></a>
                        (n° <?php echo $userId ?>)
                    </p>

                </section>
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
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                /**
                 * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
                 * A vous de retrouver comment faire la boucle while de parcours...
                 */
                while ($post = $lesInformations->fetch_assoc())
                {
                ?>                
                <article>
                    <h3>
                        <time><?php echo $post['created'] ?></time>
                    </h3>
                    <address>par <a href="wall.php?user_id=<?php echo $post['user_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                    <div>
                        <?php echo $post['content'] ?>
                    </div>                                            
                    <footer>
                        <small>♥ <?php echo $post['like_number'] ?></small>
                        <?php 

                        $idDUPost = $post['id'];

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
                <?php }
                // et de pas oublier de fermer ici vote while
                }  ?>


            </main>
        </div>
    </body>
</html>
