<?php

/**
 * Contrôleur gérant la connexion à la page administration.
 * En cas de succès, il affiche uniquement le menu d'administration.
 */

use App\Modele\Modele_Utilisateur;
use App\Vue\Vue_AfficherMessage;
use App\Vue\Vue_Menu_Administration;
use App\Vue\Vue_Connexion_Formulaire_administration;
use App\Vue\Vue_Structure_BasDePage;
use App\Vue\Vue_Structure_Entete;


//Ce contrôleur gère le formulaire de connexion pour les utilisateurs de l'entreprise
$Vue->setEntete(new Vue_Structure_Entete());
switch ($action) {
    case "Se connecter" :

        if (isset($_REQUEST["login"]) and isset($_REQUEST["password"])) {//Si tous les paramètres du formulaire sont bons

            //Vérification du mot de passe
            $utilisateur = Modele_Utilisateur::Utilisateur_Select_ParLogin($_REQUEST["login"]);
            // Connexion possible si l'utilisateur existe et qu'il n'est pas désactivé
            if ($utilisateur != null and $utilisateur["desactiver"] == 0) {
                if (password_verify($_REQUEST["password"], $utilisateur["motDePasse"]))
                {//le mot de passe est associable à ce Hash

                    $_SESSION["idUtilisateur"] = $utilisateur["idUtilisateur"];
                    $_SESSION["niveauAutorisation"] = $utilisateur["niveauAutorisation"];


                    // if RGPD accepté
                    //A inclure que si RGPD acceptée !
                    if (($utilisateur["aAccepteRGPD"] == 0 || $utilisateur["aAccepteRGPD"] == "0") && $utilisateur['niveauAutorisation'] != 1){
                        include "./Controleur/Controleur_RGPD.php";
                    } else {
                        $Vue->setMenu(new Vue_Menu_Administration($_SESSION["niveauAutorisation"]));
                    }

                } else {//mot de passe pas bon
                    $msgError = "Mot de passe erroné";
                    $Vue->addToCorps(new Vue_Connexion_Formulaire_administration($msgError));
                }
            } // Message si l'utilisateur est désactivé : il ne pourra pas se connecter
            elseif ($utilisateur != null and $utilisateur["desactiver"] != 0) {
                $msgError = "Vous n'avez pas l'autorisation nécessaire pour accéder au site";

                $Vue->addToCorps(new Vue_Connexion_Formulaire_administration($msgError));
            } else {
                $msgError = "Login non trouvé";
                $Vue->addToCorps(new Vue_Connexion_Formulaire_administration($msgError));
            }
        } else {   //Il y a un oubli quelque part !

                $msgError = "Vous devez saisir toutes les informations";

            $Vue->addToCorps(new Vue_Connexion_Formulaire_administration($msgError));
        }
        break;
    default :
        $Vue->addToCorps(new Vue_Connexion_Formulaire_administration());
}

$Vue->setBasDePage(new Vue_Structure_BasDePage());
