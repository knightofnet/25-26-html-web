<?php

function afficheMsgErreur($clef, array $msgErreur)
{
    if (isset($msgErreur[$clef])) {
        return '<p class="error">' . $msgErreur[$clef] . '</p>';
    }
    return '';
}

function afficheValue(array $values, $clef)
{
    if (isset($values[$clef])) {
        return 'value="' . $values[$clef] . '"';
    }

    return '';
}

function afficheOptionSelected(array $values, $clef, $valueOption)
{
    if (isset($values[$clef])) {
        if ($values[$clef] === $valueOption) {
            return 'selected="selected"';
        }
    }
    return '';
}

function valueClasseToNomClasse($valueClasse)
{
    $tabloConversion = [
            'eco' => 'Eco',
            'ecop' => 'Eco Premium',
            'aff' => 'Affaire',
            'pclas' => 'Première Classe'
    ];

    if (isset($tabloConversion[$valueClasse])) {
        return $tabloConversion[$valueClasse];
    }
    return '';
}

function afficheCarteVol(array $values)
{
    ?>
    <div class="carte-vol">
        <div class="carte-vol-dates">
            <p>Date de départ : <?= $values['dateDep'] ?> <?= $values['hrDep'] ?></p>
            <p>Date d'arrivée : <?= $values['dateArr'] ?> <?= $values['hrArr'] ?></p>
        </div>

        <div class="carte-vol-classe">
            <p>Classe : <?= valueClasseToNomClasse($values['classe']) ?></p>
        </div>

        <div class="carte-vol-nbr">
            <div>
                <p>Nombre d'adulte</p>
                <p><?= $values['nbAdd'] ?></p>
            </div>
            <div>
                <p>Nombre d'enfants</p>
                <p><?= $values['nbEnf'] ?></p>
            </div>
        </div>

    </div>
    <?php
}