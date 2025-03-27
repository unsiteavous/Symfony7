# Découverte du framework Symfony

Nous avons appris à coder à la main une architecture MVC. Nous nous sommes rendus compte de l'importance de travailler avec une architecture pareille, pour la SOC (Separation of Concerns), pour la maintenabilité et l'évolutivité, pour la facilité de travailler en équipe (découpage des fichiers et compréhension partagée du rangement), ...

Mais... c'est long et fastidieux de toujours recommencer à faire la même chose. Vous-même, vous avez trouvé une astuce à cela : le copier-coller, d'un projet à un autre, des fichiers qu'on retrouve tout le temps.

C'est comme ça que Symfony est né !

Afin de gagner du temps et de ne pas avoir à réécrire toujours la même chose, la création de l'architecture et du code a été automatisée, autant que faire se peut.
Nous allons donc découvrir comment, avec des lignes de commande, nous pouvons générer nos fichiers de code et toute l'architecture, pour gagner du temps.

> Gardez à l'esprit qu'un framework permet d'accélérer la production de grosses applications.  
> Dans le cas de création d'une petite application, il est très certainement superflu, lourd et lent d'utiliser un framework.
> 
> **il est inutile d'invoquer l'artillerie lourde pour envoyer une fléchette.**


## Prérequis

Pour travailler avec symfony 7 <ins>(cours 2024)</ins>, on va avoir plusieurs choses à installer.

1. Vérifier que vous avez une version de PHP **supérieure ou égale** à 8.2.
2. Installez [composer](https://getcomposer.org/).
3. Installez [scoop](https://scoop.sh/).
4. Installez **symfony CLI** : `scoop install symfony-cli`
5. vérifiez que tout est ok : `symfony check:requirements`

Et passez au [cours 1](<01 cours 1.md>) 😉


# Reste à faire
* easyadmin
* traduction
* phpUnit