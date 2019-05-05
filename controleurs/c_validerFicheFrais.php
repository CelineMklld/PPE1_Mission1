<?php
/** 
 * Script du controleur "Valider une fiche de frais"
 * @category  PPE
 * @package   GSB
 * @author : Céline Moukalled
 */


$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);



switch ($action) {


	    case 'validerFrais':
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
        $idVisiteurRecherche = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_STRING);


        $numAnnee = substr($leMois, 3, 4);
        $numMois = substr($leMois, 0, 2);

        $leMoisRecherche = "".$numAnnee.$numMois;

        //$lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
        //$moisASelectionner = $leMois;
        //include 'vues/v_listeMois.php';
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteurRecherche, $leMoisRecherche);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteurRecherche, $leMoisRecherche);

        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteurRecherche, $leMoisRecherche);

        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);

        $estValidee = ($lesInfosFicheFrais[0]=='VA' || $lesInfosFicheFrais[0]=='RB');

	    require 'vues/v_validerFicheFrais.php';
        break;
    

        case 'majFraisForfait':
        $forfaitEtapeEnvoi= filter_input(INPUT_POST, 'forfaitEtape', FILTER_SANITIZE_STRING);
        $fraisKmEnvoi= filter_input(INPUT_POST, 'fraisKm', FILTER_SANITIZE_STRING);
        $nuitHotelEnvoi= filter_input(INPUT_POST, 'nuitHotel', FILTER_SANITIZE_STRING);
		$repasRestaurantEnvoi= filter_input(INPUT_POST, 'repasRestaurant', FILTER_SANITIZE_STRING);

		$idUtilisateurRecherche=filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_STRING);
		$leMoisRecherche = filter_input(INPUT_POST, 'moisSel', FILTER_SANITIZE_STRING);


		$arrayEnvoi = array('ETP' => $forfaitEtapeEnvoi, 'KM'=> $fraisKmEnvoi, 'NUI'=>$nuitHotelEnvoi, 'REP'=>$repasRestaurantEnvoi);
		$pdo->majFraisForfait($idUtilisateurRecherche, $leMoisRecherche, $arrayEnvoi);

		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateurRecherche, $leMoisRecherche);
        $lesFraisForfait = $pdo->getLesFraisForfait($idUtilisateurRecherche, $leMoisRecherche);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUtilisateurRecherche, $leMoisRecherche);

        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];

		require 'vues/v_validerFicheFrais.php';
    	break;


        case 'majFraisHorsForfait':
        if (isset($_POST['submitModif'])) { 

    	$dateFraisHorsForfait= dateFrancaisVersAnglais(filter_input(INPUT_POST, 'dateFraisHorsForfait', FILTER_SANITIZE_STRING));
    	$libelleFraisHorsForfait = filter_input(INPUT_POST, 'libelleFraisHorsForfait', FILTER_SANITIZE_STRING);
    	$montantFraisHorsForfait = filter_input(INPUT_POST, 'montantFraisHorsForfait', FILTER_SANITIZE_STRING);
    	$idLigneFraisHorsForfait = filter_input(INPUT_POST, 'idLigneFraisHorsForfait', FILTER_SANITIZE_STRING);


        $pdo->majFraisHorsForfait($idLigneFraisHorsForfait, $libelleFraisHorsForfait, $dateFraisHorsForfait, $montantFraisHorsForfait);

        } 
        else {

        $libelleFraisHorsForfait = filter_input(INPUT_POST, 'libelleFraisHorsForfait', FILTER_SANITIZE_STRING);
        $idLigneFraisHorsForfait = filter_input(INPUT_POST, 'idLigneFraisHorsForfait', FILTER_SANITIZE_STRING);


        $pdo->refuseFraisHorsForfait($idLigneFraisHorsForfait, $libelleFraisHorsForfait);

        }

        $idUtilisateurRecherche=filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_STRING);
        $leMoisRecherche = filter_input(INPUT_POST, 'moisSel', FILTER_SANITIZE_STRING);

        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateurRecherche, $leMoisRecherche);
        $lesFraisForfait = $pdo->getLesFraisForfait($idUtilisateurRecherche, $leMoisRecherche);

        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUtilisateurRecherche, $leMoisRecherche);

        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];


        require 'vues/v_validerFicheFrais.php';
    	break;



        case 'majNbJustificatifs':

        $idUtilisateurRecherche=filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_STRING);
        $leMoisRecherche = filter_input(INPUT_POST, 'moisSel', FILTER_SANITIZE_STRING);
        $nbJustificatifs= filter_input(INPUT_POST, 'nbjustificatifs', FILTER_SANITIZE_STRING);

        $pdo->majNbJustificatifs($idUtilisateurRecherche, $leMoisRecherche, $nbJustificatifs); 

        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateurRecherche, $leMoisRecherche);
        $lesFraisForfait = $pdo->getLesFraisForfait($idUtilisateurRecherche, $leMoisRecherche);

        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUtilisateurRecherche, $leMoisRecherche);

        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];

        require 'vues/v_validerFicheFrais.php';
        break;


        case 'majFicheFrais':
        
        $montantTotal=0;
        $idUtilisateurRecherche=filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_STRING);
        $leMoisRecherche = filter_input(INPUT_POST, 'moisSel', FILTER_SANITIZE_STRING);


        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idUtilisateurRecherche, $leMoisRecherche);
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) 
        {
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $montant = $unFraisHorsForfait['montant'];

                if (substr($libelle, 0, 6)!='REFUSE') 
                {
                $montantTotal+=$montant;
                }
        }


        $lesFraisForfait = $pdo->getLesFraisForfait($idUtilisateurRecherche, $leMoisRecherche);
        $forfaitEtape = $lesFraisForfait[0][2];
        $fraisKm = $lesFraisForfait[1][2];
        $nuitHotel =  $lesFraisForfait[2][2];
        $repasRestaurant =  $lesFraisForfait[3][2];


        $montantTotal += $forfaitEtape*($pdo->getMontantFraisForfait('ETP'))+$fraisKm*($pdo->getMontantFraisForfait('KM'))+$nuitHotel*($pdo->getMontantFraisForfait('NUI'))+$repasRestaurant*($pdo->getMontantFraisForfait('REP'));

        $pdo->majEtatMontantFicheFrais('VA', $idUtilisateurRecherche, $leMoisRecherche, $montantTotal);


        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idUtilisateurRecherche, $leMoisRecherche);

        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];

        require 'vues/v_validerFicheFrais.php';
        break;
}
?>