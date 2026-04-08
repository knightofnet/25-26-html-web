<?php
require_once('php/config.php');
require_once(PROJECT_ROOT .'/php/services/vols_services.php');
require_once(PROJECT_ROOT .'/php/funcs/aide_affichage_func.php');


include(PROJECT_ROOT .'/php/inc/header.php');

$volEnregistres = recupererVolsEnregistres();
?>

    <div>
        <h2>Recherches précédentes</h2>
    </div>

    <div>
        <?php
        if (!empty($volEnregistres)) {
            echo '<a href="' . BASE_URL . '/php/trt/reset-historique.php" class="mb"><i class="fa-solid fa-x"></i> Effacer l\'historique</a>';
        }
        ?>


    </div>

    <div class="grid-vols-memorises">

        <?php
        if (count($volEnregistres) == 0) :
            ?>
            <div class="carte-vol">
                <h3>Aucun vol mémorisé</h3>
            </div>
        <?php
        else :

            foreach ($volEnregistres as $vol) {

                afficheCarteVol($vol);

            }

        endif;
        ?>

    </div>

<?php
include(PROJECT_ROOT .'/php/inc/footer.php');
