CMS
===

A Symfony project created on May 4, 2016, 8:42 pm.

#### Widgets

Widgets disponibles :
* Instagram
* Réseaux sociaux (me suivre)
* Posts en relation

#### Extensions Twig

##### Partager sur
Affiche une liste de liens pour partager l'url courante sur les réseaux sociaux
###### Utilisation
<!-- language: lang-twig -->
    {{ social_buttons() }}

##### GMDate
Renvoie la date mais le temps retourné est GMT
###### Utilisation
<!-- language: lang-twig -->
    {{ date|gmdate(format) }}

##### Instagram
Renvoie la liste des images Instagram d'un utilisateur
###### Utilisation
<!-- language: lang-twig -->
    {{ instagram_images() }}

##### Query
Exécute une requête sur les contenus et permet de filtrer les contenus à afficher
###### Utilisation
<!-- language: lang-twig -->
    {{ Query() }}
###### Paramètres disponibles :
+ author
<!-- language: lang-twig -->
    Affiche les contenus de l'auteur ayant l'identitfiant 123
    {{ Query({author: 123) }}
    
+ author_name
<!-- language: lang-twig -->
    Affiche les contenus de cet auteur à partir de son nom
    {{ Query({author_name: 'jeanbonbeurre') }}   
+ cat
<!-- language: lang-twig -->
    Affiche les contenus de la catégorie ayant l'identifiant 123
    {{ Query({author: 123) }}
+ category_name
<!-- language: lang-twig -->
    Affiche les contenus de la catégorie ayant le titre
    {{ Query({author: 'non-categorise') }}
+ post_type
<!-- language: lang-twig -->
    Affiche les contenus de type 'post'
    {{ Query({post_type: 'post') }}
+ tax_query
<!-- language: lang-twig -->
    Affiche les contenus de type 'recette' avec un champ 'Nombre de personnes' égale à 6 
    {{ Query({tax_query: {taxonomy: 'recette', field: 'nb_personnes', terms: 6}) }}
+ p
<!-- language: lang-twig -->
    Affiche les contenus d'identifiant 123
    {{ Query({p: 123) }}
+ posts_per_page
<!-- language: lang-twig -->
    Renvoie 5 contenus
    {{ Query({posts_per_page: 5) }}
+ orderby
<!-- language: lang-twig -->
    Ordonne les contenus par titre de manière alphabétique
    {{ Query({orderby: 'title') }}
+ title
<!-- language: lang-twig -->
    Affiche le contenu de titre 'Choup et Doune'
    {{ Query({title: 'Choup et Doune') }}
+ s
<!-- language: lang-twig -->
    Affiche les contenus dont le titre ou la description contient 'choup'
    {{ Query({s: 'choup') }}
