----ScriptIP_V3.6-----

Ce Script a pour But de Ping des IP contenue dans le première argument ou présent dans une base de donnée et l'envoyer par Mail

----License----

Lire la liscence dans le FIchier text : " COPYRIGHT.txt " présent dans ce dossier

-----Démarage-----

!!!!!! Pour pouvoir recevoir le mail veuiller préciser votre mail dans le script Ligne 192 !!!!!! 

Pour lancer le script il suffit de lui metre des droits d'execution avec "CHMOD"

  exemple : " chmod 770 ScriptvIP_V3.6 "

Mais aussi de préciser "php" avec l'appel du script
 
  exemple : " php monscript "

-----Argumentation------

Puis Argumentée le Script avec :
  
  -Premier argument : Liste des IPS a tester ou "-db" pour préciser que les IP vienne de la base de donnée
  -Deuxième Argument : Le fichier Html a générer avec l'extension ".html"

-----Exemple Syntaxe-----

Kali : ./scriptvIP  Adresse.txt Ma_page.html
ou
Kali : ./scriptvIP  -db Ma_page.html

-----Cofiguration Du Mail------
 
 Utilitaire utilisé :ssmtp
 
  --Installation--
  
   sudo apt-get install ssmtp
  
  --Configuration--
  
  Pour configurer ssmtp il faut se rendre le fichier de configuration ssmtp.conf 
  Il se situe dans /etc/ssmtp/ssmtp.conf
  !! Attention c'est un fichier cacher il faut donc etre en root pour le configuration !!
   
    --Puis configurer ssmtp de la sorte--
    
       configuration du mail qui recoit le mail: root=yourmail@exemple.com
       configuration du serveur mail utilisé : mailhub=smtp.your_mail_service:587
       Configuration du non de l'utisateur mail : hostname=nom
       Configuration du mail sécuriser : UseSTARTTLS=Yes
       Configuration de modification de l'entete par ssmtp : FromLineOverride=YES
       Configuration du mail qui envoie le mail: AuthUser:Your_mail@exemple.com
       Configuration du Mot de passe de l'expediteur(Le mot de passe du compte) :AuthPass=Mot_De_Passe
       
        --Fin de la configuration--
       
-----Configuration De L'envoie automatique ----
 
  Urilitaire Utilisé : crontab
  Crontab permet de configurer une comande automatique en configurant le temps entre chaque automatisation d'une comande ou d'un script comme ici
   
   --Configuration--
   
    Pour configurer Crontab il faut ouvrir un terminal 
    Et utiliser la comande : crontab -e
    Puis sélectioner 1. /bin/nano
    commencer a écrire en bas du fichier de configuration avec "*"
    Puis en dessous de "m" choisir le temps en minute de la répétition en minute
    En desous de "H" choisir le temps en heure de la répétition den heure
    Dom c'est pour configurer une journée précise du mois [1-31]
    mon c'est pour configurer le mois précis [1-12]
    dow c'est pour une journé de la semaine [1-6]
    Si vous ne voulez pas programer une de ces option écrire "*" en desous de celui si
    Puis a la suite de la configuration écrire: bash /"Chemin du script"/ /"chemin de la liste d'adresse"/ ""Nom du fichier html a crée"" ""Adresse mail utilisé pour recevoir le résultat"
    
    --Fin de la configuration--
    
------Auteur-----

Auteur : Antonin Cosse

