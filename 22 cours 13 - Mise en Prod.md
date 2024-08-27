# Mise en production
Dans une idée de déploiement continu, il est souhaitable de faire des mises en production régulièrement.

La première chose à laquelle on va se confronter, c'est la capacité à appeler l'API depuis ailleurs que ce serveur. Quand on travaille avec postman, on a l'impression que tout fonctionne, mais c'est une illusion. Postman fait des appels api qui sont passés comme s'ils venaient d'un serveur, et pas d'un front. Cela change beaucoup de choses. Et donc lorsqu'on essaie de faire un fetch depuis JS, on s'aperçoit que les requêtes sont bloquées à cause des CORS.

## Disponibilité d'API

On va donc devoir dire à symfony de nous laisser accéder à notre API, même en local ! 
Pour cela, on va installer un bundle supplémentaire, qui va implémenter pour nous la gestion des *Cross-Origin Resource Sharing*.  
[📜 Documentation de NelmioCorsBundle](https://symfony.com/bundles/NelmioCorsBundle/current/index.html)

On va commencer par demander à composer de l'installer :

```bash
composer require nelmio/cors-bundle
```
Ensuite, dans le fichier `nelmio_cors.yaml`, on va venir lui dire qu'on veut que dans le cas de nos urls d'API, on autorise le site front à y accéder : 

```yaml
nelmio_cors:
    defaults:
        origin_regex: true
        allow_origin: ['%env(CORS_ALLOW_ORIGIN)%']
        allow_methods: ['GET', 'OPTIONS', 'POST', 'PUT', 'PATCH', 'DELETE']
        allow_headers: ['Content-Type', 'Authorization']
        expose_headers: ['Link']
        max_age: 3600
    paths:
        '^/api':
            allow_origin: ['http://virtualHost']
```

Tous les réglages par défaut sont intéressants, on peut bien entendu les compléter, les modifier, ou les redéfinir dans nos paths. Vous retrouverez tout ça dans la documentation.

## Mise en prod

[📜 Documentation de la mise en prod de symfony](https://symfony.com/doc/current/deployment.html)

On a beaucoup de chance, comme symfony débarque avec son propre serveur, nous pouvons tester une première mise en ligne avec wampserver.

Pour cela, créez un virtualhost qui pointe sur votre dossier public de votre projet symfony. Mais une fois qu'on se rend sur ce virtualhost, on constate qu'on voit la superbe page d'installation de symfony, mais impossible d'aller sur une page ! le router ne marche pas... Et c'est normal : il n'y a pas de fichier `.htaccess` dans notre projet.

Pour le mettre en place, pas d'inquiétude, symfony a un autre paquet à installer : 

```bash
composer require symfony/apache-pack
```
Et voilà, on peut accéder à notre site ! 

Bon, c'est pas pour autant que nous avons fait une mise en prod. Si on utilise webpack pour le front, il faut compiler avec npm : 

```npm
npm run build
```

Si vous avez modifié le fichier `app.css` (ou que vous en avez ajouté d'autres dans le `app.js`), il va aussi falloir compiler `importmap` :
```bash
symfony console asset-map:compile
```
[📜 Documentation de la compilation des importmap](https://symfony.com/doc/current/frontend/asset_mapper.html#serving-assets-in-dev-vs-prod)

### Passer le `.env` en prod
Et ce n'est pas tout : il faut aussi modifier la configuration de notre site. Quand vous passerez sur le serveur, il faudra bien modifier les infos de BDD.

Pour faire cela, composer nous donne une commande : 
```bash
composer dump-env prod
```
Cela crée un fichier `.env.local.php`, qui sera toujours lu en priorité sur tous les autres .env qui pourraient se trouver dans le coin. Evidemment, c'est le seul qu'on mettra sur le serveur... 

Bon, quand on a fait tout ça, c'est fini ? Et non ! 😜

### Enlever les fichiers inutiles.
Si on verse tout notre projet comme ça sur le serveur, on va mettre un grand nombre de trucs qui servent à rien. 
Pour éviter ça, on va copier seulement quelques fichiers dans un nouveau dossier : 
* assets
* bin
* config
* migrations
* public
* src
* templates
* .env.local.php
* composer.json
* composer.lock
* importmap.php
* symfony.lock

**C'est tout !!!**

Ensuite on fait cette commande :
```bash
composer install --no-dev --optimize-autoloader
```

Cela télécharge uniquement les dépendances nécessaires à la production (par exemple, pas fakerphp), et ça optimise l'autoloader à fond.

Puis il faut nettoyer le cache de Symfony, et le créer spécifiquement pour la production :
```bash
symfony console cache:clear
symfony console cache:warmup --env=prod

```

Et là, ça y est tout est prêt. Si vous retournez à présent sur votre virtualHost, vous pouvez voir votre site comme sit vous étiez en ligne.

Et dans notre cas, pour pouvoir mettre notre application sur le serveur de simplon, on utilisera en plus un fichier nginx.conf comme on a l'habitude de le faire.

Bravo vous avez une app symfony mise en ligne ! 👍👏🎉

## Erreurs ?

Si vous avez des erreurs, vous pouvez rajouter cette ligne à votre fichier `.env.local.php` pour les faire apparaître :

```php
return array (
  // [...]
  'APP_DEBUG' => true,
);
```