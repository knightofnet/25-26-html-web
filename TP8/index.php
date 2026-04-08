<?php
require_once('php/config.php');

require_once(PROJECT_ROOT .'/php/funcs/aide_affichage_func.php');
require_once(PROJECT_ROOT .'/php/funcs/session_func.php');
require_once(PROJECT_ROOT .'/php/services/vols_services.php');


include(PROJECT_ROOT .'/php/inc/header.php');

$msgErreur = getAndDeleteFromSession('msgErreur', []);
$values = getAndDeleteFromSession('values', []);

$dernierVol = recupererDernierVolEnregistre();
if (isset($_GET['prec']) && $_GET['prec'] === 'ok') {
    if (!empty($dernierVol)) {
        $values = $dernierVol;
    }
}

?>

    <div>
        <h2>Trouver un vol</h2>
    </div>

    <div>
<?php
if (!empty($dernierVol)) {
    echo '<a href="?prec=ok" class="mb"><i class="fa-solid fa-arrow-rotate-right"></i> Recharger le dernier</a>';
}
?>
    </div>
    <div class="grid-vols-memorises grid-vols-memorises-2cols">
        <div class="form-container">
            <fieldset>
                <form action="php/trt/form_trouver_un_vol.php" method="post">
                    <div class="form-elt">
                        <label for="dateDep">
                            <i class="fa-solid fa-plane-departure"></i> Date de départ :
                        </label>
                        <input type="text" id="dateDep" name="dateDep"
                               placeholder="JJ/MM/AAAA"
                               required
                                <?= afficheValue($values, 'dateDep') ?>
                        >
                        <label for="hrDep">Heure de départ:
                        </label>
                        <input type="text" id="hrDep" name="hrDep"
                               placeholder="HH:MM"
                               required
                                <?= afficheValue($values, 'hrDep') ?>
                        >

                        <?= afficheMsgErreur('dateDep', $msgErreur) ?>
                        <?= afficheMsgErreur('hrDep', $msgErreur) ?>

                    </div>

                    <div class="form-elt">
                        <label for="dateArr">
                            <i class="fa-solid fa-plane-arrival"></i> Date d'arrivée :
                        </label>
                        <input type="text" id="dateArr" name="dateArr"
                               placeholder="JJ/MM/AAAA" required
                                <?= afficheValue($values, 'dateArr') ?>>
                        <label for="hrArr">Heure d'arrivée:
                        </label>
                        <input type="text" id="hrArr" name="hrArr"
                               placeholder="HH:MM" required
                                <?= afficheValue($values, 'hrArr') ?>>

                        <?= afficheMsgErreur('dateArr', $msgErreur) ?>
                        <?= afficheMsgErreur('hrArr', $msgErreur) ?>

                    </div>

                    <div class="form-elt">
                        <label for="classe">
                            <i class="fa-regular fa-money-bill-1"></i> Classe :
                        </label>
                        <select name="classe" id="classe">
                            <option <?= afficheOptionSelected($values, 'classe', 'eco') ?> value="eco">Eco</option>
                            <option <?= afficheOptionSelected($values, 'classe', 'ecop') ?> value="ecop">Eco Premium
                            </option>
                            <option <?= afficheOptionSelected($values, 'classe', 'aff') ?> value="aff">Affaires</option>
                            <option <?= afficheOptionSelected($values, 'classe', 'pclas') ?> value="pclas">Première
                                Classe
                            </option>
                        </select>
                        <?= afficheMsgErreur('classe', $msgErreur) ?>

                    </div>

                    <div class="form-elt">
                        <label for="nbAd">
                            <i class="fa-solid fa-person"></i>
                            Nombre d'adultes :
                        </label>
                        <input type="text" id="nbAd" name="nbAdd" required
                                <?= afficheValue($values, 'nbAdd') ?>>

                        <?= afficheMsgErreur('nbAdd', $msgErreur) ?>

                        <br>
                        <label for="nbEnf">
                            <i class="fa-solid fa-baby"></i>
                            Nombre d'enfants :
                        </label>
                        <input type="text" id="nbEnf" name="nbEnf" required
                                <?= afficheValue($values, 'nbEnf') ?> >

                        <?= afficheMsgErreur('nbEnf', $msgErreur) ?>

                    </div>

                    <div>
                        <button type="submit" name="submit" class="bouton">
                            Rechercher un vol
                            <i class="fa-regular fa-paper-plane"></i>
                        </button>
                    </div>

                </form>
            </fieldset>
        </div>


        <?php
        if (count($msgErreur) == 0 && count($values) > 0) :
            ?>
            <div>
                <h3>Recherche du vol validée et enregistrée :</h3>

                <?php afficheCarteVol($values); ?>

                <h4>Bon vol !</h4>


            </div>

        <?php

        else :
            echo '<div></div>';
        endif;

        ?>
    </div>
<?php

include(PROJECT_ROOT .'/php/inc/footer.php');

