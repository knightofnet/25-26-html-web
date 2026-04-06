# Explications détaillées de `ex3.php`

But du document
---------------
Ce fichier explique pas à pas le comportement du script `ex3.php` (situé dans le même dossier). On détaille la logique, les choix, les expressions régulières (côté PHP et côté HTML) et le rôle des fonctions auxiliaires contenues dans `ex3_functions.php`.

**Ce texte a été généré par une IA et relu par un humain.**

Plan de lecture (checklist)
- Présentation générale du flux (formulaire → POST → validations)
- Variables principales : `$msgErreur` et `$values`
- Contrôle d'origine (`$_SERVER['HTTP_REFERER']`) et ses limites
- Validation côté serveur par champ (avec une fonction commençant par `estValide*`)
- Rappel et explication des expressions régulières utilisées
- Correspondance avec les attributs `pattern` du HTML et différences
- Fonctions d'affichage (helpers) et pourquoi `htmlspecialchars` est utilisé
- Exemples d'entrées valides / invalides
- Récapitulatif et conseils pratiques

1) Vue d'ensemble
------------------
Le fichier `ex3.php` effectue les opérations suivantes :

- Inclut un fichier de fonctions utilitaires (`require_once("ex3_functions.php")`).
- Initialise deux tableaux : `$msgErreur` (pour stocker les messages d'erreur) et `$values` (pour conserver les valeurs soumises et les réafficher dans le formulaire si nécessaire).
- Si la page a reçu une requête `POST`, le script vérifie chaque champ soumis, nettoie la valeur (avec `htmlspecialchars`) et appelle une fonction de validation adaptée. Si la validation échoue, un message est ajouté à `$msgErreur`.
- Ensuite le HTML du formulaire est affiché. Les fonctions `afficheMsgErreur`, `afficheValue` et `afficheOptionSelected` (définies dans `ex3_functions.php`) sont utilisées pour afficher les erreurs et réinjecter les valeurs précédemment soumises.

2) Pourquoi deux tableaux : `$msgErreur` et `$values` ?
----------------------------------------------------

- `$msgErreur` : tableau associatif où chaque clé correspond au nom d'un champ (par exemple `'ref'`, `'prix'`, ...). Si une validation échoue, la fonction ajoute un message d'erreur sous la clé correspondante. Cela permet d'afficher, près du champ concerné, un message spécifique.
- `$values` : tableau associatif qui contient les valeurs propres et encodées (après `htmlspecialchars`) des champs soumis. On le stocke pour pouvoir réafficher la valeur dans le formulaire — ça évite à l'utilisateur de tout ressaisir lorsqu'une validation échoue.

Exemple simple (utilisant `isset`) :

```php
$msgErreur = [];
$values = [];
if (isset($_POST['ref'])) {
    $values['ref'] = htmlspecialchars($_POST['ref']);
    if (!estValideMajuscule($values['ref'])) {
        $msgErreur['ref'] = "La référence doit être composée de majuscules uniquement";
    }
}
```

Explication et conseil : ce document part du principe que l'on utilise `isset()` pour détecter la présence de la clé dans `$_POST`. La section suivante explique en détail pourquoi `isset()` est préférable à `empty()` dans ce cas.

2.1) Différence pratique entre `empty()` et `isset()`
---------------------------------------------------

Résumé rapide :

- `isset($x)` retourne TRUE si la variable `$x` existe et n'est pas `null`.
- `empty($x)` retourne TRUE si la variable n'existe pas ou si sa valeur est "vide" selon PHP : valeurs considérées comme vides → `""` (chaîne vide), `0` (int zéro), `"0"` (chaîne "0"), `null`, `false`, `[]`.

Pourquoi utiliser `isset()` est pertinent ici :

- Certains champs légitimes peuvent contenir la valeur littérale `"0"` ou `0` (par exemple une quantité `qtestk = 0` ou un prix `prix = 0`). Si on utilise `empty($_POST['qtestk'])`, PHP considérera cette valeur comme "vide" et le code sautera la validation/stockage de ce champ. Cela peut conduire à des comportements inattendus où la valeur `0` est ignorée.
- En remplaçant `empty()` par `isset()`, on s'assure que la logique s'exécute dès que la clé existe dans `$_POST`, même si sa valeur vaut `0` ou `"0"`.

Points à surveiller après ce changement :

- `isset()` n'empêche pas une chaîne vide `""`. Si vous voulez aussi refuser les chaînes vides côté serveur, il faudra tester explicitement `trim($val) === ''` ou équivalent après avoir vérifié `isset()`.
- Les champs du formulaire utilisent l'attribut `required` côté client : cela aide, mais la validation côté serveur doit rester complète et ne pas se reposer uniquement sur le navigateur.


3) Contrôle d'origine : `$_SERVER['HTTP_REFERER']`
------------------------------------------------

Le script contient cette vérification :

```php
if (!strpos($_SERVER['HTTP_REFERER'], 'ex3.php')) {
    $msgErreur['global'] = "L'origine des données n'est pas certifiée";
}
```

Explication : `$_SERVER['HTTP_REFERER']` contient l'URL de la page qui a mené à la requête actuelle (si le navigateur l'envoie). Le code vérifie si la chaîne `'ex3.php'` est présente dans cette URL.

Limites importantes (à connaître) :
- `HTTP_REFERER` est facultatif : certains navigateurs ou proxys le suppriment pour des raisons de confidentialité.
- C'est contrôlable côté client : un attaquant peut envoyer une requête HTTP avec un `Referer` falsifié.

Conclusion : utile pour de l'information ou un avertissement UX, mais ne doit PAS remplacer une vraie protection côté serveur (tokens CSRF, authentification, etc.).

4) Nettoyage des données : `htmlspecialchars`
-------------------------------------------

Chaque valeur soumise est traitée par `htmlspecialchars()` avant d'être stockée dans `$values`. Pourquoi ?

- Pour empêcher l'insertion de HTML ou de scripts malveillants lorsqu'on réaffiche la valeur dans la page (protection XSS lors de l'affichage).

Exemple : si l'utilisateur tape `<script>alert(1)</script>` et que l'on réaffiche la valeur sans `htmlspecialchars`, le script pourrait s'exécuter dans le navigateur d'un visiteur. `htmlspecialchars` transforme `<` en `&lt;`, `>` en `&gt;`, etc.

Important : `htmlspecialchars` protège l'affichage. Pour la validation (format attendu), on utilise des expressions régulières et des validations serveur supplémentaires.

5) Les validations côté serveur (fonctions dans `ex3_functions.php`)
------------------------------------------------------------------

Le fichier `ex3_functions.php` contient plusieurs fonctions `estValide*` et des helpers pour l'affichage. Voici leur contenu et la signification des expressions régulières :

```php
function estValideMajuscule($chaine) {
    return preg_match('/^[A-Z]+$/', $chaine);
}

function estValideMajMinChiffrs($chaine) {
    return preg_match('/^[A-Za-z0-9]+$/', $chaine);
}

function estValideDate($date) {
    return preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date);
}

function estValideMajChiffres($chaine) {
    return preg_match('/^[A-Z0-9]+$/', $chaine);
}

function estValideNombrePos($chaine) {
    return preg_match('/^[0-9]+(\.[0-9]+)?$/', $chaine);
}

function estValideEntierPos($chaine) {
    return preg_match('/^[0-9]+$/', $chaine);
}

function estValideSelect($value, array $choixPossibles) {
    return in_array($value, $choixPossibles);
}
```

Explications des expressions régulières (chaque élément)
- `/.../` : en PHP, `preg_match` attend une expression régulière encadrée par des délimiteurs, souvent `/`.
- `^` et `$` : ancrent la recherche au début et à la fin de la chaîne. Cela force la correspondance sur la totalité de la chaîne.
- `[A-Z]` : caractère compris entre A et Z (lettres majuscules ASCII).
- `[a-zA-Z0-9]` ou `[A-Za-z0-9]` : lettres minuscules, majuscules et chiffres.
- `+` : un ou plusieurs caractères (équivalent de {1,}).
- `
  - `
\d` : chiffre décimal (équivalent à `[0-9]`). Dans le fichier on écrit `\d` dans le code PHP lu ici ; dans la chaîne PHP il faut échapper la barre oblique inverse si on met l'expression dans des guillemets, mais dans les littéraux `/.../` on utilise simplement `\d` pour rendre visible l'antislash dans le fichier ; en pratique `/^\d{2}\/\d{2}\/\d{4}$/` matche `dd/mm/yyyy`.
- `\.` : point littéral (pour les décimales) — dans la regex PHP `.` signifie "n'importe quel caractère" ; on utilise `\.` pour dire "un point".
- `( ... )?` : groupe optionnel, le `?` après le groupe indique que la partie est facultative (zéro ou une occurrence).

Décryptage champ par champ
- Référence (`ref`) : `/^[A-Z]+$/` → seulement des lettres majuscules, au moins une.
- Désignation (`des`) : `/^[A-Za-z0-9]+$/` → lettres (majuscules/minuscules) et chiffres, au moins une.
- Date d'entrée (`dateEntree`) : `/^\d{2}\/\d{2}\/\d{4}$/` → exactement `2 chiffres / 2 chiffres / 4 chiffres` (ex. `05/08/2024`). Notez que la regex ne vérifie pas si le jour est <=31 ou si le mois <=12 ; elle vérifie uniquement le format.
- Allée / Etagère / Case (`allee`, `etag`, `case`) : `/^[A-Z0-9]+$/` → majuscules et chiffres uniquement.
- Prix (`prix`) : `/^[0-9]+(\.[0-9]+)?$/` → un nombre positif entier ou décimal avec un seul point décimal (ex. `12`, `12.50`).
- Quantité (`qtestk`) : `/^[0-9]+$/` → entier positif (un ou plusieurs chiffres).
- Sélecteur fournisseur (`fourn`) : `estValideSelect($value, ['hp', 'cisco', 'dell', 'verb', 'asus'])` → vérifie que la valeur soumise est exactement l'une des options autorisées.

6) Correspondances HTML `pattern` et différences par rapport à PHP
---------------------------------------------------------------

Dans le formulaire HTML, on trouve des attributs `pattern` pour aider la saisie côté client, par exemple :

```html
<input required pattern="[A-Z]+">              <!-- ref -->
<input required pattern="[A-Za-z0-9]+">       <!-- des -->
<input required pattern="\d{2}/\d{2}/\d{4}"> <!-- dateEntree -->
<input required pattern="[0-9]+(\.[0-9]+)?">  <!-- prix -->
```

Points importants :
- Le navigateur effectue une validation basique côté client (avant l'envoi) et affiche un message d'erreur si la valeur ne correspond pas au `pattern`. C'est pratique pour l'expérience utilisateur (UX).
- Le contenu du `pattern` utilise la syntaxe des expressions régulières de JavaScript (ECMAScript). On écrit la regex sans délimiteurs `/` et sans flags (par exemple pas de `/.../i`).
- Le comportement du `pattern` : l'implémentation HTML compare la valeur complète du champ avec la regex fournie. Autrement dit, le modèle est testé sur la totalité de la chaîne (comme si `^`...`$` étaient appliqués automatiquement). Donc écrire `^[A-Z]+$` dans `pattern` est redondant ; écrire `[A-Z]+` suffit.

Petite nuance d'échappement : dans l'attribut HTML, l'antislash `\` reste présent dans le texte de l'attribut. Par exemple `pattern="\d{2}/\d{2}/\d{4}"` est correct dans le HTML et correspond à la même idée que la regex en PHP. Dans le code PHP on doit faire attention si on génère des chaînes entourées par des guillemets — mais ici la regex est écrite dans le HTML statique.

Pourquoi les validations côté serveur et côté client sont légèrement différentes ?
- Le `pattern` sert surtout l'UX et évite des allers-retours inutiles serveur ↔ client.
- La validation côté serveur (avec `preg_match`) est obligatoire pour la sécurité : un utilisateur peut contourner le `pattern` (en désactivant le JavaScript ou en postant directement la requête HTTP).

7) Helpers d'affichage (dans `ex3_functions.php`)
-------------------------------------------------

Les fonctions ci-dessous facilitent l'affichage des erreurs et la réinjection des valeurs dans les champs du formulaire :

```php
function afficheMsgErreur(array $msgErreur, $clef)
{
    if (isset($msgErreur[$clef])) {
        return '<p style="color: yellow; background-color: red">' . $msgErreur[$clef] . '</p>';
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
```

Rôle pratique :
- `afficheMsgErreur($msgErreur,'ref')` renverra un paragraphe coloré si une erreur existe pour la clé `'ref'`.
- `afficheValue($values,'ref')` renverra `value="..."` si la clé existe, ce qui insère directement l'attribut `value` dans le champ `<input>`.
- `afficheOptionSelected` sert pour les `<option>` du `<select>` : si la valeur choisie auparavant correspond, on ajoute `selected="selected"`.

Remarque sécurité : les valeurs insérées par `afficheValue` proviennent de `$values`, et `$values` a été préalablement filtré par `htmlspecialchars` — c'est important pour éviter que l'attribut `value` contienne du HTML ou des guillemets non échappés.


8) Exemples concrets
---------------------

Voici quelques exemples d'entrées et la validation attendue :

- `ref` :
  - `ABC123` → invalide (contient des chiffres) ; selon la regex `/^[A-Z]+$/` seuls `ABC` validerait.
  - `ABC` → valide.

- `des` :
  - `Clavier123` → valide (`[A-Za-z0-9]+`).
  - `Clavier-éco` → invalide (le tiret `-` n'est pas autorisé par la regex actuelle).

- `dateEntree` :
  - `05/07/2025` → valide (format `dd/mm/yyyy`).
  - `5/7/2025` → invalide (il faut deux chiffres pour jour/mois).

- `prix` :
  - `12` → valide.
  - `12.5` → valide.
  - `12,50` → invalide (la virgule n'est pas acceptée par la regex actuelle, uniquement le point `.`).

- `fourn` (select) :
  - `hp` → valide.
  - `HP` → invalide (les options sont en minuscules dans la liste : `hp`, `cisco`, ...). Le format est sensible à la casse.

- `qtestk` :
  - `0` → valide (selon la regex `/^[0-9]+$/` ; si l'on veut refuser `0` il faudrait une validation supplémentaire).
  - `3.5` → invalide (décimal non permis pour la quantité).

- `allee`, `etag`, `case` :
  - `A12` → valide (`[A-Z0-9]+`).
  - `a12` → invalide (lettre minuscule non autorisée).

9) Conseils et bonnes pratiques
------------------------------------------

- Toujours valider côté serveur même si le client effectue des vérifications (`pattern`, JavaScript) : le client est contrôlable par un attaquant.
- Utiliser `htmlspecialchars` pour afficher des valeurs provenant des utilisateurs afin d'éviter des failles XSS.
- Ne pas se fier uniquement à `HTTP_REFERER` pour la sécurité.
- Pour les dates, si vous voulez valider la logique (jour <= 31, mois <= 12, année raisonnable), utilisez `DateTime::createFromFormat()` en PHP ou des validations supplémentaires après la regex de format (voir exercice 4).
- Pour les prix, attention aux formats locaux (virgule vs point). Choisir une convention (ici c'est le point) et le documenter.

10) Récapitulatif rapide
------------------------

- `$msgErreur` contient les messages d'erreur indexés par nom de champ.
- `$values` contient les valeurs nettoyées pour réaffichage.
- Les fonctions `estValide*` utilisent `preg_match` pour valider les formats.
- Les attributs `pattern` du HTML améliorent l'expérience utilisateur, mais la validation serveur est la référence.

Annexe : où trouver le code associé
-----------------------------------

- `ex3.php` : le formulaire + logique de validation et réaffichage.
- `ex3_functions.php` : fonctions de validation (`estValide*`) et helpers d'affichage (`afficheMsgErreur`, `afficheValue`, `afficheOptionSelected`).

Fin.

