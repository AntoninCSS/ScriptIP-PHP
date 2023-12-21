----ScriptIP_V3.6-----

Ce Script a pour But de Ping des IP contenue dans le premier argument ou présent dans une base de données et l'envoyer par Mail

----License----

Lire la licence dans le Fichier texte : " COPYRIGHT.txt " présent dans ce dossier

-----Démarage-----

!!!!!! Pour pouvoir recevoir le mail veuiller préciser votre mail dans le script Ligne 192 !!!!!!

Pour lancer le script, il suffit à lui mettre des droits d'exécution avec "CHMOD"

Exemple : " chmod 770 ScriptvIP_V3.6 "

Mais aussi de préciser "php" avec l'appel du script

Exemple : " php monscript "

-----Argumentation------

Puis Argumentée, le Script avec :

- Premier argument : liste des IPS a testé ou "-db" pour préciser que les IP viennent de la base de données.

- Deuxième Argument : le fichier Html a généré avec l'extension ".html"

-----Exemple Syntaxe-----

Kali : ./scriptvIP Adresse.txt Ma_page.html

Ou

Kali : ./scriptvIP -db Ma_page.html

-----Cofiguration Du Mail------

Utilitaire utilisé :ssmtp

--Installation--

sudo apt-get install ssmtp

--Configuration--

Pour configurer ssmtp il faut se rendre le fichier de configuration ssmtp.conf

Il se situe dans /etc/ssmtp/ssmtp.conf

!! Attention, c'est un fichier cacher il faut donc etre en root pour le configuration !!

--Puis configurer ssmtp de la sorte--

configuration du mail qui recoit le mail : root=yourmail@exemple.com

configuration du serveur mail utilisé : mailhub=smtp.your_mail_service:587

Configuration du non de l'utisateur mail : hostname=nom

Configuration du mail sécurisé : UseSTARTTLS=Yes

Configuration de modification de l'entête par ssmtp : FromLineOverride=YES

Configuration du mail qui envoie le mail: AuthUser:Your_mail@exemple.com

Configuration du Mot de passe de l'expéditeur (Le mot de passe du compte) :AuthPass=Mot_De_Passe

--Fin de la configuration--

-----Configuration De L'envoie automatique ----

Urilitaire Utilisé : crontab

Crontab permet de configurer une commande automatique en configurant le temps entre chaque automatisation d'une commande ou d'un script comme ici

--Configuration--

Pour configurer Crontab, il faut ouvrir un terminal.

Et utiliser la commande : crontab -e

Puis sélectioner 1. /bin/nano

commencer a écrire en bas du fichier de configuration avec "*"

Puis en dessous de "m" choisir le temps en minute de la répétition en minute

En desous de "H" choisir le temps en heure de la répétition den heure

Dom, c'est pour configurer une journée précise du mois [1-31]

Mon, c'est pour configurer le mois précis [1-12]

Dow, c'est pour une journée de la semaine [1-6]

Si vous ne voulez pas programmer une de ces options écrire "*" en dessous de celui si

Puis à la suite de la configuration écrire: bash /"Chemin du script"/ /"chemin de la liste d'adresse"/ ""Nom du fichier html a crée"" ""Adresse mail utilisé pour recevoir le résultat"

--Fin de la configuration--

------Auteur-----

Auteur : AntoninC
