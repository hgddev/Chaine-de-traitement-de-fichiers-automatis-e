#!/bin/bash

INPUT="./data/input/images"
OUTPUT="./data/output/images"
RAPPORT="./data/output/rapport_global.txt"

rm -rf "$OUTPUT"
mkdir -p "$OUTPUT"

MIN_WIDTH=350
MIN_HEIGHT=250
MAX_WIDTH=900
MAX_HEIGHT=620
POID_IMG=184320

echo "----------Début du traitement des images ----------" >> "$RAPPORT"
echo "" >> "$RAPPORT"

for image in "$INPUT"/*
    do        
    ext=${image##*.}
    nom=$(basename "$image" ."$ext")
    format=$(identify -format "%m" "$image")
    if [[ "$format" != "JPEG" && "$format" != "PNG" && "$format" != "WEBP" && "$format" != "GIF" && "$format" != "AVIF" ]]
    then
    	echo "" >> "$RAPPORT"
        echo "IMAGE : $nom.$ext" >> "$RAPPORT"
        echo "- Statut : X Fichier ignoré (extension non supportée)" >> "$RAPPORT"
        echo "" >> "$RAPPORT"
        cp "$image" "$OUTPUT/"
        continue
    fi
    
    
    if [[ "$format" != "WEBP" ]]
    then
        nom_final="$OUTPUT/$nom.webp"
        convert "$image" "$nom_final"
    else
        nom_final="$OUTPUT/$nom.$ext"
        cp "$image" "$nom_final"
    fi

    dimensions=$(identify -format "%wx%h" "$nom_final")
    width=${dimensions%x*}
    height=${dimensions#*x}
    
    echo "" >> "$RAPPORT"
    echo "IMAGE : $nom.$ext" >> "$RAPPORT"
    echo "- Format initial : $format" >> "$RAPPORT"
    echo "- Dimensions initiales : ${width}x${height}" >> "$RAPPORT"


    if [[ $width -le $MAX_WIDTH && $height -le $MAX_HEIGHT ]]
    then
        echo "- Redimension : Non nécessaire (image trop petite ou déjà conforme)" >> "$RAPPORT"
    else
        convert "$nom_final" -resize "${MAX_WIDTH}x${MAX_HEIGHT}>" "$nom_final"

        nouvelles_dimensions=$(identify -format "%wx%h" "$nom_final")
        new_width=${nouvelles_dimensions%x*}
        new_height=${nouvelles_dimensions#*x}

        if [[ $new_width -lt $MIN_WIDTH || $new_height -lt $MIN_HEIGHT ]]
        then
            echo "- Redimension : Impossible, la redimension dépasserait les dimensions minimales" >> "$RAPPORT"
        else
            echo "- Redimension : Effectuée !" >> "$RAPPORT"
            echo "- Nouvelles dimensions : ${new_width}x${new_height}" >> "$RAPPORT"
        fi
    fi
    
    
    size=$(ls -l "$nom_final" | awk '{print $5}')

    if [[ $size -gt $POID_IMG ]]
    then
	while [[ $size -gt $POID_IMG ]]
	do
		convert "$nom_final" -quality 99 "$nom_final"
		size=$(ls -l "$nom_final" | awk '{print $5}')
	done
        echo "- Poids ajusté : ($((size / 1024)) Ko)" >> "$RAPPORT"
    else
        echo "- Poids : déjà conforme ($((size / 1024)) Ko)" >> "$RAPPORT"
    fi
 

    format_final=$(identify -format "%m" "$nom_final")
    echo "- Format final : $format_final" >> "$RAPPORT"
    echo "" >> "$RAPPORT"

done 

echo "" >> "$RAPPORT"
echo "----------Fin du traitement des images ----------" >> "$RAPPORT"



https://openclassrooms.com/forum/sujet/taille-d-une-image-depuis-son-url-bash-scripting-26780
pour les dimensions