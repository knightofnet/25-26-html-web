# Explications détaillées de `ex4.php`

But du document
---------------
Ce fichier explique pas à pas le comportement du script `ex4.php` (situé dans le même dossier). On détaille la logique, les choix, les expressions régulières (côté PHP et côté HTML) et le rôle des fonctions auxiliaires contenues dans `ex4_functions.php`.

**Ce texte a été généré par une IA et relu par un humain.**

Plan (checklist)
- Vue d'ensemble du flux (formulaire -> POST -> validation -> réaffichage)
- Variables principales : `$msgErreur` et `$values`
- Nettoyage des données avec `htmlspecialchars`
- Validation champ par champ (fonctions dans `ex4_functions.php`) et explication des regex
- Pourquoi `DateTime::createFromFormat()` est utilisé 
- Helpers d'affichage (`afficheMsgErreur`, `afficheValue`, `afficheOptionSelected`)
- Différences entre `pattern` (HTML) et `preg_match` (PHP)
- Exemples d'entrées valides / invalides
- Remarques pratiques et points d'amélioration

Référence : ce document complète et renvoie à `../ex3/ex3_explications.md` pour des explications générales déjà communes (par ex. `isset()` vs `empty()`, rôle de `htmlspecialchars`, principe d'une validation serveur vs client). N'hésitez pas à consulter ce fichier si un sujet vous paraît familier.

1) Vue d'ensemble
-----------------

Le fichier `ex4.php` fait essentiellement ceci :

- Inclut le fichier de fonctions utilitaires `require_once("ex4_functions.php")`.
- Initialise deux tableaux vides : `$msgErreur = []` (pour recueillir les messages d'erreur) et `$values = []` (pour conserver les valeurs "propres" soumises).
- Si la page reçoit une requête POST (forme : envoi du formulaire), chaque champ est testé : on lit la valeur envoyée, on la nettoie et on appelle une fonction `estValide*` correspondante. Si une validation échoue, on ajoute un message dans `$msgErreur` sous la clé du champ.
- Après la validation, la page HTML affiche le formulaire. Les helpers `afficheValue`, `afficheMsgErreur` et `afficheOptionSelected` servent à réafficher les valeurs précédentes et les messages d'erreur à côté des champs.

Ce flux (collecte -> nettoyage -> validation -> stockage d'erreurs -> réaffichage) est une architecture très courante pour les formulaires web.

2) Pourquoi deux tableaux : `$msgErreur` et `$values` ?
----------------------------------------------------

- `$msgErreur` (tableau associatif) : clé = nom du champ (ex. `dateDep`, `hrDep`, `nbAdd`, ...). Si la validation du champ échoue, on stocke un message lisible pour l'utilisateur sous cette clé. Exemple :

```php
$msgErreur['dateDep'] = "La date de départ n'est pas valide (format JJ/MM/AAAA attendu)";
```

Cela permet d'afficher l'erreur précisément là où l'utilisateur s'attend à la trouver.

- `$values` (tableau associatif) : contient les valeurs nettoyées (après `htmlspecialchars`) qui seront réinjectées dans les champs `<input>` pour éviter que l'utilisateur ressaisisse tout en cas d'erreur. Exemple :

```php
$values['dateDep'] = htmlspecialchars($_POST['dateDep']);
```

Conseil pédagogique : séparer erreurs et valeurs permet de garder une logique claire : "ce tableau contient uniquement des erreurs", "l'autre contient uniquement des valeurs".

3) Nettoyage : `htmlspecialchars`
---------------------------------

Avant de stocker une valeur dans `$values`, le script fait :

```php
$values[$field] = htmlspecialchars($_POST[$field]);
```

Pourquoi ?
- Pour éviter les attaques XSS lors du réaffichage : si un utilisateur soumet `<script>...</script>` et qu'on le réaffiche dans un `value` sans échappement, le script pourrait s'exécuter.
- `htmlspecialchars` transforme `&`, `<`, `>`, `"`, `'` en entités HTML (`&amp;`, `&lt;`, ...), rendant le texte sûr à insérer en HTML.

Remarque : `htmlspecialchars` protège uniquement l'affichage (et est suffisant pour insérer dans un attribut `value`). La validation du format et la logique métier doivent toujours être faites côté serveur (regex, conversions, comparaisons...).

4) Vérification de l'existence d'une soumission POST
---------------------------------------------------

Le fichier utilise la condition :

```php
if (!empty($_POST) && count($_POST) > 0) {
    // traitement
}
```

Ceci vérifie que le tableau `$_POST` contient au moins une clé. Notez que `!empty($_POST)` est déjà suffisant : `count($_POST) > 0` est redondant. L'auteur a peut-être voulu être explicite ou défensif.

Point important (déjà détaillé dans `ex3_explications.md`) : on préfère souvent `isset($_POST['champ'])` pour savoir si un champ a été envoyé, car `empty()` considère aussi la chaîne "0" comme vide.

5) Validation champ par champ (fonctions dans `ex4_functions.php`)
-----------------------------------------------------------------

Le fichier `ex4_functions.php` contient plusieurs fonctions de validation et d'affichage. Voici les plus importantes et leur sens (extraits commentés) :

- `estValideDate($date)`

```php
function estValideDate($date)
{
    return preg_match('/^((0[1-9])|([1-2]\d)|(3[0-1]))\/((0[1-9])|(1[0-2]))\/[1-2]\d{3}$/', $date);
}
```

Explication pas à pas :
- `preg_match('/.../', $date)` : teste si `$date` correspond à l'expression régulière.
- `^` et `$` : ancrent la regex au début et à la fin de la chaîne — la chaîne entière doit correspondre.
- `((0[1-9])|([1-2]\d)|(3[0-1]))` : jour du mois au format `01` à `31`. On autorise les jours `01`..`09`, `10`..`29`, `30`, `31`.
- `\/` : le slash `/` est échappé car le délimiteur de la regex est `/`.
- `((0[1-9])|(1[0-2]))` : mois de `01` à `12`.
- `\/[1-2]\d{3}` : année commençant par `1` ou `2` puis 3 chiffres (ex. `2026`).

Remarque importante : cette regex vérifie le format (dd/mm/yyyy) et certaines limites (jour 01–31, mois 01–12), mais elle ne garantit pas que la date est « réelle » (par ex. `31/02/2024` passe le format mais n'est pas une date réelle). Pour valider la cohérence réelle, on utilise `DateTime::createFromFormat()` (voir plus loin).

- `estValideHeure($date)`

```php
function estValideHeure($date)
{
    return preg_match('/^(([0-1]\d)|(2[0-3])):([0-5]\d)$/', $date);
}
```

Explication :
- `(([0-1]\d)|(2[0-3]))` : heure `00`..`23`.
- `:([0-5]\d)` : minutes `00`..`59`.

- `estValideSelect($value, array $choixPossibles)`

```php
function estValideSelect($value, array $choixPossibles)
{
    return in_array($value, $choixPossibles);
}
```

Vérifie que la valeur soumise pour un `<select>` est bien l'une des options autorisées. Ceci empêche un utilisateur malintentionné d'envoyer directement par POST une valeur non prévue.

- `estValideMajMinChiffres($chaine)`

```php
function estValideMajMinChiffres($chaine)
{
    return preg_match('/^[A-Za-z0-9]+$/', $chaine);
}
```

Autorise uniquement lettres (majuscules/minuscules) et chiffres.

- `estValideEntierPos($chaine)`

```php
function estValideEntierPos($chaine)
{
    return preg_match('/^[0-9]+$/', $chaine);
}
```

Autorise uniquement une suite de chiffres (entier positif, éventuellement `0`).

6) Utilisation de `DateTime::createFromFormat()` et contrôle de cohérence
------------------------------------------------------------------------

Une fois que les champs `dateDep`, `hrDep`, `dateArr`, `hrArr` sont présents et ont passé la validation de format, le script crée deux objets DateTime :

```php
$isDateEnErreur = false;

// Dans un premier temps, on va réellement valider les dates :
$dateDepart = DateTime::createFromFormat('d/m/Y H:i', $values['dateDep'] . ' ' . $values['hrDep']);
if (DateTime::getLastErrors() !== false
        && (DateTime::getLastErrors()['warning_count'] > 0 || DateTime::getLastErrors()['error_count'] > 0 )) {
    $msgErreur['dateDep'] = "Erreur de format sur la date ou l'heure de départ";
    $isDateEnErreur = true;
}
$dateArrivee = DateTime::createFromFormat('d/m/Y H:i', $values['dateArr'] . ' ' . $values['hrArr']);
if (DateTime::getLastErrors() !== false
        && (DateTime::getLastErrors()['warning_count'] > 0 || DateTime::getLastErrors()['error_count'] > 0 )) {
    $msgErreur['dateArr'] = "Erreur de format sur la date ou l'heure d'arrivée";
    $isDateEnErreur = true;
}

// On va comparer les dates
if (!$isDateEnErreur && $dateArrivee <= $dateDepart) {
    $msgErreur['dateArr'] = "La date-heure d'arrivée doit être après la date-heure de départ";
}
```

Explications :
- Après chaque appel à `createFromFormat()` on interroge `DateTime::getLastErrors()` pour savoir si le parsing a généré des erreurs ou des avertissements. Si oui, on marque le champ concerné comme erroné et on évite de faire la comparaison entre les deux dates.
- On utilise un drapeau `$isDateEnErreur` (booléen) pour se souvenir qu'au moins une des conversions a échoué — la comparaison temporelle n'a lieu que si les deux conversions sont correctes.
- Cette méthode permet d'attraper des cas où la regex de format passerait (ex. `31/02/2024`) mais où `DateTime` signale un warning ou une erreur lors du parsing.

7) Conversion d'entiers et règles métier
----------------------------------------

Le script valide `nbAdd` et `nbEnf` (nombre d'adultes et d'enfants) avec `estValideEntierPos`. Si la validation réussit, la valeur est convertie en entier :

```php
$values[$field] = intval($values[$field]);
```

Puis une règle de cohérence métier est appliquée : si `nbEnf > 0` alors `nbAdd` doit être au moins 1 (un adulte obligatoire). Si cette règle échoue, un message d'erreur est ajouté sur la clé `nbAdd`.

8) Helpers d'affichage (réinjection / messages d'erreur)
-------------------------------------------------------

Les fonctions dans `ex4_functions.php` facilitent l'affichage dans le HTML :

- `afficheMsgErreur($clef, $msgErreur)` : si une erreur existe pour la clé, renvoie un `<p>` contenant le message (dans le code la couleur est ajoutée en ligne — on pourrait le faire en CSS). Exemple :

```php
if (isset($msgErreur[$clef])) {
    return '<p style="color: yellow; background-color: red">' . $msgErreur[$clef] . '</p>';
}
return '';
```

- `afficheValue($values, $clef)` : si une valeur existe, renvoie l'attribut `value="..."` prêt à être collé dans un `<input>` :

```php
if (isset($values[$clef])) {
    return 'value="' . $values[$clef] . '"';
}
return '';
```

Important : ceci suppose que la valeur dans `$values` est déjà passée par `htmlspecialchars` — sinon des caractères spéciaux pourraient casser l'attribut HTML.

- `afficheOptionSelected($values, $clef, $valueOption)` : pour un `<select>`, renvoie `selected="selected"` si la valeur enregistrée correspond à l'option.

Remarque sur l'emplacement dans le HTML :
- Dans `ex4.php` ces helpers sont insérés directement dans la balise `<input>` ou `<option>` comme ceci :

```html
<input type="text" id="dateDep" name="dateDep" placeholder="JJ/MM/AAA" required <?= afficheValue($values, 'dateDep') ?>>
```

C'est pratique mais il faut faire attention aux espaces et aux guillemets. Ici `afficheValue` renvoie (ou non) un `value="..."` complet ; l'absence de valeur renverra une chaîne vide, ce qui laisse l'attribut `required` intact.

9) Différences entre validation côté client (HTML `pattern`) et côté serveur (PHP)
---------------------------------------------------------------------------------

- HTML `pattern` (attribut d'un `<input>`) sert surtout l'expérience utilisateur : le navigateur empêche (ou signale) l'envoi si la valeur ne correspond pas au motif, ce qui évite des allers-retours serveur inutiles.
- `pattern` utilise la syntaxe des regex JavaScript (ECMAScript) et ne nécessite pas les délimiteurs `/` ni les flags. De plus, `pattern` est implicite : le champ doit correspondre complètement au motif (comme si `^...$` était ajouté automatiquement).
- Côté serveur, `preg_match('/.../', $valeur)` utilise la syntaxe PCRE (qui ressemble beaucoup à JS mais a des différences — ex. certaines extensions, quantifieurs possessifs, options d'échappement). On doit toujours valider côté serveur car un utilisateur peut contourner le `pattern`.

Voir `../ex3/ex3_explications.md` (section "Correspondances HTML `pattern` et différences par rapport à PHP") pour une discussion plus complète et des exemples d'attributs `pattern`.

10) Exemples d'entrées valides / invalides (pour `ex4.php`)
----------------------------------------------------------

- `dateDep` / `dateArr` (format `JJ/MM/AAAA`) :
  - `05/07/2026` → valide (format attendu).
  - `5/7/2026` → invalide (il faut deux chiffres pour jour/mois).
  - `31/02/2026` → passe la regex de format mais n'est pas une date réelle : il faut gérer ce cas avec `DateTime::getLastErrors()` ou vérifier après parsing.

- `hrDep` / `hrArr` (format `HH:MM`) :
  - `09:05` → valide.
  - `9:5` → invalide (il faut deux chiffres pour heure et deux pour minutes).
  - `24:00` → invalide (heure maximale `23:59`).

- `classe` (select) : doit être l'une des valeurs `eco`, `ecop`, `aff`, `pclas`. Un envoi direct par POST de `classe=hack` sera rejeté par `estValideSelect`.

- `nbAdd` et `nbEnf` :
  - `0` est accepté comme entier (`estValideEntierPos` autorise `0`).
  - Si `nbEnf > 0` et `nbAdd === 0`, alors une erreur est levée (règle métier : un adulte est obligatoire si des enfants voyagent).

11) Observations / améliorations possibles
-----------------------------------------

- Après `DateTime::createFromFormat()` il est préférable de vérifier si la conversion a réussi et d'appeler `DateTime::getLastErrors()` pour détecter les erreurs de parsing.

Exemple de vérification plus robuste :

```php
$d = DateTime::createFromFormat('d/m/Y', '31/02/2024');
$errors = DateTime::getLastErrors();
if ($d === false || $errors['warning_count'] > 0 || $errors['error_count'] > 0) {
    // date invalide
}
```

- Les messages d'erreur sont stylés en ligne par `afficheMsgErreur`. Il vaut mieux déplacer ce style dans un fichier CSS (ex. `ex4.css`) pour séparer le contenu de la présentation.
- `afficheValue` insère la valeur directement : si on souhaite gérer des attributs `placeholder` ou autres, on peut préférer utiliser l'API `sprintf(' value="%s"', $val)` afin d'être certain de la mise en forme.

12) Récapitulatif rapide
------------------------------------

- Séparez clairement : collecte de données → nettoyage (htmlspecialchars) → validation (regex / règles métier) → stockage des erreurs → réaffichage des valeurs et erreurs.
- Les expressions régulières côté serveur (PCRE, `preg_match`) peuvent être un peu différentes de celles côté client (`pattern`) : on utilise les deux, mais la validation serveur est la seule qui compte vraiment pour la sécurité.
- Utilisez des helpers (`afficheValue`, `afficheMsgErreur`, `afficheOptionSelected`) pour éviter de répéter du code lorsqu'on affiche le formulaire.
- Ajoutez toujours des contrôles supplémentaires (par ex. vérifier le retour de `createFromFormat`) pour rendre le code robuste.

Annexes
------
- Voir `ex4_functions.php` (le fichier de fonctions) pour toute la logique des regex et des helpers.
- Pour des explications générales et communes (regex, `pattern`, `htmlspecialchars`, `isset` vs `empty`) consultez `../ex3/ex3_explications.md`.

Fin.

