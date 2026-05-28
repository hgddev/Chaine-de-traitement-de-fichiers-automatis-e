<?php

$RAPPORT = "./data/output/rapport_global.txt";

file_put_contents($RAPPORT, "---------- Début du traitement du fichier texte(TITRES et paragraphes) ----------\n", FILE_APPEND);

$dossier_input = "./data/input/texte/";

$fichiers = scandir($dossier_input); // recupere tout les fichiers présents dans le repertoire

foreach ($fichiers as $entree) {

    if ($entree === "." || $entree === "..") continue;

    $entree = $dossier_input . $entree;

    $info = pathinfo($entree); // recupere l'extention du fichier qui dans notre cas n'est pas visible
    $sortie = "./data/input/texte/" . $info["filename"] . "_2." . $info["extension"];

    $fic_entree = fopen($entree, "r");
    $fic_sortie = fopen($sortie, "w");

    if ($fic_entree == false || $fic_sortie == false) {
        file_put_contents($RAPPORT, "Erreur d'ouverture des fichiers\n", FILE_APPEND);
        exit;
    }

    $titre_ecrit = false;

    while (($ligne = fgets($fic_entree)) != false) {

        $ligne = trim($ligne); // supprime les espaces inutile

        $position = strpos($ligne, "="); // recupere la position du "=" sur la ligne

        if ($position === false) {
            continue;
        } 

    
        $contenu = substr($ligne, $position + 1); // supprime tout jusqu'au "=" inclus

    
        if (substr($ligne, 0, $position) == "TITLE" && $titre_ecrit == false) {
            fwrite($fic_sortie, $contenu . PHP_EOL); // ecrit jusqu'au marqueur de fin de ligne
            $titre_ecrit = true;
        }

    
        else if (substr($ligne, 0, $position) == "TEXT") {
            fwrite($fic_sortie, $contenu . PHP_EOL); // ecrit jusqu'au marqueur de fin de ligne
        }
    }

    fclose($fic_entree);
    fclose($fic_sortie);

    file_put_contents($RAPPORT, "Traitement du fichier terminé\n", FILE_APPEND);

}

file_put_contents($RAPPORT, "---------- Fin du traitement du fichier texte(TITRES et paragraphes) ----------\n", FILE_APPEND);
?>