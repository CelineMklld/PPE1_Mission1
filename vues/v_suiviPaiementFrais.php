<?php
/** 
 * Script de la vue "Suivre le paiement d'une fiche de frais"
 * @category  PPE
 * @package   GSB
 * @author : Céline Moukalled
 */
?>


<!DOCTYPE html>
<html>

<head>
	<title></title>
	<meta charset="utf-8"/> <!--- meta = balise qui définit tout ce qui n'est pas visible sur la page --->
	<link rel="stylesheet" type="text/css" href="../styles/style.css"> <!--- link = lier des fichiers externes à la page (ex : css) --->
	<h1> Suivre le paiement des fiches de frais <small> - <?php if ($_SESSION['estComptable']) {
		echo "Comptable";
	} 
	else {echo "Visiteur";} ?> :

	<?php 
	echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']
	?></small>
</h1>
</head>



<!--- Sélection du visiteur --->
<body>
	
	<div class="column">
		<div class="row">
			<form action="index.php?uc=suiviPaiementFrais&action=afficherSuiviPaiementFrais" name='selUserForm' method="POST">
				<div class="col-md-4">
					<div class="form-group">
						Choisir le visiteur : 
						<select id="visiteur" class="form-control" name="idVisiteur">
							<?php 

							$requete = $pdo->getVisiteurs();
							$requete->execute();
							while ($data=$requete->fetch()) {
								?>
								<option value="<?php echo $data['id']; ?>" 
									<?php if(isset($display)) 
									{
										if($data['id']==$idVisiteurRecherche) {
											echo "selected";
										}
									}

									?> 
									> 
									<?php
									echo $data['prenom']; echo " "; echo $data['nom'];
									?></option>     
									<?php
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<input id="ok" type="submit" value="Valider" class="btn btn-success"  role="button" style="margin-top: 20px;">
						</div>
					</div>
				</form>
			</div>



			<!--- Afficher les sélections de fiches de frais qui ont l'état VA (Validée et mise en paiement) & de fiches de frais qui ont l'état RB (Remboursée) --->
			<div class="" style="display: flex; flex-direction: column; margin-right:450px; margin-left:450px; margin-top:30px; margin-bottom: 30px;">
				<?php  
				if (isset($display)) 
				{
					?>
					<div>

						<table class="table table-bordered table-responsive">
							<tbody>
								<?php  
								foreach ($result as $elt) 
								{

									$mois = substr($elt[0], 4, 5);
									$annee = substr($elt[0], 0, 4);

									echo("<tr><td> <a href='index.php?uc=suiviPaiementFrais&action=afficherFiche&Mois=".$elt[0]."&idVisiteur=".$idVisiteurRecherche."'> Fiche de frais du mois ");
									echo $mois."/".$annee;
									echo("</a></td></tr>");
								}
								?>

							</tbody>
						</table>
					</div>
				</div>
				<?php 
			}



			// Affichage de la fiche sélectionnée
			if(isset($displayInfosFiche))
			{

				?>

				<hr>
				<div class="panel panel-primary">
					<div class="panel-heading">Fiche de frais du mois 
						<?php echo $numMois . '-' . $numAnnee ?> : </div>
						<div class="panel-body">
							<strong><u>Etat :</u></strong> <?php echo $libEtat ?>
							depuis le <?php echo $dateModif ?> <br> 
							<strong><u>Montant validé :</u></strong> <?php echo $montantValide ?>
						</div>
					</div>
					<div class="panel panel-info">
						<div class="panel-heading">Eléments forfaitisés</div>
						<table class="table table-bordered table-responsive">
							<tr>
								<?php
								foreach ($lesFraisForfait as $unFraisForfait) {
									$libelle = $unFraisForfait['libelle']; ?>
									<th> <?php echo htmlspecialchars($libelle) ?></th>
									<?php
								}
								?>
							</tr>
							<tr>
								<?php
								foreach ($lesFraisForfait as $unFraisForfait) {
									$quantite = $unFraisForfait['quantite']; ?>
									<td class="qteForfait"><?php echo $quantite ?> </td>
									<?php
								}
								?>
							</tr>
						</table>
					</div>
					<div class="panel panel-info">
						<div class="panel-heading">Descriptif des éléments hors forfait - 
							<?php echo $nbJustificatifs ?> justificatifs reçus</div>
							<table class="table table-bordered table-responsive">
								<tr>
									<th class="date">Date</th>
									<th class="libelle">Libellé</th>
									<th class='montant'>Montant</th>                
								</tr>
								<?php
								foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
									$date = $unFraisHorsForfait['date'];
									$libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
									$montant = $unFraisHorsForfait['montant']; ?>
									<tr>
										<td><?php echo $date ?></td>
										<td><?php echo $libelle ?></td>
										<td><?php echo $montant ?></td>
									</tr>
									<?php
								}
								?>
							</table>
						</div>


						<hr>

						<!--- Bouton "Fiche de frais remboursée" --->
						<div class="" style="display: flex; flex-direction: column; margin-right:450px; margin-left:450px; margin-top:30px; margin-bottom: 30px;">
							<form action="index.php?uc=suiviPaiementFrais&action=majFicheFrais" method="POST">

								<input type="hidden" name="idUser" value="<?php if(isset($idVisiteurRecherche)){
									echo $idVisiteurRecherche;
									} else {echo $idUtilisateurRecherche;}
									?>">
									<input type="hidden" name="moisSel" value="<?php if(isset($moisRecherche)){
										echo $moisRecherche;
									} 
									?>">

									<input id="ok" type="submit" value=" ✓ Fiche de frais rembours&eacute;e " class="btn btn-primary btn-lg" <?php  

									if ($estRemboursee) {
										echo "disabled";
									} ?>


									>
								</form>
							</div>

							<?php
						}
						?>
					</body>
					</html>
