#!/bin/bash

> data/output/rapport_global.txt

RAPPORT="./data/output/rapport_global.txt"

echo "¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤   Rapport de traitement des fichiers   ¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤" >> "$RAPPORT"
echo "" >> "$RAPPORT"
echo "" >> "$RAPPORT"
echo "Date d'exécution : $(date)" >> "$RAPPORT"
echo "" >> "$RAPPORT"
echo "" >> "$RAPPORT"




docker run --rm -v "$(pwd):/work" -w /work bigpapoo/sae103-imagick scripts/conversion_images.sh
echo "" >> "$RAPPORT"




echo "" >> "$RAPPORT"
docker run --rm -v "$(pwd):/work" -w /work php:8.2 php scripts/traitement_texte.php
echo "" >> "$RAPPORT"


echo "" >> "$RAPPORT"
echo "" >> "$RAPPORT"
echo "" >> "$RAPPORT"



echo "--------------------  Début traitement Excel / PDF --------------------" >> "$RAPPORT"


echo "[1/4] XLSX -> CSV brut" >> "$RAPPORT"
docker run --rm --platform linux/amd64 -v "$(pwd):/work" bigpapoo/sae103-excel2csv \
  ssconvert /work/data/input/convertexcel/sites.xlsx /work/data/output/sites_raw.csv >> "$RAPPORT" 2>&1


echo "[2/4] Nettoyage CSV" >> "$RAPPORT"
docker run --rm -v "$(pwd):/work" -w /work bigpapoo/sae103-php php -r '
$in = "data/output/sites_raw.csv";
$out = "data/output/sites.csv";
$fi = fopen($in, "r");
$fo = fopen($out, "w");
$ligne = 0;
while (($row = fgetcsv($fi, 0, ",")) !== false) {
    $ligne++;
    if ($ligne <= 3) continue;
    if (count($row) < 3) continue;

    $nom = trim($row[0]);
    $dept = trim($row[1]);
    $vis = trim($row[2]);

    if ($dept == "" || $nom == "") continue;
    if ($vis == "") $vis = "0";

    fputcsv($fo, [$dept, $nom, $vis], ";");
}
fclose($fi);
fclose($fo);
' >> "$RAPPORT" 2>&1


echo "[3/4] Génération data_html.php" >> "$RAPPORT"
docker run --rm -v "$(pwd):/work" -w /work bigpapoo/sae103-php php scripts/prepare_data_html.php \
  data/output/sites.csv \
  data/input/convertexcel/DEPTS \
  data/input/convertexcel/REGIONS \
  scripts/data_html.php >> "$RAPPORT" 2>&1


echo "[4/4] Génération HTML" >> "$RAPPORT"
docker run --rm --platform linux/amd64 -v "$(pwd):/work" -w /work bigpapoo/sae103-php \
  php scripts/generateur_html.php >> "$RAPPORT" 2>&1


echo "[4/4] Conversion HTML -> PDF" >> "$RAPPORT"
docker run --rm --platform linux/amd64 -v "$(pwd):/work" -w /work bigpapoo/sae103-html2pdf \
  weasyprint data/output/temp_dept.html data/output/sites-dept.pdf >> "$RAPPORT" 2>&1


docker run --rm --platform linux/amd64 -v "$(pwd):/work" -w /work bigpapoo/sae103-html2pdf \
  weasyprint data/output/temp_visites.html data/output/sites-visites.pdf >> "$RAPPORT" 2>&1


docker run --rm --platform linux/amd64 -v "$(pwd):/work" -w /work bigpapoo/sae103-html2pdf \
  weasyprint data/output/temp_regions.html data/output/sites-regions.pdf >> "$RAPPORT" 2>&1


echo "[Fin] Vérification PDFs" >> "$RAPPORT"


rm -f data/output/temp_*.html data/output/sites_raw.csv data/output/sites.csv

ls -l data/output >> "$RAPPORT" 2>&1


echo "¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤   Fin du rapport   ¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤" >> "$RAPPORT"
