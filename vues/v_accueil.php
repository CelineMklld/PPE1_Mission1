<?php
/**
 * Vue Accueil
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @author    Céline Moukalled
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?>

<div id="accueil">
    <h2>
        Gestion des frais<small> - <?php if ($_SESSION['estComptable']) {
            echo "Comptable";
        } 
        else {echo "Visiteur";} ?> :

        <?php 
        echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']
        ?></small>
    </h2>
</div>

<?php  

if (!$_SESSION['estComptable']) {
    ?>

    <!-- Affichage de la page d'accueil lorsque la session est celle d'un visiteur --->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="glyphicon glyphicon-bookmark"></span>
                        Navigation Visiteur
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <a href="index.php?uc=gererFrais&action=saisirFrais"
                            class="btn btn-success btn-lg" role="button">
                            <span class="glyphicon glyphicon-pencil"></span>
                            <br>Renseigner la fiche de frais</a>
                            <a href="index.php?uc=etatFrais&action=selectionnerMois"
                            class="btn btn-primary btn-lg" role="button">
                            <span class="glyphicon glyphicon-list-alt"></span>
                            <br>Afficher mes fiches de frais</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php  }

// Affichage de la page d'accueil lorsque la session est celle d'un comptable

if ($_SESSION['estComptable']) {

    ?>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="glyphicon glyphicon-bookmark"></span>
                        Navigation Comptable
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <a href="index.php?uc=validerFicheFrais&action=validerFrais&init"
                            class="btn btn-success btn-lg" role="button">
                            <span class="glyphicon glyphicon-ok"></span>
                            <br>Valider une fiche de frais</a>
                            <a href="index.php?uc=suiviPaiementFrais&action=suivrePaiementFrais"
                            class="btn btn-primary btn-lg" role="button">
                            <span class="glyphicon glyphicon-euro"></span>
                            <br>Suivre le paiement d'une fiches de frais</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php }  ?>