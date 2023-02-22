<?php
session_start();
?>
<!doctype html>
<html lang="fr">

<head>
    <?php include_once('headmeta.php'); ?>
    <title>ReSoC - Connexion</title>
</head>

<body>
    <?php include_once('header.php'); ?>
    <?php include('connexion.php'); ?>
    <div class="alert"></div>

    <div id="wrapper">

        <aside>
            <h2>Présentation</h2>
            <p>Bienvenue sur notre réseau social.</p>
        </aside>
        <main>
            <article>
                <h2>Connexion</h2>
                <?php
                /**
                 * TRAITEMENT DU FORMULAIRE
                 */

                $enCoursDeTraitement = isset($_POST['email']);
                if ($enCoursDeTraitement) {

                    $emailAVerifier = $_POST['email'];
                    $passwdAVerifier = $_POST['motpasse'];

                    //Etape 4 : Petite sécurité
                    // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                    $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
                    $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);
                    // on crypte le mot de passe pour éviter d'exposer notre utilisatrice en cas d'intrusion dans nos systèmes
                    $passwdAVerifier = md5($passwdAVerifier);
                    //Etape 5 : construction de la requete
                    $lInstructionSql = "SELECT * "
                        . "FROM users "
                        . "WHERE "
                        . "email LIKE '" . $emailAVerifier . "'"
                    ;
                    // Etape 6: Vérification de l'utilisateur
                    $res = $mysqli->query($lInstructionSql);
                    $user = $res->fetch_assoc();
                    if (!$user or $user["password"] != $passwdAVerifier) {
                        echo "La connexion a échouée. ";
                        ?>
                        <form action="login.php" method="post">
                            <input type='hidden' name='???' value='achanger'>
                            <dl>
                                <dt><label for='email'>E-Mail</label></dt>
                                <dd><input type='email' name='email'></dd>
                                <dt><label for='motpasse'>Mot de passe</label></dt>
                                <dd><input type='password' name='motpasse'></dd>
                            </dl>
                            <input type='submit'>
                        </form>
                        <p>
                            Pas de compte?
                            <a href='registration.php'>Inscrivez-vous.</a>
                        </p>
                    <?php
                    } else {
                        echo "Votre connexion est un succès : " . $user['alias'] . ".";

                        // Etape 7 : Se souvenir que l'utilisateur s'est connecté pour la suite
                        $_SESSION['connected_id'] = $user['id'];
                    }
                } else {
                    ?>

                    <form action="" method="post">
                        <input type='hidden' name='???' value='achanger'>
                        <dl>
                            <dt><label for='email'>E-Mail</label></dt>
                            <dd><input type='email' name='email'></dd>
                            <dt><label for='motpasse'>Mot de passe</label></dt>
                            <dd><input type='password' name='motpasse'></dd>
                        </dl>
                        <input type='submit'>
                    </form>
                    <p>
                        Pas de compte?
                        <a href='registration.php'>Inscrivez-vous.</a>
                    </p>
                <?php
                }
                ?>



            </article>
        </main>
    </div>
</body>

</html>