<?php
//Récupération des label des tags et tag_id sur les posts
$laQsurlesLabels = "
    SELECT tags.label, posts_tags.tag_id 
    FROM tags 
    INNER JOIN posts_tags ON tags.id = posts_tags.tag_id 
    WHERE post_id = $idDUPost" ; 

$listsTags = $mysqli->query($laQsurlesLabels);


?>