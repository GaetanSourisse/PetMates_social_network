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
    <div class="alert"></div>

    <div id="wrapper">
        <?php
        //on récupère le user_id depuis l'url du mur où on se trouve
        $userId = intval($_GET['user_id']);
        ?>
        <aside class="present-profil">

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
            <img src="user.png" alt="Portrait de l'utilisatrice" />
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

                <h1 class="pseudo"><?php echo $user['alias'] ?></h1>
                <h4>Biographie perso 🖍️ </h4>
            
                <h4>Animaux🐾</h4>

                <?php
                if (isset($_POST['typePet'])) {
                $requetePet = "INSERT INTO pets (id, user_id, type_pet, race_pet, name_pet, sex_pet)"
                . "VALUES (NULL, "
                . "'" . $_SESSION['connected_id'] . "', "
                . "'" . $_POST['typePet'] . "', "
                . "'" . $_POST['racePet'] . "', "
                . "'" . $_POST['namePet'] . "', "
                . "'" . $_POST['pets'] . "'" 
                . ");";

                
                    $infoPets = $mysqli->query($requetePet);
                }

                $requeteListPet= "SELECT * FROM pets WHERE user_id= '$userId' ";
                $lesInfoPet = $mysqli->query($requeteListPet);
    
                while ($petList = $lesInfoPet->fetch_assoc()) {
                ?>
                    <div class="animList">
                        <ul>
                           <li class="nameAnim"><?php echo $petList['name_pet'] ?></li>
                           <li><?php echo $petList['type_pet'] ?> (<?php echo $petList['race_pet'] ?>)</li>
                           <li class="animSex"><?php echo $petList['sex_pet'] ?> </li>
                        </ul>    
                    </div>
                <?php
                }
                ?>
                
                <?php 
                //si on est sur son propre mur, on ajoute la possibilité d'afficher les abonnements et abonnés
                if ($userId == intval($_SESSION['connected_id'])) {
                 ?>   
                   
                        <div class="ajoutPets">
                        <form action='' method="post">
                            <button name="addPets" type="submit">+ Ajouter un animal</button>
                        </form>
                        <?php   

                        if (isset($_POST['addPets'])) {
                            
                            ?>
                            <form action="" method="post">
                                
                                    <label for='typePet'>Type de l'animal</label>
                                    <input name='typePet'>
                                   
                                    <label for='racePet'>Race de l'animal</label>
                                    <input name="racePet">
                                  
                                    <label for='namePet'>Nom de l'animal</label>
                                    <input name='namePet'>

                                    <label for="sex-select" class="sex-select">Sexe de l'animal</label>

                                    <select name="pets" class="sex-select">
                                        <option value="">Selectionner</option>
                                        <option value="&#9792; Femelle">Femelle</option>
                                        <option value="&#9794; Mâle">Mâle</option>    
                                    </select> 
                                
                                <input class="buttonPet" type='submit' value="valider">
                            </form>
                            <?php

                        }

                    ?>
                    </div>   
                    
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
        <main class="mainwall">

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
                        <dd><textarea id="textarea" name='message'></textarea></dd>
                    </dl>
                    <input type='submit'>
                    </form>
                </article>

            <?php } ?>

            <?php
            //lancement de la requête pour supprimer les posts
                if ((!empty($_SESSION['connected_id'])) && ($userId == intval($_SESSION['connected_id']))) { 
                    if (isset($_POST['supp'])){
                        $rqtDeletePost = "DELETE FROM posts WHERE id='".$_POST['supp']."';";
                        $mysqli->query($rqtDeletePost);
                    }; 
                };
                   
            //récupérer tous les posts du user visité
            $laQuestionEnSql = "
                    SELECT posts.content, posts.created, posts.user_id, posts.id, users.alias as author_name, 
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
            }; 

            while ($post = $lesInformations->fetch_assoc()) {
                
                //infos concernant les likes
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
                    <address>par
                        <?php echo $post['author_name'] ?>
                    </address>
                    <div>
                        <p>
                        <?php echo $post['content'] ?>
                        </p>
                    </div>
                    <div class="tags">
                        <?php 
                        //On vérifie que la taglist n'est pas vide avant d'afficher le #
                        if(!empty($post['taglist'])) { ?>
                        <a href="">#
                        <?php echo $post['taglist'] ?>
                        </a>
                        <?php } ?>
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
                            <div class="button">
                                <form action="" method="post">
                                    <button type='submit' name='supp' value='<?php echo $idDuPost ?>' class="button-sup">
                                            <div class="delete">
                                            🗑️
                                            </div>
                                    </button>
                                </form>
                            </div>
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
                            <dd><textarea id="textarea" name='commentaire'></textarea></dd>
                        </dl>
                        <button type='submit' name='postcomment' value='<?php echo $idDuPost ?>'>Envoyer le
                            commentaire</button>
                    </form>

                </article>
            <?php } ?>


        </main>
    </div>
</body>

</html>