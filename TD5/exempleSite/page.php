<?php
session_start();
if (!isset($_SESSION['avis'])) {
    $_SESSION['avis'] = [];
}

/*
* Données d'exemple de livres pour la page de produit
* Chaque livre est représenté par un tableau associatif contenant ses informations
* Ces données pourraient normalement être récupérées d'une base de données, mais ici elles sont écrites en dur pour l'exemple.
*
*/
$donneesLivres = [
    1 => [
        "title" => "Apprendre le CSS de A à Z",
        "author" => "Arnaud Leblanc",
        "price" => "29.99",
        "rating" => "4.5",
        "description" => "Découvrez les secrets du CSS et maîtrisez l'art de la mise en page web avec 'Apprendre le CSS de A à Z'. Ce livre complet vous guide à travers les fondamentaux du CSS, des sélecteurs aux propriétés avancées, en passant par les techniques de mise en page modernes. Que vous soyez débutant ou développeur expérimenté, ce livre vous aidera à créer des sites web élégants et réactifs.",
        "chapters" => [
            "Introduction au CSS",
            "Sélecteurs et propriétés de base",
            "Mise en page avec Flexbox",
            "Mise en page avec Grid",
            "Animations et transitions",
            "Techniques avancées de CSS"
        ],
        "ASIN" => "B0EXAMPLE",
        "fundedBy" => "Université de Tours",
        "publicationDate" => "2024-05-15",
        "language" => "Français",
        "pages" => "275",
        "imageLarge" => "livre_css_large.png",
        "imageSmall" => "livre_css_small.png",
        "imageBack" => "livre_css_4eme.png"
    ],
    2 => [
        "title" => "Apprendre le HTML à la fac",
        "author" => "François Dupont",
        "price" => "49.99",
        "rating" => "4.5",
        "description" => "Le livre indispensable pour apprendre le HTML en s'amusant ! Avec des exemples concrets et des exercices pratiques, ce livre vous guidera pas à pas dans la maîtrise du langage HTML. Que vous soyez débutant avec mes balises telles que &lt;html&gt; et &lt;div&gt;, ou que vous souhaitiez approfondir vos connaissances, ce livre est fait pour vous. Etudiants de L1 Maths découvrez le HTML de manière ludique et efficace avec de vrais exercices de TD adaptés à votre niveau.",
        "chapters" => [
            "Introduction au HTML",
            "Les bases des balises HTML",
            "Structurer une page web",
            "Les formulaires et interactions",
            "CSS et mise en forme",
            "Projet final : création d'un site web"
        ],
        "ASIN" => "L1-MATHS-GR1",
        "fundedBy" => "Université de Tours - Faculté des Sciences",
        "publicationDate" => "2026-02-16",
        "language" => "Français",
        "pages" => "250",
        "imageLarge" => "livre_html_large.jpg",
        "imageSmall" => "livre_html_small.jpg",
        "imageBack" => "livre_html_4eme.png"
    ],
    3 => [
        "title" => "Apprendre le JavaScript à la fac",
        "author" => "Sophie Martin",
        "price" => "39.99",
        "rating" => "4.0",
        "description" => "Découvrez les bases du JavaScript et apprenez à créer des sites web interactifs avec 'Apprendre le JavaScript à la fac'. Ce livre complet vous guide à travers les fondamentaux du JavaScript, des variables aux fonctions, en passant par la manipulation du DOM et les événements. Que vous soyez débutant ou que vous souhaitiez approfondir vos connaissances, ce livre est fait pour vous. Etudiants de L1 Maths découvrez le JavaScript de manière ludique et efficace avec de vrais exercices de TD adaptés à votre niveau.",
        "chapters" => [
            "Introduction au JavaScript",
            "Variables et types de données",
            "Fonctions et portée",
            "Manipulation du DOM",
            "Événements et interactions",
            "Projet final : création d'une application web"
        ],
        "ASIN" => "L1-MATHS-GR2",
        "fundedBy" => "Université de Tours - Faculté des Sciences",
        "publicationDate" => "2026-02-16",
        "language" => "Français",
        "pages" => "300",
        "imageLarge" => "livre_js_large.jpg",
        "imageSmall" => "livre_js_small.jpg",
        "imageBack" => "livre_js_4eme.png"
    ]

];

// On récupère l'identifiant ID du livre, 1 par défaut, mais peut être modifié en fonction de l'URL (ex: page.php?id=2 pour afficher le deuxième livre)
$id = 1;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
}

// Traitement du formulaire d'avis soumis par l'utilisateur
// ==> on verra ça lors du TD6
$messageErreurTraitementFormulaire = "";
$userRating = "";
$userComment = "";
$userEmail = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Etape 1 : récupération et nettoyage des données du formulaire
    $bookId = isset($_POST['bookId']) ? intval($_POST['bookId']) : null; // Récupération de l'ID du livre depuis le champ caché du formulaire
    $userRating = isset($_POST['rating']) ? htmlspecialchars($_POST['rating']) : null;
    $userComment = isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : null;
    $userEmail = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : null;
    $verifInput = isset($_POST['verifInput']) ? htmlspecialchars($_POST['verifInput']) : null;

    // Etape 2 : on vérifie que tous les champs sont remplis (note, commentaire, email, vérification anti-robots)
    if (!$bookId || !$userRating || !$userComment || !$userEmail || !$verifInput) {
        $messageErreurTraitementFormulaire = "Veuillez remplir tous les champs du formulaire pour soumettre votre avis.";
    }

    // Vérification de l'input anti-robots (doit être égal à "9,00" pour valider le formulaire)
    if ($verifInput === "9,00") {

        // Etape 3 : validation des données (ex: vérifier que la note est entre 1 et 5, que l'email est valide, etc.)
        if (!preg_match("/^[1-5]$/", $userRating)) {
            $messageErreurTraitementFormulaire = "La note doit être un nombre entier entre 1 et 5.";
        } else if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $messageErreurTraitementFormulaire = "Veuillez entrer une adresse email valide.";
        } else if (!preg_match("/^[1-2]$/", $bookId)) {
            $messageErreurTraitementFormulaire = "ID de livre invalide.";
        }

        $bookId = intval($bookId);


        // Etape 4 : si tout est valide (donc si $messageErreurTraitementFormulaire est vide), on peut enregistrer l'avis (dans une base de données généralement)
        if (empty($messageErreurTraitementFormulaire)) {

            // Ici, POUR L'EXEMPLE, on va enregistrer les avis dans $_SESSION pour les afficher ensuite (note : ce n'est pas une solution de stockage permanente, juste pour l'exemple)

            // Enregistrement de l'avis dans la session
            $_SESSION['avis'][] = [
                "bookId" => $bookId,
                "rating" => $userRating,
                "comment" => $userComment,
                "email" => $userEmail,
                "date" => date("Y-m-d H:i:s")
            ];

            // On redirige l'utilisateur vers la même page pour éviter la soumission du formulaire en cas de rafraîchissement
            header("Location: page.php?id=" . $bookId);

            // On utilise exit après la redirection pour s'assurer que le script s'arrête et que la redirection se fasse correctement
            exit;
        }
    } else {
        $messageErreurTraitementFormulaire = "Vérification anti-robots échouée. Veuillez entrer la bonne réponse pour valider votre avis.";
    }
}



/*
 Récupération des données du livre à afficher, en utilisant l'ID fourni dans l'URL (ex: page.php?id=1)
*/
$bookTitle = $donneesLivres[$id]["title"];
$bookAuthor = $donneesLivres[$id]["author"];
$bookPrice = explode(".", $donneesLivres[$id]["price"])[0];
$bookPriceCents = explode(".", $donneesLivres[$id]["price"])[1];

$bookRating = $donneesLivres[$id]["rating"];
$bookRatingImage = $donneesLivres[$id]["rating"] . "_stars.png";

$bookDescription = $donneesLivres[$id]["description"];
$bookChapters = $donneesLivres[$id]["chapters"];

$bookASIN = $donneesLivres[$id]["ASIN"];
$bookFundedBy = $donneesLivres[$id]["fundedBy"];

$bookPublicationDate = $donneesLivres[$id]["publicationDate"];
$bookLanguage = $donneesLivres[$id]["language"];
$bookPages = $donneesLivres[$id]["pages"];
$bookImageLarge = $donneesLivres[$id]["imageLarge"];
$bookImageSmall = $donneesLivres[$id]["imageSmall"];
$bookImageBack = $donneesLivres[$id]["imageBack"];

$bookReviews = 0;
if (isset($_SESSION['avis']) && !empty($_SESSION['avis'])) {
    foreach ($_SESSION['avis'] as $avis) {
        if ($avis['bookId'] == $id) {
            $bookReviews++;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Athalante - Site de commerce</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <main>

        <header>

            <img src="img/logo_athalante.png" alt="Logo d'Athalante - Site de commerce">
            <nav>
                <a href="page.php">Accueil</a>
                <a href="http://site.athalante.fr/produits">Produits</a>
                <a href="mailto:contact@athalante.fr">Contact</a>
                <a href="http://univ-tours.fr">À propos</a>
            </nav>

        </header>

        <aside class="zone-achat">

            <p id="p-rating">
                <?= $bookRating ?>
                <img src="img/<?= $bookRatingImage ?>" alt="Note de <?= $bookRating ?> étoiles">
                <span>
                    (<?= $bookReviews ?> avi<?= $bookReviews > 1 ? 's' : '' ?>)
                </span>
            </p>

            <p class="price">
                <?= $bookPrice ?><sup><?= $bookPriceCents ?> €</sup>
            </p>

            <p>
                Le prix inclut la TVA. Les frais de livraison et d'expédition sont calculés lors du passage à la caisse.
            </p>


            <p class="bold">En stock</p>

            <button>Ajouter au panier</button>


            <h3>Infos sur l'expédition :</h3>
            <ul>
                <li>Livraison standard : 3-5 jours ouvrables</li>
                <li>Livraison express : 1-2 jours ouvrables</li>
                <li>Livraison gratuite pour les commandes de plus de 50€</li>
            </ul>

            <section class="product-evaluation">
                <h2>Votre avis sur ce livre</h2>

                <?php
                if (!empty($messageErreurTraitementFormulaire)) {
                    echo "<p class='error-message'>{$messageErreurTraitementFormulaire}</p>";
                }
                ?>

                <form method="post" action="page.php">
                    <fieldset>

                        <input type="hidden" name="bookId" value="<?= $id ?>">

                        <div>
                            <label for="rating">Votre note :</label>
                            <select id="rating" name="rating" required>
                                <option value="">--Choisissez une note--</option>
                                <option value="1" <?= $userRating == 1 ? 'selected' : '' ?>>1 étoile</option>
                                <option value="2" <?= $userRating == 2 ? 'selected' : '' ?>>2 étoiles</option>
                                <option value="3" <?= $userRating == 3 ? 'selected' : '' ?>>3 étoiles</option>
                                <option value="4" <?= $userRating == 4 ? 'selected' : '' ?>>4 étoiles</option>
                                <option value="5" <?= $userRating == 5 ? 'selected' : '' ?>>5 étoiles</option>
                            </select>
                        </div>


                        <div>
                            <label for="comment">Votre commentaire :</label>
                            <br>
                            <textarea id="comment" name="comment" rows="4" cols="50"
                                placeholder="Écrivez votre commentaire ici..." required><?= htmlspecialchars($userComment) ?></textarea>
                        </div>

                        <div>
                            <label for="email">Votre adresse email :</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($userEmail) ?>" required>
                        </div>

                        <div>
                            <label for="verifInput">Vérification anti-robots :</label>
                            <input type="text" id="verifInput" name="verifInput" required
                                placeholder="Combien font 4 + 5 ? Réponse au format 000,00" pattern="[\d]{1,3},[\d]{2}">
                            <p>Veuillez entrer le résultat de l'addition : 4 + 5 = ? avec deux chiffres après la virgule
                            </p>
                        </div>

                        <div>
                            <button type="submit">Envoyer</button>
                        </div>


                    </fieldset>
                </form>

                <?php
                // Affichage des avis soumis par les utilisateurs pour ce livre (stockés dans $_SESSION)
                if (isset($_SESSION['avis']) && !empty($_SESSION['avis'])) {
                    echo "<h2>Avis des utilisateurs :</h2>";
                    $nbAvisAffiches = 0;
                    foreach ($_SESSION['avis'] as $avis) {
                        if ($avis['bookId'] == $id) { // Afficher uniquement les avis pour le livre actuel

                            echo "<div class='user-review'>";
                            echo "<p><strong>Note :</strong> " . $avis['rating'] . " étoiles</p>";
                            echo "<p><strong>Commentaire :</strong> " . $avis['comment'] . "</p>";
                            echo "<p><strong>Email :</strong> " . $avis['email'] . "</p>";
                            echo "<p><em>Posté le " . $avis['date'] . "</em></p>";
                            echo "</div>";

                            $nbAvisAffiches++;
                        }
                    }

                    if ($nbAvisAffiches == 0) {
                        echo "<p>Aucun avis pour ce livre pour le moment. Soyez le premier à donner votre avis !</p>";
                    }
                }

                ?>



            </section>

        </aside>





        <article class="product-main">
            <article>
                <h1><?= $bookTitle ?></h1>


                <p class="price">
                    <?= $bookPrice ?><sup><?= $bookPriceCents ?> €</sup>
                </p>

                <p class="text-justify">

                    <?= $bookDescription ?>
                </p>
                <p>
                    Découvrir les chapitres du livre :
                </p>
                <ol>
                    <?php foreach ($bookChapters as $chapter): ?>
                        <li><?= $chapter ?></li>
                    <?php endforeach; ?>
                </ol>


            </article>

            <section class="product-details">
                <h2>Détails sur le produit</h2>

                <table>
                    <tr>
                        <th scope="row">ASIN</th>
                        <td><?= $bookASIN ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Auteur</th>
                        <td><?= $bookAuthor ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Financé par</th>
                        <td rowspan="2"><?= $bookFundedBy ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Collection</th>

                    </tr>

                    <tr>
                        <th scope="row">Date de publication</th>
                        <td><?= $bookPublicationDate ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Langue</th>
                        <td><?= $bookLanguage ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Nombre de pages</th>
                        <td><?= $bookPages ?></td>
                    </tr>


                </table>
            </section>

            <section>
                <h2>Autres livres</h2>

                <?php
                // Affichage de liens vers les autres livres disponibles (en utilisant les données du tableau $donneesLivres)
                foreach ($donneesLivres as $idDuLivre => $donnesDuLivre) {

                    if ($idDuLivre != $id) { // Afficher uniquement les autres livres, pas celui actuellement affiché

                ?>
                        <div class="other-book">
                            <h3><?= $donnesDuLivre["title"] ?></h3>
                            <p>Par <?= $donnesDuLivre["author"] ?></p>
                            <a href="page.php?id=<?= $idDuLivre ?>">Voir ce livre</a>
                        </div>


                <?php

                    }
                }
                ?>

            </section>



        </article>


        <div class="product-images">


            <div>
                <img src="img/<?= $bookImageLarge ?>" alt="Couverture du livre '<?= $bookTitle ?>'">
            </div>
            <div>
                <img src="img/<?= $bookImageSmall ?>" alt="Couverture du livre '<?= $bookTitle ?>'">
            </div>
            <div>
                <img src="img/<?= $bookImageBack ?>" alt="Quatrième de couverture du livre '<?= $bookTitle ?>'">
            </div>

        </div>



        <footer>
            <p>&copy; 2026 TP Noté - L1-Math Groupe 1A - Université de Tours</p>
        </footer>



    </main>



</body>

</html>