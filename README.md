# Chaine de Traitement Automatisee — SAE 1.03

[![Docker](https://img.shields.io/badge/Docker-2496ED?style=flat&logo=docker&logoColor=white)](https://www.docker.com)
[![Bash](https://img.shields.io/badge/Bash-4EAA25?style=flat&logo=gnu-bash&logoColor=white)](https://www.gnu.org/software/bash/)
[![PHP](https://img.shields.io/badge/PHP_8.3_CLI-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net)


Réalisé par Hugo Davy, Marcel Ekia Diwanga, Divi Le Gall, Fevzi Emre Gündüz (2026).

---

## Objectif du Projet

Concevoir et developper une chaine de traitement automatisee et industrialisee capable de nettoyer, standardiser et transformer des lots de fichiers bruts (images, donnees Excel et textes). L'enjeu principal etait de batir une solution robuste basee sur des conteneurs ephemeres, garantissant qu'aucun outil tiers (hors Docker et Bash local) ne soit requis sur la machine hote pour executer les traitements.

---
## Accès au dossier de convertion principal

Pour cela il vous faut télécharger le dossier nommé "convert" puis éxecuter via un terminal le script nommé "script_principal.sh"


## Architecture et Fonctionnement

Le projet respecte scrupuleusement une arborescence de fichiers standardisee pour dissocier les scripts du flux de donnees :

```text
convert/
├── data/
│   ├── input/           # Fichiers sources recus (images, excel, textes)
│   └── output/          # Fichiers finaux traites et rapports produits
|
|___script_principal.sh  # Ordonnanceur general (seul script execute en local)
|
|
|___scripts
    ├── traitement_images.sh
    ├── traitement_excel.sh
    ├── traitement_textes.sh
