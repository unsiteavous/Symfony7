# Cours 9 : Les retours JSON
Il est probable que vous souhaitiez renvoyer au front des réponses JSON. Outre la super solution de UX Turbo (voir [Pour aller plus loin du cours 6](<10 cours 6.md#pour-aller-plus-loin-spa--😇>)), on va pouvoir utiliser une méthode connexe au `render` : `json`.

Afin de séparer les routes qui vont renvoyer des vues, et celles qui vont gérer notre API, je vous propose de créer un dossier API dans le dossier controllers, et d'y créer de nouveaux controllers, pour chaque élément.

> ## Apparté
> Il faut comprendre un petit peu comment ça va marcher, même si par la suite la méthode `json` fera tout toute seule. 
> Pour transformer un objet en json, on est obliger de le **sérialiser**. On l'a fait déjà en PHP natif, là aussi symfony doit le faire. Il y a un serializer dans Symfony qui est très puissant, qui permet de convertir ce qu'on veut en pleins de formats possibles, ...  
> [📜 Documentation du serializer](https://symfony.com/doc/current/components/serializer.html)
>
> Il se trouve quand on regarde dans le détail ce que fait la méthode `json`, elle s'occupe déjà de sérialiser les données qu'on lui donne. Mais il faut bien le garder en tête, parce que pour déserialiser c'est pareil, on va devoir passer par ça, et que là par contre, on a pas de méthode `json` toute prête.

par exemple, pour l'entité Film :
```php
<?php
// Dans le fichier src\Controller\API\FilmController.php

namespace App\Controller\API;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path:"/api/films")]
class FilmController extends AbstractController {

  #[Route(path:"/firstroute")]
  public function firstRoute()
  {
    return $this->json([
        'message' => 'Premier Envoi en JSON',
        'Status' => 'succès',
    ]);
  }

  #[Route(path:"/")]
  public function index(FilmRepository $filmRepository) 
  {
    $films = $filmRepository->findAll();

    return $this->json($films);
  }
}
```
Hyper simple ! Sauf que... si on fait ça, on a une erreur :

> Ignore on "Proxies\__CG__\App\Entity\Classification::__setInitialized()" cannot be added. Ignore can only be added on methods beginning with "get", "is", "has" or "set".

Cela est provoqué parce qu'on a une boucle qui est en train de se créer : on appelle un film, qui appelle une classification, et la classification appelle la collection des films qui ont des classifications qui appellent... Vous avez compris l'idée.

Donc pour éviter que ça boucle, et pour d'autres raisons qu'on va découvrir juste après, on va ajouter des annotations dans nos entités. 

On va commencer par ajouter ceci à notre méthode du controller :

```php
return $this->json($films, 200, [], [
      "groups"=> "api_film_index"
    ]);
```
Cette option de `group` va nous permettre de récupérer dans les entités tous les éléments qui sont prévus pour être affichés par cette vue. Ensuite dans les entités, on va justement dire si on veut donner ces infos à cette vue. Par exemple, pour un `user`, on ne donnera jamais le champ `password`, ou `rgpd`. On pourra donc les ignorer dans le rendu JSON.

Dans notre entité `film`, on va donc ajouter les annotations :
```php
use Symfony\Component\Serializer\Annotation\Groups;

    #[Groups(['api_film_index'])]
    private ?string $titre = null;
```
Maintenant si vous retournez sur votre route API, vous constatez qu'on récupère bien tous les objets avec seulement leur titre. À nous de mettre toutes les annotations voulues.

> Si le bug n'est pas résolu, c'est que var-exporter n'a pas la bonne version. Attention, cela est circonstanciel : Il est probable que cela soit résolu avec les nouvelles version de symfony, et que cette partie du cours soit obsolète :
>
> Il est possible qu'il faille réinstaller `var-exporter` dans une version antérieure :
> ```bash
> composer require "symfony/var-exporter=7.0.4"
>```
> [*Source à consulter*](https://stackoverflow.com/questions/78285349/ignore-on-proxyclass-setinitialized-cannot-be-added/78407100?noredirect=1)

Et pour les classifications ? C'est quand même utile de les voir ! Si on met l'annotation sur la propriété, on retrouve le bug. Il va falloir le mettre sur le getter cette fois-ci, et dans l'entité `Classification`, on va aussi mettre les annotation devant les champs qu'on veut récupérer : dans ce cas, juste le nom ! 

Et voilà, on a renvoyé du JSON ! 

> Il est possible que vous ayez un problème de dépendance, qui se résout avec cette installation de la librairie manquante :
>
> ```bash
> composer require "symfony/var-exporter"
> ```

## Recevoir du JSON

Pour recevoir du json, c'est un peu plus compliqué. C'est toujours compliqué quand il s'agit de recevoir des données utilisateurs, parce que ça veut dire **vérifier les données**, protéger son appli, renvoyer des messages, ... 

Pour garder une cohérence avec au-dessus, lorsqu'on reçoit les données, on doit :
 * récupérer la requête,
 * la déserializer,
 * la valider, 
 * si la validation est ok, l'enregistrer,
 * répondre quelque chose.

voilà comment ça va se traduire dans la méthode :

```php

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;



#[Route(path:"/new", methods: ['POST'])]
  public function new(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator)
  {
    // On commence par récupérer les données grâce à $request()->getContent()
    // Ensuite, on deserialize le json, pour recomposer un objet categorie, en ne remplissant que les champs autorisés par le groupe :
    $categorie = $serializer->deserialize($request->getContent(), Categorie::class,'json', ["groups" => "api_categorie_index"]);

    // On valide les données reçues :
    $errors = $validator->validate($categorie);

    // S'il y a des erreurs, on s'arrête là et on retourne les erreurs à l'envoyeur :
    if ($errors->count()) {
      $messages = [];
      foreach ($errors as $error) {
        $messages[] = $error->getMessage();
      }
      return $this->json($messages, Response::HTTP_UNPROCESSABLE_ENTITY);
    }else {

      // Sinon on enregistre en base de données :
      $em->persist($categorie);
      $em->flush();
      
      // Si tout s'est bien passé on veut permettre au front de rediriger l'utilisateur sur la bonne page.
      // On va donc lui donner une url.
      $url = $urlGenerator->generate('app_categories_show', ['id' => $categorie->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
      
      return $this->json($categorie, Response::HTTP_CREATED, ['Location' => $url]);
      
    }

  }
  ```

  Si on fait un test avec [postman](https://www.postman.com/downloads/), on voit que si on donne une mauvaise catégorie, on a bien une erreur qui est levée, si on donne une catégorie correctement, elle est enregistrée.

  ## Pour aller plus loin... Factoriser le code

  Il existe néanmoins une manière plus rapide de faire la même chose, en passant par une couche d'abstraction supplémentaire.

```php

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;


#[Route(path: "/new", methods: ['POST'])]
  public function new(

    #[MapRequestPayload(
      serializationContext: [
        'groups' => ['app_categories_new']
      ]
    )]
    Categorie $categorie,
    EntityManagerInterface $em,
    UrlGeneratorInterface $urlGenerator
  ) {

    // Sinon on enregistre en base de données :
    $em->persist($categorie);
    $em->flush();

    // Si tout s'est bien passé on veut permettre au front de rediriger l'utilisateur sur la bonne page.
    // On va donc lui donner une url.
    $url = $urlGenerator->generate('app_categories_show', ['id' => $categorie->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

    return $this->json($categorie, Response::HTTP_CREATED, ['Location' => $url]);
  }
  ```

On ne peut pas utiliser l'injection de dépendance sur des entités. Pour réussir à construire l'entité avec les données reçues, on doit utiliser `MapRequestPayload`, qui va récupérer les données, les serializer, les valider, vérifier qu'elles font bien partie du groupe de champs qu'on peut modifier, et enfin, créer l'objet souhaité. 

## Composer un objet avec des dépendances à d'autres objets
Dans le cas de Film, on veut enregistrer également les catégories associées, et la classification correspondante. Or, le sérializer n'est pas capable de faire cela, parce qu'il ne connait pas ces objets. Il va vouloir forcer la création de nouveaux objets, même si on lui passe l'id d'un objet déjà existant.

On va donc devoir décomposer la requête JSON, et recomposer l'objet nous-même :

```php
#[Route(path: "/new", methods: ['POST'])]
public function new(
    Request $request,
    EntityManagerInterface $em,
    SerializerInterface $serializer,
    UrlGeneratorInterface $urlGenerator,
    ValidatorInterface $validator,
    ClassificationRepository $classificationRepository
) {
    $data = json_decode($request->getContent(), true);

    // On désérialise uniquement les champs scalaires du film.
    // La classification NE doit PAS faire partie du groupe "api_film_index"
    // côté propriété $classification, sinon le serializer recrée un objet détaché.
    $film = $serializer->deserialize($request->getContent(), Film::class, 'json', [
        "groups" => "api_film_index",
    ]);

    // On récupère la classification existante en base plutôt que de laisser
    // le serializer en recréer une instance détachée avec seulement l'id renseigné.
    if (!empty($data['classification']['id'])) {
      $classification = $classificationRepository->find($data['classification']['id']);
      if ($classification) {
        $film->setClassification($classification);
      }
    }

    // On désérialise uniquement les champs scalaires des catégories
    foreach ($data['categories'] as $categoryData) {
      $category = $categoryRepository->find($categoryData['id']);
      if ($category) {
        $film->addCategory($category);
      }
    }

    $errors = $validator->validate($film);

    if ($errors->count()) {
      $messages = [];
      foreach ($errors as $error) {
        $messages[] = $error->getMessage();
      }
      return $this->json($messages, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    $em->persist($film);
    $em->flush();

    $url = $urlGenerator->generate('app_films_show', ['id' => $film->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
    return $this->json($film, Response::HTTP_CREATED, ['Location' => $url]);
}
```

## Activité
Aller à [l'activité 7](<16 Activité 7.md>).