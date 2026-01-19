# Retours sur le TD1

## Structure de base d'une page HTML

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Titre de la page</title>
</head>
<body>
    <!-- Contenu de la page -->
</body>
</html>
```

Cette structure de base est essentielle pour toute page HTML. Le doctype déclare le type de document, la balise `<html>` englobe tout le contenu, la balise `<head>` contient les métadonnées et la balise `<body>` contient le contenu visible de la page. C'est le point de départ pour **toute** création de page web.


## Tableaux 

Structure simple :

```html
<table>
    <!-- En-tête du tableau -->
    <tr>
        <th>En-tête colonne A</th>
        <th>En-tête colonne B</th>
    </tr>

    <!-- Première ligne de données -->
    <tr>
        <td>Donnée A1</td>
        <td>Donnée B1</td>
    </tr>

    <!-- Deuxième ligne de données -->
    <tr>
        <td>Donnée A2</td>
        <td>Donnée B2</td>
    </tr>

</table>
```

Structure complexe :

```html
<table>

    <!-- En-tête du tableau (optionnel) -->
    <thead>
        <tr>
            <th>En-tête colonne X</th>
            <th>En-tête colonne Y</th>
        </tr>
    </thead>

    <!-- Corps du tableau -->
    <tbody>
        <tr>
            <td>Donnée X1</td>
            <td>Donnée Y1</td>
        </tr>
        <tr>
            <td>Donnée X2</td>
            <td>Donnée Y2</td>
        </tr>
    </tbody>

    <!-- Pied de page du tableau (optionnel) -->
    <tfoot>
        <tr>
            <td>Pied de page colonne X</td>
            <td>Pied de page colonne Y</td>
        </tr>
    </tfoot>

</table>
```

Les tableaux simples sont suffisants pour des données basiques, tandis que les tableaux complexes offrent une meilleure organisation et sémantique (cela peut aider le référencement et l'accessibilité) pour des données plus structurées.

## Balise pre

```html
<pre>
    Ceci est un texte préformaté.
    Les espaces et les sauts de ligne sont conservés.
        Exemple d'indentation.
</pre>
```

La balise `<pre>` est utilisée pour afficher du texte préformaté, où les espaces et les sauts de ligne sont respectés. C'est utile pour afficher du code source, des poèmes, ou tout autre contenu où la mise en forme doit être conservée.

## Images et chemins relatifs

Lorsque vous insérez une image dans une page HTML, il est important de comprendre comment fonctionnent les chemins vers les fichiers. 

De base, vous écrivez des balises HTML dans un fichier html. Ce fichier se trouve quelque part dans votre arborescence de fichiers / dans votre disque dur. Par exemple, imaginons que votre fichier HTML se trouve dans le dossier `Documents/TD1` et qu'il se nomme `exo1.html`. Dans ce dossier, vous avez également une image nommée `photo.jpg`. Se trouve aussi, un dossier `images` qui contient une autre image nommée `image2.png`.

Voici l'arborescence :

```
Documents/
└── TD1/
    ├── exo1.html
    ├── photo.jpg
    └── images/
        └── image2.png
        └── exo2.html
    └── autresImages/
        └── vacances/
            └── plage.jpg    
```

Dans le cas de chemins relatifs : les images, ou autres ressources (comme des fichiers CSS ou JavaScript), doivent être référencées par rapport à l'emplacement de ce fichier HTML.

Plusieurs cas de figure peuvent se présenter :

* Si l'image se trouve dans le même dossier que le fichier HTML ( dossier `TD1`), vous pouvez simplement utiliser le nom du fichier image :

```html
<img src="photo.jpg" alt="Description de l'image">
```

* Si l'image se trouve dans un sous-dossier (`images`), vous devez inclure le nom du dossier dans le chemin :

```html 
<img src="images/image2.png" alt="Description de l'image">
```

* Si l'image se trouve dans un sous-dossier plus profond (`autresImages/vacances`), vous devez inclure tous les dossiers dans le chemin :

```html
<img src="autresImages/vacances/plage.jpg" alt="Description de l'image">
```

* Si vous devez remonter d'un dossier (par exemple, si vous êtes dans `images/exo2.html` et que vous voulez accéder à `photo.jpg`), vous utilisez `..` pour remonter d'un niveau :

```html
<img src="../photo.jpg" alt="Description de l'image">
```

Cela dépend de la structure de vos dossiers et de l'emplacement relatif du fichier HTML par rapport aux images que vous souhaitez afficher.

**Attention aux chemins absolus !** : Les chemins absolus (comme `C:/Users/VotreNom/Documents/TD1/photo.jpg`) peuvent fonctionner sur votre propre machine, mais ils ne fonctionneront pas si vous déplacez votre projet / vos pages HTML ou si vous le partagez avec quelqu'un d'autre. Il est donc préférable d'utiliser des chemins relatifs pour garantir que les ressources sont correctement référencées quel que soit l'endroit où le projet est hébergé.

## Images cliquables avec des zones définies (map)

```html
    <!-- Exemple d'une carte avec des zones cliquables -->
    <map name="carte-region-centre">
        <area shape="circle" coords="178,92,75" href="http://fr.wikipedia.org/wiki/Eure-et-Loir" alt="Chartres">
        <area shape="circle" coords="229,186,75" href="http://fr.wikipedia.org/wiki/Loiret" alt="Orléans">
        <area shape="circle" coords="212, 385, 75" href="http://fr.wikipedia.org/wiki/Indre" alt="Châteauroux">
        <area shape="circle" coords="81, 304, 75" href="http://fr.wikipedia.org/wiki/Indre-et-Loire" alt="Tours">
        <area shape="circle" coords="308, 345, 75" href="http://fr.wikipedia.org/wiki/Cher" alt="Bourges">
        <area shape="circle" coords="174, 241, 75" href="http://fr.wikipedia.org/wiki/Loir-et-Cher" alt="Blois">
    </map>

    <!-- Image associée à la carte; l'attribut usemap lie l'image à la carte définie ci-dessus, avec un # devant le nom ciblé -->
     <img src="ex16-Carte/carte-region-centre.gif" alt="" usemap="#carte-region-centre">
```