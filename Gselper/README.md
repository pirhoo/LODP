# [Gselper](https://github.com/Pirhoo/LODP/tree/master/Gselper)

Cette petite Classe vous permet d'utiliser très simplement un Google Speadsheet comme source de données, directement depuis vos applications Javascript.

## Bien démarrer avec Gselper ##

### 0. Dépendances ###

Pour fonctionner cette classe dépend de jQuery 1.8+ (non testée sur des version antérieures). Pour installer jQuery, vous pouvez utilisez en trois clics les CDNs de Google :
```html
<script src="https://code.jquery.com/jquery-2.1.4.min.js" type="text/javascript"></script>
```
   
### 1. Inclure Gselper ###

Facile, vous faites ça 18 fois par jour (mais veillez à n'inclure Gselper qu'une seule fois) :
```html
<script src="./class.Gselper.js" type="text/javascript"></script>
```

### 2. Préparez votre Google Spreadsheet ###

Après avoir créé un nouveau Spreadsheet sur [Google Document](https://docs.google.com/), indiquez en tête de votre tableau le nom des colonnes. Attention la version actuelle de Gselper ne fonctionne qu'avec des documents d'une seule feuille. Rendez vous ensuite dans le menu sur "*Collaborer*" > "*Partager en tant que page Web*" puis dans la fenêtre Pop-up qui s'affiche cliquez sur le bouton "*Démarrer la publication*". Enfin, dans la partie "*Insérer un lien vers les données publiées*" du formulaire, selectionnez la version "*RSS*" est mettez le lien obtenu de coté. C'est très important.

Si vous ne savez pas quelles données utiliser, prenez [cet extrait de document](https://docs.google.com/spreadsheet/ccc?key=0Avn7N68sxVzHdGpDRVVEZjZrMlBkTmNYLXM3aHYzUHc&hl=fr).

### 3. Quelques lignes de code et c'est parti ! ###
Souvenez vous, je vous ai bien dit de mettre le lien précédent de coté. Il va falloir en extraire deux petites choses. Un exemple avec le lien :
    https://spreadsheets.google.com/feeds/list/0Avn7N68sxVzHdGpDRVVEZjZrMlBkTmNYLXM3aHYzUHc/od6/public/basic?alt=rss

Ici, relevez l'identifiant du document "0Avn7N68sxVzHdGpDRVVEZjZrMlBkTmNYLXM3aHYzUHc" et le worksheet "od6" (qui est toujours *od6* si vous n'avez qu'une seule feuille dans votre document).

Une fois dans votre script Javascript vous n'avez plus qu'à faire :
```js
// Création de l'instance de Gselper
var doc = new Gselper({

    // Identifiant du document
    key: "0Avn7N68sxVzHdGpDRVVEZjZrMlBkTmNYLXM3aHYzUHc",
   
    // Le worksheet du document
    worksheet: "od6",
   
    // La fonction à appeler lorsque le document est chargé
    onComplete: function(data) {
   
        // Ici faites ce qu'il vous chante
        // Par exemple, afficher dans la console le contenu de la première case
        console.log( data.get(0).country );
       
        // Ou parcourir le document ligne après ligne
        $.each(data.get(), function(i, line) {
           
            // et afficher le résultat dans la console
            console.log("Pays : " + line.country);
           
        });
       
        // Jusqu'ici nous avons utilisé la variable reçu en paramètre "data"
        // mais une fois le document chargé, nous pouvons utiliser l'objet "doc",
        // ici pour afficher la première ligne
        console.log( doc.get(1) );               
    },

    // La fonction à appeler lorsque qu'une erreur survient dans le chargement
    onFail: function(data) {

        console.log( "Something happened. Something happened." );               
    }
});
```

## To-Do ##

Le processus de récupération me semble encore un petit peu trop fastidieux à mon goût. Il serait bon de n'avoir qu'à donner l'url du document tel que son auteur la voit dans son navigateur et n'avoir ensuite qu'à clicher sur "Publier en tant que page Web". Une Regex se chargerait ensuite dans extraire les données.

Aussi tous les documents n'ont pas forcément besoin d'identifier leurs ligne par des numéros mais plutôt par des clefs plus élaborées. J'aimerai donc ajouter une nouvelle option pour préciser la clef à utiliser dans la méthode "get()" de la classe Gselper.

En outre l'utilisation de jQuery dans cette classe est minimale, nous pouvons donc sans trop de mal adapter le code pour supprimer cette dépendance qui n'est pas nécessairement la bienvenue.

Enfin, comme vous pouvez l'observer ce code est assez simple et il est largement envisageable d'en faire l'implémentation dans d'autres langage comme Ruby, PHP ou encore Java.

Votre aide est la bienvenue !
