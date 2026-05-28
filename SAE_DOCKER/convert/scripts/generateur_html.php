<?php

include __DIR__ . '/data_html.php';

$CSS = '/work/data/input/convertexcel/style.css';
$LOGO = '/work/data/input/convertexcel/logo.png';

$SORTIE_DEPT = 'data/output/temp_dept.html';
$SORTIE_VISITES = 'data/output/temp_visites.html';
$SORTIE_REGIONS = 'data/output/temp_regions.html';

function conversion_html($data, $titre, $sortie) {
    global $CSS, $LOGO;
    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title><?= $titre ?></title>
        <link rel="stylesheet" href="<?= $CSS ?>">
    </head>
    <body>
        <header>
            <img src="<?= $LOGO ?>" alt="Logo Offices de Tourisme de France">
            <h3><?= $titre ?></h3>
        </header>

        <table>
            <thead>
                <tr><th>Departement</th><th>Site</th><th>Visiteurs</th></tr>
            </thead>
            <tbody>
                <?php foreach ($data as $entree): ?>

                    <?php
                    // Département ou région
                    if (isset($entree['dept_code'])) {
                        $col1 = $entree['dept_code'];
                    }else{
                        $col1 = $entree['region_nom'];
                    }

                    if (isset($entree['nom_site'])) {
                        $col2 = $entree['nom_site'];
                    }else{
                        if (isset($entree['dept_nom'])) {
                            $col2 = $entree['dept_nom'];
                        }else{
                            $col2 = ""; // cas régions: pas de nom de site/dépt
                        }
                    }

                    // Visiteurs
                    if (isset($entree['visiteurs'])) {
                        $col3 = $entree['visiteurs'];
                    }else{
                        $col3 = $entree['total_visiteurs'];
                    }

                    // Affichage (UTF-8 pour éviter les mauvaises surprises)
                    $col1 = htmlspecialchars($col1, ENT_QUOTES, 'UTF-8');
                    $col2 = htmlspecialchars($col2, ENT_QUOTES, 'UTF-8');
                    $col3 = htmlspecialchars((string)$col3, ENT_QUOTES, 'UTF-8');
                    ?>

                    <tr>
                        <td><?= $col1 ?></td>
                        <td><?= $col2 ?></td>
                        <td><?= $col3 ?></td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
    </html>
    <?php
    $contenu = ob_get_clean();
    file_put_contents($sortie, $contenu);
}

conversion_html($data_dept, 'Rapport Sites, Tri par Departement', $SORTIE_DEPT);
conversion_html($data_visites, 'Rapport Sites,Tri par Visites', $SORTIE_VISITES);
conversion_html($data_regions, 'Synthese Regionale, Visites par Region', $SORTIE_REGIONS);

?>
