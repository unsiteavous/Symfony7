# Cours 2 : Twig

[📜 DOCUMENTATION](https://twig.symfony.com/doc/3.x/)

Les vues en symfony utilise de qu'on appelle un moteur de template. Il en existe plusieurs (twig, blade, smarty, raintpl ...), et Symfony a choisi twig.

Dans l'idée, c'est pour nous obliger à respecter la SOC (Separation of Concerns) :  

> **Les vues affichent :** *aucun calcul ou autre traitement est fait dans une vue.*

Même si on pourrait encore ouvrir les balises php, on ne le fera pas. Le but de twig est de nous permettre d'intégrer des variables, d'inclure des composants, de moduler la page sous condition, tout en nous aidant à respecter la sécurité.

## Découverte d'un template
Si on va faire un tour dans le fichier qui a été créé tout à l'heure par notre ligne de commande (`templates/home/index.html.twig`), on se rend compte qu'il y a du HTML classique, parsemé d'autres balises :

### Extends
```twig
{% extends 'base.html.twig' %}
```
Cette balise est formidable : elle nous permet de cibler un template à remplir. Cela change tout le paradigme de l'affichage des vues ! 

Dans nos précédents MVC, nous appelions un fichier de template, en lui passant des variables :
* la section à afficher (users, films, catégories),
* l'action en cours (edit, show, new, ...)

Et le template, dans un grand `switch case`, devait retrouver quel bout inclure. 

Ce n'était pas ni pratique, ni aisément lisible.

Grâce à twig, on va fonctionner à l'envers : Notre contrôleur appelle directement le fichier final. Et on explique à ce fichier comment il doit se construire, en allant chercher des bouts ailleurs, ou en remplissant lui-même les bouts d'un **template parent**, en quelque sorte.

Le code `extends` permet de dire que nous allons remplir le template `base.html.twig`.

### block / endblock
Et comment allons-nous compléter le template parent ? en remplissant des **blocs** ! 

```twig
{# home/index.html.twig #}

{% block body %}
  Contenu HTML qui va remplir le bloc body du template étendu.
{% endblock %}
```

En entourant le code avec ces deux balises, nous disons à twig de mettre ce code dans le bloc body du template étendu.

C'est la méthode `include` à l'envers ! 

Et dans le fichier base, voici comment ça se présente :

```twig
{# base.html.twig #}

<body>
  {% block body %}
    Texte par défaut, qui sera remplacé lors de l'extension par un autre fichier
  {% endblock %}
</body>
```
## Mise en pratique
* [Activité twig](<03 Activité 1.md>)