# Activité 3 : Travailler avec la BDD
Pour commencer, rentrons à la main au moins une catégorie, une classification et un film.

Nous allons construire ensuite un nouveau controller `FilmController`, rappelez-vous de la ligne de commande pour ça. Ensuite, modifiez la première route pour qu'elle soit appelée à l'URL `films`. 

récupérez tous les films, et donnez-les à la vue.

Affichez tous les films dans une liste, avec leur titre.

Sur chaque titre, on va vouloir mettre un lien, qui nous permettra de voir les infos d'un film en particulier.

## URL avec paramètre
Comme on l'a vu dans le [cours 1](<01 cours 1.md>), on peut récupérer un paramètre dans l'url.

Ça va nous être très utile pour créer des liens pour chaque film.

Commençez par créer la route qui permettra de voir un film, dans `FilmController`

## Ajouter un lien dans la vue index
Dans notre fichier `index.html.twig`, sur chaque titre on va venir ajouter un lien vers la page du film en question, en fonction de son id.

Pour cela, je vous laisse lire la documentation de symfony :
[créer des liens](https://symfony.com/doc/current/templates.html#linking-to-pages)

## Créer la vue show
Pensez à créer la vue `show.html.twig` dans vos templates. 
Elle permettra de voir tous les éléments du film mis en forme.

