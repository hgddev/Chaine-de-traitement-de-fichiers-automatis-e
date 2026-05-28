#!/usr/bin/env php
<?php

$fichier_sites = $argv[1] ?? "";
$fichier_depts = $argv[2] ?? "";
$fichier_regions = $argv[3] ?? "";
$fichier_sortie = $argv[4] ?? "";

$noms_depts = [];
$lignes = file($fichier_depts);
foreach ($lignes as $ligne) {
    $ligne = trim($ligne);
    if ($ligne != "") {
        $noms_depts[] = $ligne;
    }
}

$codes_depts = [];
for ($i = 1; $i <= 19; $i++) {
    $codes_depts[] = str_pad((string)$i, 2, "0", STR_PAD_LEFT);
}
$codes_depts[] = "2A";
$codes_depts[] = "2B";
for ($i = 21; $i <= 95; $i++) {
    $codes_depts[] = (string)$i;
}

$depts = [];
for ($i = 0; $i < count($codes_depts) && $i < count($noms_depts); $i++) {
    $depts[$codes_depts[$i]] = $noms_depts[$i];
}

$dept_vers_region = [];
$lignes = file($fichier_regions);
foreach ($lignes as $ligne) {
    $ligne = trim($ligne);
    if ($ligne == "") continue;

    $parties = explode("=", $ligne, 2);
    if (count($parties) < 2) continue;

    $nom_region = trim($parties[0]);
    $liste = trim($parties[1]);

    $liste_codes = explode(",", $liste);
    foreach ($liste_codes as $code) {
        $code = trim($code);
        if ($code == "") continue;

        if (ctype_digit($code)) {
            $code = str_pad($code, 2, "0", STR_PAD_LEFT);
        }

        $dept_vers_region[$code] = $nom_region;
    }
}

$sites = [];
$lignes = file($fichier_sites);
foreach ($lignes as $ligne) {
    $ligne = trim($ligne);
    if ($ligne == "") continue;

    $parties = explode(";", $ligne);
    if (count($parties) < 3) continue;

    $code_dept = trim($parties[0]);
    $nom_site = trim($parties[1]);
    $visiteurs = (int) trim($parties[2]);

    if (ctype_digit($code_dept)) {
        $code_dept = str_pad($code_dept, 2, "0", STR_PAD_LEFT);
    }

    if ($code_dept == "" || $nom_site == "") continue;

    $sites[] = [
        "dept_code" => $code_dept,
        "nom_site" => $nom_site,
        "visiteurs" => $visiteurs
    ];
}

$total_par_dept = [];
foreach ($sites as $s) {
    $d = $s["dept_code"];
    if (!isset($total_par_dept[$d])) {
        $total_par_dept[$d] = 0;
    }
    $total_par_dept[$d] += $s["visiteurs"];
}

$data_dept = [];
foreach ($depts as $code => $nom) {
    $data_dept[] = [
        "dept_code" => $code,
        "dept_nom" => $nom,
        "total_visiteurs" => $total_par_dept[$code] ?? 0
    ];
}

usort($data_dept, function($a, $b) {
    return strcmp($b["dept_code"], $a["dept_code"]);
});

$data_visites = $sites;
usort($data_visites, function($a, $b) {
    if ($a["visiteurs"] == $b["visiteurs"]) {
        return strcmp($a["dept_code"], $b["dept_code"]);
    }
    return ($a["visiteurs"] < $b["visiteurs"]) ? 1 : -1;
});

$total_par_region = [];
foreach ($sites as $s) {
    $d = $s["dept_code"];
    if (!isset($dept_vers_region[$d])) continue;

    $r = $dept_vers_region[$d];
    if (!isset($total_par_region[$r])) {
        $total_par_region[$r] = 0;
    }
    $total_par_region[$r] += $s["visiteurs"];
}

$data_regions = [];
foreach ($total_par_region as $region => $total) {
    $data_regions[] = [
        "region_nom" => $region,
        "total_visiteurs" => $total
    ];
}

usort($data_regions, function($a, $b) {
    if ($a["total_visiteurs"] == $b["total_visiteurs"]) {
        return strcmp($a["region_nom"], $b["region_nom"]);
    }
    return ($a["total_visiteurs"] < $b["total_visiteurs"]) ? 1 : -1;
});

$sortie = "";
$sortie .= "<?php\n";
$sortie .= "\$data_dept = " . var_export($data_dept, true) . ";\n";
$sortie .= "\$data_visites = " . var_export($data_visites, true) . ";\n";
$sortie .= "\$data_regions = " . var_export($data_regions, true) . ";\n";

file_put_contents($fichier_sortie, $sortie);
