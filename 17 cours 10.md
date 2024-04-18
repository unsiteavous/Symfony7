# Cours 10 : Authentification

Super, nous avons une appli contactable par API, avec des vues, des CRUDs, ... Impressionnant ! 

Mais comment faisons-nous pour gérer les utilisateurs, les connexion, ... ?

À vos lignes de commandes ! 

## Mettre en place l'architecture

[📜 Documentation Symfony Sécurité](https://symfony.com/doc/current/security.html)
### Créer une entité User

la commande classique pour créer une entité est `symfony console make:entity`. Et bien il se trouve que lorsqu'il s'agit des utilisateurs, il y a une commande spéciale, qui fait celle-ci, en mieux, spécialement pour les utilisateurs :

```bash
symfony console make:user
```
On répond aux questions et ... Oh miracle, un utilisateur ! 

Pour le coup, si on veut modifier notre entité par la suite, c'est bien la commande `make:entity` qu'on refera.

Une fois que cela est fait, quelle est la suite déjà ? Créer un fichier de migration, puis faire la migration.

### Authentification
Et pour l'authentification, alors qu'avant, il fallait construire des routes, écouter la base de données avec le repository, faire du calcul, afficher des messages, rediriger, ... 

Maintenant, on fait :

```bash
symfony console make:auth
```
On suit le guide... 

### Et l'inscription ?

Ben... pareil ...

```bash
php bin/console make:registration-form
```
Il y aura potentiellement plusieurs commandes à faire par la suite, en fonction des réponses que vous donnez.

> **ATTENTION :** des fichiers ont été créés, mais vous devez encore les personnaliser. Dans plusieurs d'entre eux il reste des *TODO*. Ce n'est pas parce que tout est généré automatiquement qu'on a ri 

## Gérer les routes
Il y a plusieurs manières de gérer les routes. Il ne faut pas oublier que symfony travaille avec des fichiers yaml, qui gèrent pas mal de configurations. Je trouve personnellement que ce n'est pas très pratique d'avoir les autorisations des routes qui sont ailleurs que près de mes routes, ça m'oblige à ouvrir plusieurs fichiers pour savoir qui a le droit d'aller sur telle ou telle route. 

À la place, je préfère utiliser `isGranted`.
 
[📜 Documentation Symfony isGrandted (vieux)](https://symfony.com/bundles/SensioFrameworkExtraBundle/current/annotations/security.html#isgranted) 
[📜 Documentation Symfony (nouveau)](https://symfony.com/doc/current/security.html#security-securing-controller-attributes)  
[📜 Documentation SymfonyCast](https://symfonycasts.com/screencast/symfony-security/is-auth)


Cette annotation permettra de savoir quel rôle peut accéder à quelle partie de mon application.
`isGranted` accepte comme argument les rôles, mais pas que. Voici une liste des arguments possibles qu'on peut avoir dans cette annotation :

* ROLE_USER, ROLE_ADMIN, ROLE_SUPER_ADMIN, ROLE_PERSONNALISÉ...  
* IS_AUTHENTICATED_FULLY  
* IS_AUTHENTICATED_REMEMBERED  
* PUBLIC_ACCESS  

| Argument | style d'autorisation |
|-----------|-----------------------|
|**ROLE_USER** | autorisé qu'aux USER simples (rôle par défaut dans symfony) |
|**ROLE_ADMIN** | Autorisé qu'aux admins |
|**ROLE_SUPER_ADMIN** | Autorisé qu'aux super-admins |
|**ROLE_PERSONNALISÉ** | Autorisé qu'aux ... [votre rôle] |
|**IS_AUTHENTICATED_FULLY** | Autorisation accordée à tout utilisateur entièrement authentifié par les informations d'identification fournies à chaque demande.|
|**IS_AUTHENTICATED_REMEMBERED** | Autorisation accordée à tout utilisateur authentifié par un cookie "remember me". existe depuis Symfony 5.1. |
|IS_AUTHENTICATED_ANONYMOUSLY | Autorisation accordée à tout utilisateur non authentifié.|
|**IS_ANONYMOUS** | Seulement les visiteurs ont ça. Depuis Symfony 5.1.
|IS_IMPERSONATOR | uniquement les utilisateurs qui se font passer pour un autre utilisateur dans la session. Depuis Symfony 5.1.|
|**PUBLIC_ACCESS** | Accès autorisé à tous quelque soit son rôle ou son état |
|*OWNER* | Autorisation accordée à l'utilisateur propriétaire d'une ressource. Cette autorisation est généralement utilisée dans le contexte de contrôle d'accès basé sur le propriétaire.|
|*VIEW* | Autorisation accordée pour visualiser une ressource.|
|*EDIT* | Autorisation accordée pour éditer une ressource.|
|*DELETE* | Autorisation accordée pour supprimer une ressource.|
|*CREATE* | Autorisation accordée pour créer une nouvelle ressource.|
|*LIST* | Autorisation accordée pour afficher une liste de ressources.|
|*EXPORT* | Autorisation accordée pour exporter des données.|
|*IMPORT* | Autorisation accordée pour importer des données.|

*En gras, les plus utiles, en italique, les spécifiques à des actions du CRUD.*

voici tout ce qu'on peut dire à `isGranted` :
```php
#[isGranted('ROLE_ADMIN', statusCode: 423, message: "Vous n'avez pas les droits pour accéder à cette page")]
```

### Et dans twig ?
L'avantage de cette annotation `isGranted` sur les autres méthodes qui existent dans symfony pour vérifier les rôles, c'est qu'elle ressemble beaucoup à ce qu'on fait en twig pour la même chose : 

```twig
{% if is_granted('ROLE_ADMIN') %}
    <a href="...">Delete</a>
{% endif %}
```

ou encore 
```twig
{% if is_granted('IS_AUTHENTICATED_REMEMBERED') %}
    <a class="link" href="{{ path('logout') }}">Logout</a>
{% else %}
    <a class="link" href="{{ path('login_form') }}">Login</a>
{% endif %}
```
[📜 Une autre explication des différences des autorisation.](https://symfonycasts.com/screencast/symfony2-ep2/twig-security-is-authenticated)