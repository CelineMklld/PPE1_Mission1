<?php
/** 
 * Script du controleur "Suivre le paiement d'une fiche de frais"
 * @category  PPE
 * @package   GSB
 * @author : Céline Moukalled
 */


$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);


switch ($action) {

	case 'suiviPaiementFrais':
		break;


	case 'afficherSuiviPaiementFrais' :
		$idVisiteurRecherche = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_STRING);
		$result = $pdo->getFichesFraisVAetRB($idVisiteurRecherche);
		$display = true;
	    break;


	case 'afficherFiche' : 
		$moisRecherche = filter_input(INPUT_GET, 'Mois', FILTER_SANITIZE_STRING);
		$idVisiteurRecherche = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_STRING);

		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteurRecherche, $moisRecherche);
		$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteurRecherche, $moisRecherche);
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteurRecherche, $moisRecherche);

		$displayInfosFiche = true;
		$display = true;
		$result = $pdo->getFichesFraisVAetRB($idVisiteurRecherche);

		$numMois = substr($moisRecherche, 4, 2);
		$numAnnee = substr($moisRecherche, 0, 4);

		$libEtat = $lesInfosFicheFrais['libEtat'];
		$montantValide = $lesInfosFicheFrais['montantValide'];
 	    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
	  	$dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);


	  	$estRemboursee = ($lesInfosFicheFrais[0]=='RB');
	    break;


	case 'majFicheFrais':
		$mois = filter_input(INPUT_POST, 'moisSel', FILTER_SANITIZE_STRING);
		$idVisiteur = filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_STRING);

		$pdo->majEtatFicheFrais($idVisiteur, $mois, 'RB');

		header('location: index.php?uc=suiviPaiementFrais&action=afficherFiche&Mois='.$mois.'&idVisiteur='.$idVisiteur.'');
		exit;
		break;


}
require 'vues/v_suiviPaiementFrais.php';
?>