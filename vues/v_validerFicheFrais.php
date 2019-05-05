<?php
/** 
 * Script de la vue "Valider une fiche de frais"
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
  <h1> Valider une fiche de frais <small> - <?php if ($_SESSION['estComptable']) {
    echo "Comptable";
  } 
  else {echo "Visiteur";} ?> :

  <?php 
  echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']
  ?></small>
</h1>
</head>



<body>

  <!--- Choix du visiteur et du mois --->
  <div class="column">
    <div class="row">
      <form action="index.php?uc=validerFicheFrais&action=validerFrais" name='selUserForm' method="POST">
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
              <?php
              if (isset($idVisiteurRecherche) && $idVisiteurRecherche == $data['id']) {
                echo "selected";
              }  
              else if (isset($idUtilisateurRecherche) && $idUtilisateurRecherche == $data['id']) {
                echo "selected";
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
          Mois : 
          <select id="lstMois" name="lstMois" class="form-control"> 
            <?php
            for ($i = 1; $i <= 12; $i++) {
             $months[] = date("m/Y", strtotime( date( 'Y-m-01' )." -$i months"));
           }

           foreach ($months as $element) {

            $isDefSel=false;
            if(substr($leMoisRecherche, 0, 4)==substr($element, 3, 4) && substr($leMoisRecherche, 4, 2)==substr($element, 0, 2)) 
            {
              $isDefSel=true; 
            }

            ?>
            <option value="<?php echo $element ?>" <?php if ($isDefSel) {echo "selected";} ?>>
              <?php echo $element; ?>
            </option>
            <?php
          }
          ?>    

        </select>            
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <input id="ok selectUser" type="submit" value="Valider" class="btn btn-success"  role="button" style="margin-top: 20px;">
      </div>

    </div>
  </form> <!--- form = ajouter un formulaire --->
</div>
<hr>



<!--- Exception : pas de fiche de frais pour tel visiteur tel mois --->
<?php
if (!isset($_GET['init'])) {

  if (empty($lesInfosFicheFrais)) {
    echo "<h3 style='text-align:center;'> Pas de fiche de frais pour ce visiteur ce mois. </h3>";
    die;
  }

  if (empty($lesFraisForfait)) {
    echo "<h3 style='text-align:center;'> Pas de fiche de frais pour ce visiteur ce mois. </h3>";
    die;
  }

  if (empty($lesFraisHorsForfait)) {
    echo "<h3 style='text-align:center;'> Pas de fiche de frais pour ce visiteur ce mois. </h3>";
    die;
  }

  // Affichage de la fiche de frais

  $forfaitEtape = $lesFraisForfait[0][2];
  $fraisKm = $lesFraisForfait[1][2];
  $nuitHotel =  $lesFraisForfait[2][2];
  $repasRestaurant =  $lesFraisForfait[3][2];

  ?> 


  <!--- Affichage des éléments forfaitisés --->
  <div>

    <h3>Eléments forfaitisés</h3>

    <form action="index.php?uc=validerFicheFrais&action=majFraisForfait" id="formFraisForfait" method="POST"  class="column col-md-4">

     <label for="forfait">Forfait étape </label>	
     <input type="text" id="forfait" name="forfaitEtape" class="form-control" value="<?php echo $forfaitEtape ?>">

     <label for="fraisKM">Frais Kilométrique</label>
     <input type="text" id="fraisKM" name="fraisKm" class="form-control" value="<?php echo $fraisKm ?>">

     <label for="nuit">Nuitée Hôtel</label>
     <input type="text" id="nuit" name="nuitHotel" class="form-control" value="<?php echo $nuitHotel ?>">

     <label for="repas">Repas Restaurant</label>
     <input type="text" id="repas" name="repasRestaurant" class="form-control" value="<?php echo $repasRestaurant ?>">

     <input type="hidden" name="idUser" value="<?php if(isset($idVisiteurRecherche)){
      echo $idVisiteurRecherche;
      } else {echo $idUtilisateurRecherche;}
      ?>">
      <input type="hidden" name="moisSel" value="<?php if(isset($leMoisRecherche)){
        echo $leMoisRecherche;
      } 
      ?>">
      <div style="margin-top: 10px;">
        <input id="ok" type="submit" value="Corriger" class="btn btn-success" 
        role="button">
        <input id="annuler" type="reset" value="Réinitialiser" class="btn btn-danger" 
        role="button">
      </div>
    </form> 

  </div>
</div>
<hr>


<!--- Affichage des éléments hors forfait --->
<div class="row">
  <div class="panel panel-info">
    <div class="panel-heading">Descriptif des éléments hors forfait</div>
    <table class="table table-bordered table-responsive">
      <thead>
        <tr>
          <th class="date">Date</th>
          <th class="libelle">Libellé</th>  
          <th class="montant">Montant</th>  
          <th class="action">&nbsp;</th> 
        </tr>
      </thead>  
      <tbody>
        <?php
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
          $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
          $date = $unFraisHorsForfait['date'];
          $montant = $unFraisHorsForfait['montant'];
          $id = $unFraisHorsForfait['id']; ?>           
          <tr> <form action="index.php?uc=validerFicheFrais&action=majFraisHorsForfait" method="POST">
            <input type="hidden" name="idLigneFraisHorsForfait" value = "<?php echo $id ;  ?>">
            <input type="hidden" name="idUser" value="<?php if(isset($idVisiteurRecherche)){
              echo $idVisiteurRecherche;
              } else {echo $idUtilisateurRecherche;}
              ?>">
              <input type="hidden" name="moisSel" value="<?php if(isset($leMoisRecherche)){
                echo $leMoisRecherche;
              } 
              ?>">
              <td><input type="text" name="dateFraisHorsForfait" class="form-control" value="<?php echo $date ?>"></td>
              <td><input type="text" name="libelleFraisHorsForfait" class="form-control" value="<?php echo $libelle ?>"></td>
              <td><input type="text" name="montantFraisHorsForfait" class="form-control" value="<?php echo $montant ?>"></td>
              <td><input id="ok" type="submit" value="Corriger" class="btn btn-success" 
                name="submitModif">
                <input id="annuler" type="reset" value="Réinitialiser" class="btn btn-danger" 
                role="button">
                <input type="submit" value="Refuser" class="btn btn-danger" name="submitRefuser"></td>
              </form>
            </tr>
            <?php
          }
          ?>
        </tbody>  
      </table>
    </div>
    <div style="display: flex; flex-direction: row; margin-bottom: 30px;">
      <form action="index.php?uc=validerFicheFrais&action=majNbJustificatifs" method="POST" class="column col-md-4">
        <div style="display: flex; flex-direction: row;">
         <input type="hidden" name="idUser" value="<?php if(isset($idVisiteurRecherche)){
          echo $idVisiteurRecherche;
          } else {echo $idUtilisateurRecherche;}
          ?>">
          <input type="hidden" name="moisSel" value="<?php if(isset($leMoisRecherche)){
            echo $leMoisRecherche;
          } 
          ?>">
          <label for="nbJustInp" style="margin-right: 10px;">Nombre de justificatifs : </label>
          <input type="text" id="nbJustInp" name="nbjustificatifs" class="form-control" style="width: 10%" value="<?php echo $nbJustificatifs ?>">
        </div>
        <div class="row" style="margin-top:20px;">
          <input id="ok" type="submit" value="Valider" class="btn btn-success"  role="button">
          <input id="annuler" type="reset" value="Réinitialiser" class="btn btn-danger" 
          role="button">
        </div>
      </form>
    </div>

  </div>

  <hr>

  <div class="" style="display: flex; flex-direction: column; margin-right:450px; margin-left:450px; margin-top:30px; margin-bottom: 30px;">
    <form action="index.php?uc=validerFicheFrais&action=majFicheFrais" method="POST">

     <input type="hidden" name="idUser" value="<?php if(isset($idVisiteurRecherche)){
      echo $idVisiteurRecherche;
      } else {echo $idUtilisateurRecherche;}
      ?>">
      <input type="hidden" name="moisSel" value="<?php if(isset($leMoisRecherche)){
        echo $leMoisRecherche;
      } 
      ?>">

      <!--- Bouton de validation de la fiche de frais --->
      <input id="ok" type="submit" value=" ✓ Valider la fiche de frais" class="btn btn-primary btn-lg"  <?php  
      if ($estValidee) {
        echo "disabled";
      } ?>
      >
    </form>
  </div>
  <?php 

  if($estValidee) {
    ?>
    <script type="text/javascript">
      var totalInput = document.getElementsByTagName("input");
      for(var i = 0; i<totalInput.length; i++) {
        var currentInput = totalInput[i];
        if ((currentInput.type=='text' || currentInput.type=='submit' || currentInput.type=='reset') && currentInput.id!='ok selectUser') {
          currentInput.disabled = true;
        }
      }
    </script>

    <?php  

  }
}?>
</body>
</html>
