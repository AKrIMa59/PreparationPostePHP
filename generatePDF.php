<?php

require 'fpdf/pdf.php';
require 'lib/glpiQuery.php';

$refTicket = $_POST['refTicket'];
$oldHostname = $_POST['oldHostname'];
$hostname = $_POST['hostname'];
$name = $_POST['name'];
$service = $_POST['service'];
$tel = $_POST['telephone'];
$technician = $_POST['technician'];
$date_du_jour = date('d-m-Y'); 
$typePoste = $_POST['typePoste'];
$typeUtilisation = $_POST['typeUtilisation'];
$imprimantes = $_POST['imprimante'];
if (!empty($_POST['logicielSTD'])) {
    $logiciel = $_POST['logicielSTD'];
}
$otherlogiciel = $_POST['logicielSpec'];
$recuperationDonnee = $_POST['recuperationDonnee'];
$procdeg = $_POST['procdeg'];
$accesVPN = $_POST['vpn_access'];
$stateHardware = $_POST['stateHardware'];
$commentaire = $_POST['commentaire'];
$stepNumber = 1;
$stepNumberTXT = 1;

$convergencePresence = false;

function fileCreationInfo($file)
{
    fwrite($file, "Référence du ticket : " . $GLOBALS['refTicket'] ."\r\n");
    fwrite($file, "##############################################\r\n");
    //Write the user informations
    fwrite($file, "Nom du demandeur : " . $GLOBALS['name'] ."\r\n");
    fwrite($file, "Service : " . $GLOBALS['service'] ."\r\n");
    fwrite($file, "Téléphone : " . $GLOBALS['tel'] ."\r\n");
    fwrite($file, "##############################################\r\n");
    //Write the computer informations
    fwrite($file, "Type de poste : " . $GLOBALS['typePoste'] ."\r\n");
    fwrite($file, "Type d'utilisation : " . $GLOBALS['typeUtilisation'] ."\r\n");
    fwrite($file, "Imprimantes : " . $GLOBALS['imprimantes'] ."\r\n");
    fwrite($file, "Logiciels :\r\n");
    if (!empty($GLOBALS['logiciel'])) {
        foreach ($GLOBALS['logiciel'] as $logiciel) {
            fwrite($file, " - " . $logiciel ."\r\n");
        }
    }
    fwrite($file, "Autres logiciels : " . $GLOBALS['otherlogiciel'] ."\r\n");
    fwrite($file, "Récupération des données : " . $GLOBALS['recuperationDonnee'] ."\r\n");
    fwrite($file, "Procédure dégradé : " . $GLOBALS['procdeg'] ."\r\n");
    fwrite($file, "Commentaire : " . $GLOBALS['commentaire'] ."\r\n");
    fwrite($file, "##############################################\r\n");
    fclose($file);
}

function fileCreationProcedure($file)
{
    //Write procedure
    fwrite($file, "PROCEDURE \r\n");
    fwrite($file, "##############################################\r\n");
    fwrite($file, "Etape " . $GLOBALS['stepNumberTXT'] . " : Installation de l'image MECM\r\n");
    $GLOBALS['stepNumberTXT']++;
    if (!empty($GLOBALS['logiciel'])) {
        foreach ($GLOBALS['logiciel'] as $key => $value) {
            switch ($value) {
                case 'Convergence':
                    if ($GLOBALS['typeUtilisation'] == "SEDEN") {
                        fwrite($file, " - XXXXXX = TRUE\r\n");
                    }
                    $GLOBALS['convergencePresence'] = true;
                    break;
                case 'Meva':
                    fwrite($file , " - XXXXXX = TRUE\r\n");
                    break;
                case 'speechTranscribe':
                    fwrite($file , " - XXXXXX = TRUE\r\n");
                    break;
                case 'speechDictate':
                    fwrite($file , " - XXXXXX = TRUE\r\n");
                    break;
                case 'dictDicteur':
                    fwrite($file , " - XXXXXX = TRUE\r\n");
                    break;
                case 'dictTranscripteur':
                    fwrite($file , " - XXXXXX = TRUE\r\n");
                    break;
                case 'Synapse':
                    fwrite($file , " - XXXXXX = TRUE\r\n");
                    break;
                default:
                    fwrite($file , " - ERROR : " . $value . " is not a valid software\r\n");
                    break;
            }
        }
    }

    fwrite($file, "##############################################\r\n");
    fwrite($file, "Etape " . $GLOBALS['stepNumberTXT'] . " : Paramétrage Active Directory et SSO\r\n");
    fwrite($file, 'Ajouter le poste dans le groupe "XXXXXX'. $GLOBALS['typeUtilisation'] . '"' . "\r\n");
    fwrite($file, "Installation du SSO " . $GLOBALS['typeUtilisation'] ."\r\n");
    $GLOBALS['stepNumberTXT']++;

    
    if(!empty($GLOBALS['otherlogiciel'])){
        fwrite($file, "##############################################\r\n");
        fwrite($file, "Etape " . $GLOBALS['stepNumberTXT'] . " : Installation des logiciels spécifiques\r\n");
        fwrite($file, "Installation des logiciels suivants : " . $GLOBALS['otherlogiciel'] ."\r\n");
        $GLOBALS['stepNumberTXT']++;
    }

    if(!empty($GLOBALS['imprimantes'])) {
        fwrite($file, "##############################################\r\n");
        fwrite($file, "Etape " . $GLOBALS['stepNumberTXT'] . " : Installation des imprimantes\r\n");
        fwrite($file, "Installation des imprimantes suivantes : " . $GLOBALS['imprimantes'] ."\r\n");
        $GLOBALS['stepNumberTXT']++;
    }

    if($GLOBALS['typePoste'] == "Portable") {
        fwrite($file, "##############################################\r\n");
        fwrite($file, "Etape " . $GLOBALS['stepNumberTXT'] . " : Paramétrage du Wifi\r\n");
        fwrite($file, "Paramétrage du Wifi XXXXXX\r\n");
        $GLOBALS['stepNumberTXT']++;
    }

    if($GLOBALS['convergencePresence']) {
        fwrite($file, "##############################################\r\n");
        fwrite($file, "Etape " . $GLOBALS['stepNumberTXT'] . " : Paramétrage de Convergence\r\n");
        fwrite($file, "Paramétrage de l'imprimante sur Convergence\r\n");
        fwrite($file, "Ajout du nom du poste dans STATION\r\n");
        if ($GLOBALS['typeUtilisation'] == "FUS") {
            fwrite($file, "Creation du fichier de config imprimante dans XXXXXX\r\n");
            fwrite($file, "Raccourci Convergence avec le serveur du service \"" . $GLOBALS['service'] . "\"\r\n");
        }
        $GLOBALS['stepNumberTXT']++;
    }

    if($GLOBALS['typeUtilisation'] == "FUS" && $GLOBALS['typePoste'] == "Portable") {
        fwrite($file, "##############################################\r\n");
        fwrite($file, "Etape " . $GLOBALS['stepNumberTXT'] . " : Paramétrage spécifique pour les portables FUS\r\n");
        fwrite($file, "Désactivation de la veille\r\n");
        $GLOBALS['stepNumberTXT']++;
    }

    if($GLOBALS['recuperationDonnee'] == "Oui") {
        fwrite($file, "##############################################\r\n");
        fwrite($file, "Etape " . $GLOBALS['stepNumberTXT'] . " : Récupération des données\r\n");
        fwrite($file, "Récupération des données sur l'ancien poste\r\n");
        $GLOBALS['stepNumberTXT']++;
    }

    if($GLOBALS['procdeg'] == "oui") {
        fwrite($file, "##############################################\r\n");
        fwrite($file, "Etape " . $GLOBALS['stepNumberTXT'] . " : Procédure dégradé\r\n");
        fwrite($file, "Création du compte local XXXXXX\r\n");
        fwrite($file, "Ajout de l'imprimante en connexion USB\r\n");
        fwrite($file, "Création du Dossier XXXXXX à la racine de C:\r\n");
        fwrite($file, "Création du raccourci vers le dossier XXXXXX sur le bureau\r\n");
        $GLOBALS['stepNumberTXT']++;
    }
}

function fileCreationVerifications($file) {
    fwrite($file,"VERIFICATION ET INSTALLATION SUR SITE\r\n");
    fwrite($file,"##############################################\r\n");
    fwrite($file,"Test d'ouverture de session utilisateur\r\n");
    fwrite($file,"Test d'impression via Windows\r\n");
    if ($GLOBALS['typeUtilisation'] == "SEDEN") {
        fwrite($file, "Test Ouverture OUTLOOK\r\n");
    }
    if ($GLOBALS['procdeg'] == "oui") {
        fwrite($file, "Test Ouverture Session degrad\r\n");
    }
    if ($GLOBALS['convergencePresence']) {
        fwrite($file,"Test d'ouverture et d'utilisation de Convergence\r\n");
        fwrite($file, "Test d'impression via Pastel\r\n");
    }

}

//Generation du fichier texte

if (!is_dir('data/' . $hostname)){
    mkdir('data/' . $hostname) or die('Erreur lors de la création du dossier');
}
//Create the file with the informations general
if (file_exists('data/' . $hostname . '/' . $hostname . '-info' . '.txt')) {
    unlink('data/' . $hostname . '/' . $hostname . '-info' . '.txt');
    $fileInfo = fopen('data/' . $hostname . '/' . $hostname . '-info' . '.txt', 'w');
    fileCreationInfo($fileInfo);
} else {
    $fileInfo = fopen('data/' . $hostname . '/' . $hostname . '-info' . '.txt', 'w');
    fileCreationInfo($fileInfo);
}

//Create the file with the procedure
if (file_exists('data/' . $hostname . '/' . $hostname . '-procedure' . '.txt')) {
    unlink('data/' . $hostname . '/' . $hostname . '-procedure' . '.txt');
    $fileProcedure = fopen('data/' . $hostname . '/' . $hostname . '-procedure' . '.txt', 'w');
    fileCreationProcedure($fileProcedure);
} else {
    $fileProcedure = fopen('data/' . $hostname . '/' . $hostname . '-procedure' . '.txt', 'w');
    fileCreationProcedure($fileProcedure);
}

//Create the file with the verifications
if (file_exists('data/' . $hostname . '/' . $hostname . '-verifications' . '.txt')) {
    unlink('data/' . $hostname . '/' . $hostname . '-verifications' . '.txt');
    $fileVerifications = fopen('data/' . $hostname . '/' . $hostname . '-verifications' . '.txt', 'w');
    fileCreationVerifications($fileVerifications);
} else {
    $fileVerifications = fopen('data/' . $hostname . '/' . $hostname . '-verifications' . '.txt', 'w');
    fileCreationVerifications($fileVerifications);
}


//Generation Tache GLPI

$glpi = New glpiQry();
$ticket = $glpi->GetTicketInfo($refTicket);
if ($ticket) {
    $contentTaskInfo = $glpi->escapeString(file_get_contents('data/' . $hostname . '/' . $hostname . '-info' . '.txt'));
    $glpi->InsertTaskSimple($ticket, $contentTaskInfo, 0);
    $contentTaskProc = $glpi->escapeString(file_get_contents('data/' . $hostname . '/' . $hostname . '-procedure' . '.txt'));
    $glpi->InsertTaskSimple($ticket, $contentTaskProc , 3600);
    $contentTaskVerif = $glpi->escapeString(file_get_contents('data/' . $hostname . '/' . $hostname . '-verifications' . '.txt'));
    $glpi->InsertTaskSimple($ticket, $contentTaskVerif, 1800);
}

//Génération du PDF

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->headerGHSC("ghsc.jpg", "Fiche de préparation du poste " . $hostname);

//Section utilisateur
$pdf->sectionTitle("Information Utilisateur", 10, 40);
$pdf->sectionContentElement("Nom de l'utilisateur :", $name);
$pdf->sectionContentElement("Service :", $service);
$pdf->sectionContentElement("Téléphone :", $tel);
$pdf->sectionContentElement("Technicien :", $technician);
$pdf->sectionContentElement("Référence du ticket :", $refTicket);
$pdf->sectionContentElement("Date de préparation :", $date_du_jour);

//Section matériel
$pdf->sectionTitle("Information Poste", 110, 40);
if ($oldHostname != NULL) {
    $pdf->sectionContentElement("Ancien Hostname:", $oldHostname);
}
$pdf->sectionContentElement("Hostname :", $hostname);
$pdf->sectionContentElement("Type de poste :", $typePoste);
$pdf->sectionContentElement("Type d'utilisation :", $typeUtilisation);
$pdf->sectionContentElement("Imprimantes :", $imprimantes);
if (!empty($otherlogiciel)) {
$pdf->sectionContentTextArea("Logiciels supplémentaires :", $otherlogiciel);
}
$pdf->sectionContentElement("Données :", $recuperationDonnee);
$pdf->sectionContentElement("Accès via VPN :", $accesVPN);
$pdf->sectionContentElement("Etat du poste :", $stateHardware);
$pdf->sectionContentTextArea("Commentaire :", $commentaire);


//Generation de la procédure
$pdf->stepTitleH1("Etape " . $stepNumber . " : Installation de l'image MECM", 10,$pdf->getPDF_Y()+3);
$pdf->stepText("Installer l'image MECM sur le poste. Pour cela, il faut selectionner l'image \"MASTER\"");
$pdf->stepText("Une fois l'image selectionné, il faut cliquer sur \"Next\" et définir les paramètres suivants :");
//Définition du nom du poste
$pdf->stepTextWithCheckbox("OSDCOMPUTERNAME = " . $hostname);
$pdf->stepTextWithCheckbox("OFFICE = 2016");
$pdf->stepTextWithCheckbox("LOCALISATION = " . $service);

if ($typeUtilisation == "SEDEN") {
    $pdf->stepTextWithCheckbox("SSO = SEDEN");
} else if ($typeUtilisation == "FUS") {
    $pdf->stepTextWithCheckbox("SSO = FUS");
} else {
    $pdf->stepText("Type de poste inconnu");
}

//Définition des logiciels standards à installer
if (!empty($logiciel)) {
    foreach ($logiciel as $key => $value) {
        switch ($value) {
            case 'Convergence':
                if ($typeUtilisation == "SEDEN") {
                    $pdf->stepTextWithCheckbox("CONVERGENCE = TRUE");
                }
                $convergencePresence = true;
                break;
            case 'MEVA':
                $pdf->stepTextWithCheckbox("XXXXXX = XXXXXX");
                break;
            case 'speechTranscribe':
                $pdf->stepTextWithCheckbox("XXXXXX = XXXXXX");
                break;
            case 'speechDictate':
                $pdf->stepTextWithCheckbox("XXXXXX = XXXXXX");
                break;
            case 'dictDicteur':
                $pdf->stepTextWithCheckbox("XXXXXX = XXXXXX");
                break;
            case 'dictTranscripteur':
                $pdf->stepTextWithCheckbox("XXXXXX = XXXXXX");
                break;
            case 'WINREST':
                $pdf->stepTextWithCheckbox("XXXXXX = XXXXXX");
                break;
            default:
                $pdf->stepText("Logiciel par défaut non reconnu");
                break;
        }
    }
}
$pdf->addY(2);
$pdf->stepText("Une fois les paramètres définis, il faut cliquer sur \"Next\" pour lancer l'installation de l'image MECM.");
$stepNumber++;

if (!empty($otherlogiciel)) {
    $pdf->stepTitleH1("Etape " . $stepNumber . " : Ajout des logiciels particuliers", 10,$pdf->getPDF_Y()+5);
    $pdf->stepText("Installer les logiciels suivants :");
    foreach (explode(",",$otherlogiciel) as $key => $value) {
        $pdf->stepTextWithCheckbox($value);
    }
    $stepNumber++;
}

$pdf->stepTitleH1("Etape " . $stepNumber . " : Installation des imprimantes", 10,$pdf->getPDF_Y()+5);
$pdf->stepText("Installer les imprimantes suivantes :");
foreach (explode(",",$imprimantes) as $key => $value) {
    $pdf->stepTextWithCheckbox($value);
}
$stepNumber++;

if ($typePoste == "Portable") {
    $pdf->stepTitleH1("Etape " . $stepNumber . " : Paramétrage du Wifi", 10,$pdf->getPDF_Y()+5);
    $pdf->stepTextWithCheckbox("Paramétrage du Wifi XXXXXX", 120);
    $stepNumber++;
}

if ($convergencePresence == true || $typeUtilisation == "FUS") {
    $pdf->stepTitleH1("Etape " . $stepNumber . " : Paramétrage de Convergence", 10,$pdf->getPDF_Y()+5);
    $pdf->stepTextWithCheckbox("Paramétrage de l'imprimante sur Convergence", 130);
    $pdf->stepTextWithCheckbox("Ajout du nom du poste dans STATION", 130);
    if ($typeUtilisation == "FUS") {
        $pdf->stepTextWithCheckbox("Creation du fichier de config imprimante dans XXXXXX", 130);
        $pdf->stepTextWithCheckbox("Raccourci Convergence avec le serveur du service \"" . $service . "\"", 130);
    }
    $stepNumber++;
}

if ($typePoste == "Portable" && $typeUtilisation == "FUS") {
    $pdf->stepTitleH1("Etape " . $stepNumber . " : Paramétrage spécifique pour les portables FUS", 10,$pdf->getPDF_Y()+5);
    $pdf->stepTextWithCheckbox("Désactivation de la veille", 120);
    $stepNumber++;
}

if ($accesVPN == "oui") {
    $pdf->stepTitleH1("Etape " . $stepNumber . " : Paramètrage pour accès VPN", 10,$pdf->getPDF_Y()+5);
    $pdf->stepTextWithCheckbox("XXXXXX", 120);
    $pdf->stepTextWithCheckbox("XXXXXX", 120);
    $stepNumber++;
}

if ($recuperationDonnee == "oui") {
    $pdf->stepTitleH1("Etape " . $stepNumber . " : Récupération des données", 10,$pdf->getPDF_Y()+5);
    $pdf->stepTextWithCheckbox("Récupération des données sur l'ancien poste", 120);
    $stepNumber++;
}

if ($procdeg == "oui") {
    $pdf->stepTitleH1("Etape " . $stepNumber . " : Procédure dégradée", 10,$pdf->getPDF_Y()+5);
    $pdf->stepTextWithCheckbox("Création du compte local XXXXXX", 120);
    $pdf->stepTextWithCheckbox("Ajout de l'imprimante en connexion USB", 120);
    $pdf->stepTextWithCheckbox("Création du Dossier XXXXXX à la racine de C:", 120);
    $pdf->stepTextWithCheckbox("Création du raccourci XXXXXX sur le bureau", 120);
    $stepNumber++;
}

$pdf->stepTitleH1("Installation du poste : Vérification ", 10,$pdf->getPDF_Y()+5);
$pdf->stepTextWithCheckbox("Test d'ouverture de session utilisateur");
if ($procdeg == "oui") {
    $pdf->stepTextWithCheckbox("Test Ouverture Session degrad");
}
if ($typeUtilisation == "SEDEN") {
    $pdf->stepTextWithCheckbox("Test Ouverture Outlook");
}
$pdf->stepTextWithCheckbox("Test d'impression via Windows");
if ($convergencePresence == true) {
    $pdf->stepTextWithCheckbox("Test d'ouverture et d'utilisation de Convergence");
    $pdf->stepTextWithCheckbox("Test d'impression via Pastel");
}


$pdf->Output("I", "Preparation-". $hostname .".pdf");

