# Cours 5 : Les formulaires
Nous avons vu dans le [cours précédent](<07 cours 4.md>) comment afficher ce qu'on a en base de données, grâce à Doctrine. Mais comment fait-on pour écrire en base de données ??

Dans un premier temps, nous allons découvrir comment Symfony nous permet d'accélérer grandement la production des formulaires, et le traitement des données.

Ensuite nous verrons comment parler à la BDD pour la création, la mise à jour ou la suppression d'une ressource, grâce à l'entity manager.

## Créer un formulaire
A votre avis ?  
Avec une ligne de commande bien sûr ! 😉

```bash
symfony console make:form
```

Cela nous crée un nouveau dossier et fichier : `src\Form\FilmType.php`. 

Si nous allons voir ce qu'il y a dedans, on se rend compte que symfony a construit tout seul un FilmType, avec tous les champs du formulaires tirés des propriétés de notre entité. Même pour les relations avec les autres classes, par exemple les catégories, tout est construit, c'est très impressionnant.

Pour utiliser notre formulaire, dans la route de notre controller qui correspondra, il nous faudra l'instancier. L'abstract Controller a une méthode `createForm()` que nous allons utiliser en lui spécifiant le formulaire à utiliser, et dans le cas d'une modification, l'objet à modifier.

```php
// Création
$form = $this->createForm(FilmType::class);

// Modification
$form = $this->createForm(FilmType::class, $film);
```
Et on le donnera comme variable supplémentaire à notre `render`.

<br>

## Afficher le formulaire

Twig de son côté a une méthode `form()` à laquelle on passera la variable form qu'on aura créé, et qui met en forme tout seul le formulaire. ça fait beaucoup de fois le mot form dans une seule phrase, mais ne vous inquiétez pas on va s'en sortir ! 

```twig
  // La fonction form() à laquelle on passe le formulaire (variable form, son nom peut évidemment changer)

  {{ form(form) }}
```

Si on regarde le résultat, on voit que tout les champs ont été mis en place, les balises `<form>` aussi, directement avec la méthode POST. On s'aperçoit qu'il n'y a pas l'attribut `action` à ce formulaire, donc par défaut ce sera la même route en méthode post qui sera appelée.

On voit par ailleurs que les catégories ou les classifications nous sont données selon leur ID, et pas leur nom, ce qui n'est pas super pratique pour l'utilisateur. Pour remédier à ça, on va changer dans le FilmType le `choiceLabel`.

On voit enfin que le formulaire comporte un dernier champ caché, avec un token, qui permet de s'assurer que le formulaire soumis viendra bien de cette page là. Cela permet de contrer la [faille CSRF](https://fr.wikipedia.org/wiki/Cross-site_request_forgery).

Si on veut mettre un peu de style par défaut, symfony nous propose des choses simplissimes : 

Dans le fichier `config/packages/twig.yaml`, on va pouvoir ajouter ce code :

```yaml
twig: # Attention existe déjà, faut pas l'ajouter ça ! 
    form_themes: ['bootstrap_5_layout.html.twig']

    # Vous pouvez utiliser tailwind si vous préférez :
    form_themes: ['tailwind_2_layout.html.twig']
```

Et maintenant si vous retournez sur votre formulaire, magie il est mis en forme avec bootstrap... C'est pas merveilleux ?

Si vous voulez aller voir comment les formulaires sont mis en forme, vous pouvez aller lire le fichier `vendor\symfony\twig-bridge\Resources\views\Form\bootstrap_5_layout.html.twig`  
(ou `vendor\symfony\twig-bridge\Resources\views\Form\tailwind_2_layout.html.twig` si vous utilisez tailwind)

## Et le bouton de soumission ??

Et ben oui, on a un joli formulaire, mais on ne peut pas le soumettre ! 
On a deux possibilités, soit en twig, on utilise `form_start`, `form_rest` et `form_end`, et on ajoute le bouton à la main, soit on va modifier le FilmType.

Pour la seconde option, on devra rajouter une ligne à la fin des champ, comme ceci :

```php
->add('submit', SubmitType::class, [
  'label'=> 'Enregistrer'
]);
```
Le premier paramètre permet de savoir à quel propriété de notre entité on se réfère. Par exemple, `->add('titre')` correspond au titre de mon entité film. 

Dans notre cas, on ne se réfère à rien, on peut donc donner le nom qu'on veut. On aurait pu faire :

```php
->add('Enregistrer', SubmitType::class);
```
ça aurait donné pareil.  
Le second paramètre permet de spécifier le type de champ que c'est. Par défaut, symfony sait trouver le type de champ en fonction du type de propriété (champ date, ...) mais si vous avez envie de changer un champ, ou un label, ou autre, vous pourrez évidemment venir modifier des lignes.

Lisez la [📜 documentation à ce sujet](https://symfony.com/doc/current/reference/forms/types/form.html).

## Le traitement

Si on retourne dans notre controller et qu'on écoute la même route en méthode POST à présent, nous allons pouvoir recevoir les données.

```php
if ($request->getMethod() === 'POST') {
    dd($_POST);
}
```
> `dd()` est une fonction de symfony qui veut dire "dump and die".  
> Cela permet de faire l'équivalent de notre var_dump() puis die; habituel.
> Si vous ne voulez pas faire die, vous pouvez utiliser juste dump().

Ça c'est la méthode en php natif. Symfony a revisité toute les interraction back et front, et nous permet d'automatiser un certain nombre de choses.

Avec les formulaires viennent tout un lot de méthodes qui nous permettent de récupérer les données, de les vérifier, les valider, ...

```php
#[Route('film/{id}/edit', name:'app_film_edit')]
public function update(Film $film, Request $request): Response
{
  // Vous remarquez que la fonction récupère un film directement : en sous-entendu, symfony est capable, juste avec l'id récupéré dans l'url, de contacter tout seul Doctrine pour récupérer le film associé. Pas mal !

  // on crée le formulaire à afficher :
  $form = $this->createForm(FilmType::class, $film);

  // on vérifie si le formulaire a été soumis, en récupérant le contenu de la requête :
  $form->handleRequest($request);

  // Si le formulaire est soumis & valide, alors on instancie notre film avec les données récupérées.
  // On reviendra sur la validation des données un peu plus loin.
  if ($form->isSubmitted() && $form->isValid()) {
      $film = $form->getData();

      // Suite du traitement.
  }

  // Affichage de la vue par défaut :
  return $this->render('film/edit.html.twig', ['film'=> $film,'form'=> $form]);
}
```

## Enregistrement des données

Pour enregistrer en Base de Données les informations requeillies, on va faire appel à l'entity Manager. C'est un outil qui travaille de pair avec Doctrine, et qui est capable de faire tout un tas de choses tout seul, comme par exemple savoir si le film qu'on a instancié existe en BDD, s'il doit être mis à jour, créé, ou rien du tout, tout cela avec deux méthodes.

### Objet récupéré en BDD
Si a un moment on a fait un `find` (avec le filmRepository par exemple), l'entity manager connait déjà l'objet.

à ce moment-là, si on veut le modifier, on a juste à appeler un setter, puis pour enregistrer les informations, à faire cette ligne :

```php
$em = new EntityManagerInterface;
$repo = new FilmRepository;

$film = $repo->find('id');
$film->setTitre('Titre mis à jour');

$em->flush();
```
Et c'est tout ! avec flush, l'entity Manager sait qu'il doit mettre à jour ce qui n'est pas identique entre les objets qu'il a en mémoire et la base de données.

### Objet créé encore inexistant en BDD

Dans le cas où on crée un objet, l'entity Manager ne le connait pas encore. Il faut donc d'abord lui demander de l'ajouter à sa mémoire, on appelle ça faire persister les données.

```php
$em = new EntityManagerInterface;

$film = new Film;
$film->setTitre('Nouveau film');
//  ...

$em->persist();
$em->flush();
```

### Supprimer un film
Enfin, c'est aussi l'entity manager qui nous permetttra de supprimer un film, avec la méthode `remove` :

```php
$em = new EntityManagerInterface;
$repo = new FilmRepository;

$film = $repo->find('id');

$em->remove($film);
$em->flush();
```

### Optimisation 
Quand on appelle l'entity Manager, comme son nom l'indique, il gère les entités. Il peut donc aussi nous permettre de retrouver les repositories associés, dans avoir besoin d'appeler le repository aussi. 

```php
$em = new EntityManagerInterface;
$film = $em->getRepository(Film::class)->find('id');
```
