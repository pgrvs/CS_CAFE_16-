<?php

use App\Vue\Vue_RGPD;
use App\Vue\Vue_Structure_BasDePage;
use App\Vue\Vue_Structure_Entete;

$Vue->setEntete(new Vue_Structure_Entete());
switch ($action) {

    case "AccepterRGPD":
        break;
    case "RefuserRGPD":
        session_destroy();
        unset($_SESSION);
        $Vue->setEntete(new Vue_Structure_Entete());
        $Vue->addToCorps(new Vue_Connexion_Formulaire_client());
        break;

    case "AfficherRGPD":
    default:
        $Vue->addToCorps(composant: new Vue_RGPD());
        break;
}
$Vue->setBasDePage(new Vue_Structure_BasDePage());