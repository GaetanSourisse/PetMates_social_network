<?php
//vérification si le membre est passé par la page de login :
if (!isset($_SESSION['connected_id'])) {
    $msg = "vous_devez_vous_identifier_pour_accéder_à_cette_page.";

    // si la variable de session login n'est pas enregistré : retour sur la page login.php
    header("location:login.php?page=login");
} else // si tu es bien connecté...
{
    $userId = $_SESSION['connected_id'];
}
;
?>