# PARTICIPATION MEMBRES EQUIPE A6

## TABLEAU DE SYNTHESE JEU DE FICHIERS

Temps total : 25h

| Membre | %   | Commentaire |
| ------ | --- | --------------------------------------------------------------------------------------------------|
| Emré   | 25  | Génération des rendus HTML et PDF + traitement des données en PHP                                 |
| Divi   | 25  | Découpage du fichier "presentation_musee_louvre" TITRE/PARAGRAPHE                                 |
| Hugo   | 25  | Convertion des images au bon format ainsi que le managment du rapport global                      |
| Marcel | 25  | Traitement des données en PHP                                                                     |    
-------------------------------------------------------------------------------------------------------------------|



## TACHES

CONTEXTE : Un dossier contenant notre arborescence sera fournie à l'équipe de développement web, 
chaque fichier à convertir ou modifier devra être ajouté à l'interieur du dossier INPUT dans son 
emplacement correspondant afin de garantir le bon déroulement des modifications de ces fichiers. 
En effet, nous avon smis en place une arborescence spécifique et a respecer. 

Voici notre arborescence : 


                            convert----------------data---------input__images
                               |                   |         |       |_convertexcel
                        script_principal.sh       scripts    |       |_texte
                               |                             |--ouput
                        participation.md 
        
        
        
            
            ------
            
                               
                               
Le dossier convert contient le script principal et 
le dossier scripts tout nos scripts  de modification

Dans le dossier ouput se trouvera tout nos fichiers convertis 
et prêts a être utilisés par l'equipe de développement     



Étape A-1 :  Convertion des images   
- Vérification du format de l'image
- Conversion des images n'étant pas dans le bon format en WEBP
- Modification si possible des dimensions
- Modification du poid

Étape A-2 : Gestion du fichier presentation_musee_louvre
- Suppression des balises
- Reécriture dans le fichier de sortie 
- Automatisation pour d'autres fichiers équivalent 


Étape A-3 : Chaîne de traitement  
- Analyse des fichiers fournis (Excel, images, fichiers texte DEPTS et REGIONS et fichier texte de presentation(fichier découpage TITRE & PARAGRAPHES) )
- Définition de l’ordre des traitements à effectuer  
- Mise en place d’un script principal pour automatiser l’ensemble du processus  
- Utilisation de Docker pour garantir un environnement identique à chaque exécution  

Étape A-4 : Conversion et nettoyage des données  
- Conversion du fichier Excel en CSV  
- Nettoyage du CSV (suppression des lignes inutiles, réorganisation des colonnes)  
- Vérification de la cohérence des données obtenues  
- Génération d’un fichier CSV final exploitable par les scripts PHP 

Étape B-2 : Traitement des données en PHP  
- Lecture du fichier CSV nettoyé  
- Création des tableaux PHP correspondant aux tris demandés :
  - Tri par département  
  - Tri par nombre de visiteurs  
  - Synthèse par région  
- Génération automatique du fichier `data.php` contenant les tableaux utilisés dans `generateur_html.php` 

Étape B-3 : Génération des rendus HTML et PDF  
- Création des pages HTML à partir des tableaux PHP  
- Mise en forme avec une feuille de style CSS fournie  
- Conversion automatique des pages HTML en fichiers PDF  
- Vérification de la conformité des résultats avec les consignes  

Étape B-4 : Organisation finale et tests  
- Organisation des dossiers d’entrée et de sortie  
- Tests complets de la chaîne de traitement avec un seul script  
- Vérification que l’exécution fonctionne sans intervention manuelle  
