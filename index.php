<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préparation de poste</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="p-2 bg-dark text-white text-center">
        <h1 class="p-1">Préparation de poste</h1>
    </header>
    <div class="container mt-2 form-width">
        <form action="generatePDF.php" method="post">
            <h3 class="text-center">Informations utilisateur</h3>
            <div class="form-group mb-3">
                <label class="form-label" for="nom">Nom du demandeur :</label>
                <input type="text" class="form-control border-dark" id="name" name="name" placeholder="Nom" required>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="service">Service :</label>
                <input type="text" class="form-control border-dark" id="service" name="service" placeholder="Service" required>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="telephone">Téléphone :</label>
                <input type="text" class="form-control border-dark" id="telephone" name="telephone" placeholder="Téléphone" required>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="technician">Technicien :</label>
                <select name="technician" id="technician" class="form-control border-dark" required>
                    <option value="">--Selectionner un technicien--</option>
                    <option value="Luc BOURBIAUX">Luc BOURBIAUX</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="refTicket">Référence du ticket :</label>
                <input type="number" class="form-control border-dark" id="refTicket" name="refTicket" placeholder="Référence du ticket" required>
            </div>
            <h3 class="text-center">Informations matériel</h3>
            <div class="form-group mb-3">
                <label class="form-label" for="oldHostname">Nom d'hôte de l'ancien poste (si remplacement) :</label>
                <input type="text" class="form-control border-dark" id="oldHostname" name="oldHostname" placeholder="Nom d'hôte">
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="hostname">Nom d'hôte du nouveau poste :</label>
                <input type="text" class="form-control border-dark" id="hostname" name="hostname" placeholder="Nom d'hôte" required>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="typePoste">Type de poste :</label>
                <select class="form-select border-dark" id="typePoste" name="typePoste" required>
                    <option value="Bureau">Bureau</option>
                    <option value="Portable">Portable</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="typeUtilisation">Type d'utilisation :</label>
                <select class="form-select border-dark" id="typeUtilisation" name="typeUtilisation" required>
                    <option value="FUS">FUS</option>
                    <option value="SEDEN" selected>SEDEN</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="Imprimante">Imprimante à installer :</label>
                <input type="text" class="form-control border-dark" id="imprimante" name="imprimante" placeholder="SIMPXX,SMFPXX" required>
            </div>
            <!--Case à cocher pour le choix des logiciels standards souhaités-->
            <div class="form-group mb-3">
                <label class="form-label d-block">Logiciels standards :</label>
                <div class="form-check">
                    <input class="form-check-input border-dark" type="checkbox" id="Convergence" name="logicielSTD[]" value="Convergence" checked>
                    <label class="form-check-label">Convergence</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input border-dark" type="checkbox" id="MEVA" name="logicielSTD[]" value="MEVA">
                    <label class="form-check-label">XXXXXX</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input border-dark" type="checkbox" id="WINREST" name="logicielSTD[]" value="WINREST">
                    <label class="form-check-label">XXXXXX</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input border-dark" type="checkbox" id="speechTranscribe" name="logicielSTD[]" value="speechTranscribe">
                    <label class="form-check-label">XXXXXX</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input border-dark" type="checkbox" id="speechDictate" name="logicielSTD[]" value="speechDictate">
                    <label class="form-check-label">XXXXXX</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input border-dark" type="checkbox" id="dictDicteur" name="logicielSTD[]" value="dictDicteur">
                    <label class="form-check-label">XXXXXX</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input border-dark" type="checkbox" id="dictTranscripteur" name="logicielSTD[]" value="dictTranscripteur">
                    <label class="form-check-label">XXXXXX</label>
                </div>
            </div>
            <!--Case à cocher pour le choix des logiciels spécifiques souhaités-->
            <div class="form-group mb-3">
                <label class="form-label">Logiciels spécifiques :</label>
                <input type="text" class="form-control border-dark" id="logicielSpec" name="logicielSpec" placeholder="logiciel1,logiciel2..">
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="recuperationDonnee">Recuperation de données :</label>
                <select class="form-select border-dark" id="recuperationDonnee" name="recuperationDonnee" required>
                    <option value="oui">Oui</option>
                    <option value="non" selected>Non</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="procdeg">Procédure dégradée :</label>
                <select class="form-select border-dark" id="procdeg" name="procdeg" required>
                    <option value="oui">Oui</option>
                    <option value="non" selected>Non</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="commentaire">Commentaire :</label>
                <textarea class="form-control border-dark" id="commentaire" name="commentaire" rows="3"></textarea>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="stateHardware">Etat du poste</label>
                <select class="form-select border-dark" id="stateHardware" name="stateHardware" required>
                    <option value="Neuf" selected>Neuf</option>
                    <option value="Reconditionné">Reconditionné</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" for="vpn_access">Accès VPN</label>
                <select class="form-select border-dark" id="vpn_access" name="vpn_access" required>
                    <option value="oui">Oui</option>
                    <option value="non" selected>Non</option>
                </select>
            </div>
            <div class="text-center mb-3">
                <button type="submit" class="btn btn-success">Générer le PDF</button>
            </div>
        </form>
    </div>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>