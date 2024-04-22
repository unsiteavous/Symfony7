# Cours 1 : Création d'un nouveau projet

Avec symfony CLI, nous allons pouvoir utiliser des lignes de commande simplifiées.

## 1. Installation
Lorsqu'on fait une application complète, qui gèrera aussi l'affichage des vues, on a besoin d'une webapp :  
```bash
symfony new --webapp NomDossier
```
Si nous voulions faire une application API ou un micro-service :

```bash
symfony new NomDossier
```

### Découverte de l'architecture

Vous connaissez déjà beaucoup de choses présentes dans le dossier créé :

```
assets         // stocke le css, js, img, ...
bin            // nous permet d'intéragir avec notre app via la console
config         // stocke des données de configuration pour symfony
migrations     // contient les migrations de la BDD
public         // contient juste un fichier index.php => porte d'entrée
src            // contient le code de notre app
templates      // contient les vues de notre app
tests          // contient les fichiers de test
translations   // les traductions
var            // contient les fichiers de log, erreur, ...
vendor         // contient les fichiers des librairies annexes
.env           // nous permet de personnaliser notre environnement (Base de données, variables globales, ...)
```

### Documentation 
Symfony est livrée avec la documentation complète (en anglais) :
https://symfony.com/doc/current/index.html  
**Prenez le temps de la lire et de vous y référer !!**

## 2. Première commande

Symfony a tout un tas de commandes toutes prêtes. Pour en voir la liste, exécutez cette commande :
```
symfony console
```

notre première commande nous permettra de voir le rendu web de notre application. Symfony a un émulateur de serveur interne. Pas besoin de wamp dans un premier temps. (*Nous aurons tout de même besoin de wamp pour faire tourner mysql notamment, et pour simuler une mise en prod*).

```
symfony server:start -d
```

le drapeau `-d` permet de faire tourner le serveur en arrière-plan, pour vous permettre de continuer à pouvoir utiliser votre ligne de commande.

## 3. Première route

Nous allons nous construire notre première route.
Dans notre MVC fait main, nous avions un fichier `router.php`, qui écoutait l'url demandée au serveur. Ici, les choses vont être un peu différentes.

Tout d'abord, nous allons créer un contrôleur qui répondra à notre route :
```
symfony console make:Controller HomeController
```
Vous constatez que deux fichier sont apparus : le `HomeController`, et un fichier dans `Templates/home/index.html.twig`. Nous nous occuperons des templates plus tard.

Pour l'instant, la classe du HomeController est barbare, commentez-la. Vous pouvez simplement écrire à la place :
```php
class HomeController {
    public function index(): Response {
        return new Response("Hello World !");
    }
}
```

Maintenant, dans le fichier `config/routes.yaml` vous pouvez ajouter ceci :

```yaml
home:
    path: /home
    controller: App\Controller\HomeController::index
```

ce qui veut dire qu'on a une route home, qui sera appelée lorsqu'on aura /home dans l'url, et qui appelera à son tour la méthode index du HomeController.

Rendez-vous sur votre site /home, vous devriez voir le résultat.

Si on veut voir une vue, plutôt que de faire une response directe, on va utiliser la méthode `render`, que je vous ai faite découvrir !
```php
class HomeController {
    public function index(): Response {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
```

Bravo ! Vous venez de faire vorte première route ! C'est de cette manière que vous pourrez construire vos routes, si vous souhaitez garder un fichier unique pour toutes les routes. Il existe cependant une autre manière de faire, qu'on voit juste après.

### Routes en Annotations
Symfony propose de noter les routes sous un autre format, directement dans les contrôleurs. Cela fait disparaître le fichier routes en tant que tel. Directement au-dessus de chaque méthode de nos contrôleurs, nous retrouverons une ligne qui permettra de dire par quelle route cette méthode sera appelée.

> ATTENTION : tous les frameworks ne fonctionnent pas comme symfony sur ce point. Laravel par exemple, qui est un autre framework PHP, garde le fichier routes.php, et sépare comme on l'a appris les routes des contrôleurs. 

Pour découvrir comment ça marche, on va revenir en arrière : supprimez la route du fichier `config/routes.yaml`, et revenez à la version initial dans le HomeController.

```php
class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
```
Dans ce code, on découvre que la route est mise en annotation au-dessus de la méthode associée. 
On voit que cette méthode render vient d'une classe `AbstractController`, et pas d'un trait. Mais dans l'idée, c'est tout pareil !

### Récupérer un paramètre

On est en php. Donc toutes les variables qui existent de base en natif existe toujours. Vous pouvez donc encore utiliser $_POST ou $_GET. 

Maintenant, Symfony propose là aussi des choses toutes prêtes pour ça. On va avoir un objet `Request` et un objet `Response`, qu'on va pouvoir utiliser pour écouter la requête et formuler la réponse.

par exemple, `$request->query->get('prenom')` équivaut à `$_GET['prenom']`.

Mais on peut aussi se servir des annotations pour ça :

```php
class HomeController extends AbstractController
{
    #[Route('/home/{prenom}', name: 'app_home')]
    public function index($prenom = "inconnu"): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'prenom' => $prenom
        ]);
    }
}
```
Ici on écoute la fin de l'url et on la transforme en variable. On la passe ensuite à la vue.

### Quelle route prendre ?
Si vous avez envie de voir toutes les routes de votre application, il suffit de faire cette ligne de commande, qui va vous afficher la liste de toutes les routes existantes :

```bash
symfony console debug:router
```


### Quoi répondre ?

On peut faire trois sortes de réponses :
* des Responses 
  ```php
  return new Response("Bonjour $prenom !");
  ```
  ça retourne juste une info au bon format.

* Des vues
  ```php
  return $this->render('home/index.html.twig', [
      'controller_name' => 'HomeController',
      'prenom' => $prenom
  ]);
  ```
  ça retourne une vue construite avec twig (on verra ça plus tard)

* Des JsonResponses
  ```php
  return new JsonResponse(['prenom' => $prenom, 'status' => 'OK']);
  ```
  ça retourne une vue au format JSON, avec les bons header et tout ce qu'il faut.

Maintenant qu'on a une route qui marche, on veut afficher une vue. Allons voir dans le détail ce qu'apporte twig.

C'est dans le [cours 2](<02 cours 2.md>) !