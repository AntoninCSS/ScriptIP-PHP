#!/usr/bin/php -q
<?php
#one line to give the program's name and an idea of what it does.
#Copyright (C) yyyy  name of author

#This program is free software; you can redistribute it and/or
#modify it under the terms of the GNU General Public License
#as published by the Free Software Foundation; either version 2
#of the License, or (at your option) any later version.

#This program is distributed in the hope that it will be useful,
#but WITHOUT ANY WARRANTY; without even the implied warranty of
#MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#GNU General Public License for more details.

#You should have received a copy of the GNU General Public License
#along with this program; if not, write to the Free Software
#Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

$arg1=$argv[1]; //argument 1
$arg2=$argv[2]; //argument 2

//Déclaration de la date FR (Utilisée dans le script et L'HTML)
setlocale(LC_TIME, 'fr','fr_FR','fr_FR@euro','fr_FR.utf8','fr-FR','fra');
date_default_timezone_set('Europe/Paris');
$date1= utf8_decode(strftime('%A %d %B, %H:%M'));


function Test_argu($arg1,$arg2)//vérification de la précenses des 2 arguments et le 1 argument existe bien
{
  if(is_null($arg1) || is_null ($arg2) ) // Si le fichier text (arg1) est nul ou le fichier (arg2) est Null
   {

    echo "Syntaxe : ScripIP.php \"fichier_IP\" \"fichier_html_a_générer\" \n";
    return 0; 

   }
   elseif((file_exists($arg1) == false)&& ($arg1 != "-db"))  // Si le fichier (arg1) n'existe pas (n'est pas executable) ou le -db n'est pas présent
   {

    echo "\nLe fichier text contenant les IP n'existe pas \n";
    return 0;

   }
   else
   {

    if($arg1 == "-db")
    {

     return 2; 

    }

    else
    {

     return 1;
    
    }
   }
} 

function Test_IP($Entrer1, $mod) // Ping les IP et renvoie leur résulat
{
 global $date1;
 $htmladd="";
 echo "---------- > TEST DU RESEAU DE L'ENTREPRISE : \"SIO02-SLAM\" <----------\n"; // En-tete du script
 echo "---------- > " ;
 echo $date1; //Affiche la Date
 echo " <----------\n";

 $ip_correct = $Entrer1[0];// Prend les ip correct dans une variable
 $ip_incorrect = $Entrer1[1];// Prend les ip non correct dans une variable

 if($mod = "-db")
 {

  $database = new PDO('mysql:host=localhost;dbname=DB_IP;charset=utf8','IP','IPMDP'); // conexion a la BD

 }

 foreach($ip_correct as $IP) // Fait une boucle pour chaque ligne du tableau 
 {

  $IP = preg_replace("/\n/","", $IP); //Permet de retirer le saut de ligne de la variable $IP
  exec("ping -c 1 -w 1 " .$IP,$output,$result); //output = sortie de comande ,$result aprouve si la comannde a une erreur ou non , si elle a une erreur elle retourne 1 sinon 0 

  if($result == 0) // Si pas d'erreur alors [ OK ]

  {

   if($mod == "-db")

   {

       $DBATester = $database->prepare("UPDATE IP SET A_TESTER = TRUE WHERE ADR_IP = '$IP'"); // Pemet de modifier l'onglet "A_tester" dans la table IP
       $DBATester->execute();

   } 

      echo "Test du ping $IP : [ OK ]\n";
      $htmladd=$htmladd ."<tr> <td> ".$IP." </td>  <td style=\"background-color: lightgreen;\"> [ OK ] </td>  </tr>";

  } 

  else // Sinon [ NOT OK ]

  {

      if($mod == "-db")

   {

    $DBATester = $database->prepare("UPDATE IP SET A_TESTER = FALSE WHERE ADR_IP = '$IP'"); // Permet de modifier l'onglet "A_tester" dans la table IP
    $DBATester->execute();

    $DBCountNotOK = $database->prepare("UPDATE IP SET CPT_NOT_OK = CPT_NOT_OK + 1 WHERE ADR_IP = '$IP'"); // permet de modifier l'onglet "CPT_NOt_OK"
    $DBCountNotOK->execute();

   } 

   echo "Test du ping $IP : [ NOT OK ]\n";
   $htmladd=$htmladd ."<tr> <td> ".$IP." </td> <td style=\"background-color: LightCoral;\"> [ ERREUR ] </td> </tr>";

  }

 }

  foreach($ip_incorrect as $IP)
  {

  $IP = preg_replace("/\n/","", $IP); //Permet de retirer le saut de ligne de la variable $IP
  echo " L'IP $IP : [ NOT VALID ]\n";
  $htmladd=$htmladd ."<tr> <td> ".$IP." </td> <td style=\"background-color: DarkOrange;\"> [ NOT VALID ] </td> </tr>";

 }
 
  echo "----------> Fin Du Test Reseaux <----------\n";

  $DBDate = $database->prepare("UPDATE IP SET DATE_DERNIER_CTRL = CURDATE()"); //Permet de modifier L'onglet "DATE_DERNIER_CTRL"
  $DBDate->execute();

 return $htmladd;
 
}


function IPV_4($IP1,$mod) //Permet de vérifier la bonne syntax d'un adreese ip via une expression régulière 
{

  $mp2 = array();
  $mp3 = array();

  if($mod == "txt") // Vérifie le mode de fonction soit avec .txt ou la DB
  {
   $fg = file($IP1); // Place dans un tableau les ligne du fichier source IP
  }

  else

  {
   $fg = $IP1;
  }

  foreach($fg as $IP) // Fait une boucle pour chaque ligne du tableau 
   {

     if(preg_match('%^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$%',$IP)) // Pour chaque IP Vérifie via l'expression régulière leur confomitée en tant que IPV4 ([0-255].[0-255].[0.255].[0.255])
      {

       array_push($mp2,$IP);  //Retourne les IP valide sous forme de tableau (array).
      
      }
      else
      {

       array_push($mp3,$IP); //Retourne les IP non valide sous forme de tableau (array)

      }
   }
   $mp4 = array($mp2,$mp3); // concatene les ip bonne ($mp2) et mauvaise ($mp3)
   return $mp4;
}


function HTML($arg2,$htmladd) // création du mail en format HTML 
{
 global $date1;

 //Destinataire
 $to = "exemple@exemple.com";

 //sujet du mail
 $Subject = utf8_decode("Surveillance réseau SIO2-SLAM : Erreur(s) détectée(s)");

 //Message au format HTML
 $message =     "<!DOCTYPE HTML>
                 <hml lang=\"FR\">
                 <head>
                  <meta charset=\"UTF-8\">
                   <style>
                      body{
                           background-color: aliceblue;
                           margin : auto;
                           text-align: center;
                           padding: 15px;
                          }
                     table{
                            border: solid 3px black;
                            margin: auto;
                            background-color: aliceblue;
                            height: 100px;
                            width: 20%;
                            border-collapse: collapse;
                            box-shadow: 6px 3px 3px #A4A4A4;
                          }
                        td{
                            text-align: center;
                            border: solid 2px black;
                            background-color: #ecebef;
                            width: 100px;
                            border-collapse: collapse;
                          }
                        h2{
                            background-color: darkseagreen;
                            text-align: center;
                          }
                        th{
                            background-color: #A4A4A4;
                            border: solid 2px black;
                            width: 100px;
                            border-collapse: collapse;
                          }
                        h3{
                            text-align:center;
                            background-color: darkseagreen;
                          }
                    </style>
                 </head>
                 <body>
                  <h2> Script Planifiée : TEST DU RESEAU DE L'ENTREPRISE : \"SIO2-SLAM\" </h2>
                  <h3> ".$date1." </h3>
                 <table>
                    <tr>
                      <th> Adresse IP </th>
                      <th>Status</th>
                    </tr>
                     ".$htmladd."
                    </table>
                    <h2> FIN DU TEST RESEAU </h2>
                    </body>
                    </html>
                  ";


//L'en-tête Mail "Content-Type" doit être défini :
$headers = "MIME-Version: 1.0 \r\n";
$headers .= "Content-type:text/html; charset=\"utf-8\" \r\n";

//envoi du mail

if(mail($to, $Subject, $message, $headers)) echo "\n=> Couriel envoyé !!\n";
else echo "\n=> Erreur De L'envoie Du Couriel!!\n";


#echo($message);
#echo $fichierHTMl < $message ;
#$fichierHTML = fopen($arg2,"rw") or die("erreur");
#fwrite($fichierHTML, $message);
#fclose($fichierHTML);
file_put_contents($arg2, $message);//permet de crée un fichier
}

function DB_1() // conecion a la base de données 
{
$database = new PDO('mysql:host=localhost;dbname=DB_IP;charset=utf8','IP','IPMDP');
$tableIP1 = $database->prepare('SELECT ADR_IP FROM IP');
$tableIP1->execute();
$tableIP = $tableIP1->fetchAll(); // Translate les IPS de la DB sous forme de tabelau     
$retour = array();
foreach($tableIP as $IP)
 {
  array_push($retour,$IP["ADR_IP"]); // retire les doublons des IP dans un tableau propre 
 }
return $retour;
}



function finale($arg1,$arg2) //Fonction finale qui assemble l'ensemble des fonctions crée pour finaliser le script
{
  $mod=Test_argu($arg1,$arg2); // Si return 0 = pas d'argument présent , 1 = fichier txt présent ou 2 = DB présent; 
  if($mod > 0 )
  {
   if($mod == 1) 
   { 
    $tableau_ip_verif = IPV_4($arg1, "txt");
    $tableauHTMl = Test_IP($tableau_ip_verif, "txt"); 
    HTML($arg2,$tableauHTMl);
   }
   else
   {
    echo "test";
    $table_ip_pure = DB_1();
    $tableau_ip_verif = IPV_4($table_ip_pure, "-db");
    $tableauHTMl = Test_IP($tableau_ip_verif, "-db"); 
    HTML($arg2,$tableauHTMl);
    
   }
  }
}

finale($arg1,$arg2); // lance le script finale en assemblant toute les fonction

?>
