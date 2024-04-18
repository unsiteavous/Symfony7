# Cours 4 : Doctrine et requêtes personnalisées

Si on a envie de faire des requêtes spéciales, on va devoir utiliser doctrine un peu différemment.

En effet, une longue liste de méthodes toutes prêtes existe (find, findBy, findAll...) Mais si on veut récupérer tous les films qui durent moins d'une heure, comme faire ?

## Le QueryBuilder
On pourrait écrire du SQL. Mais comme doctrine est un outil qui nous permet de pouvoir parler avec différents types de base de données (postgresql, mysql, mariaDB, ...) on se limiterait dans la possibilité de changer de langage, en écrivant juste du SQL.

À la place, Doctrine nous permet de créer une requête, et de s'occuper toute seule de la transcrire dans le bon langage pour nous.

Vous avez des exemples de ce genre de requêtes commentées dans les repositories autogénérés.  

Voici comment ça va se passer :

```php
public function findFilmWhereDurationLowerThan( \DateTime $duree): array
{
  // On commence par créer un QueryBuilder, et on donne en paramètre un alias de la table dans laquelle on va chercher. Dans tous les cas, comme on est dans FilmRepository, ce sera dans la table film qu'on cherchera, même si on met f, ou F, ou autre chose. C'est un alias.
    return $this->createQueryBuilder("film")
    // On précise la clause WHERE :
    ->where("film.duree <= :duree")
    // On peut choisir l'ordre des résultats :
    ->orderBy("film.duree","ASC")
    // Le nombre de résultats maximum :
    ->setMaxResults(10)
    // On lui donne le paramètre qu'on lui a promis c'est comme bindParam avec PDO : (les ':' sont facultatifs)
    ->setParameter(":duree", $duree)
    // On exécute la requête :
    ->getQuery()
    // Et enfin on récupère le résultat :
    ->getResult();
}
```

Et dans notre controller, on va créer une nouvelle route :
 
```php
// Dans la route, on récupère le temps (int), en minutes :
#[Route('films/tri-par-duree-{duree}', name:'app_film_tri_duree')]
public function tri(FilmRepository $filmRepository, $duree)
{
  // On transforme les minutes en heures et minutes, et on crée un Datetime avec ça :
  $heure = floor($duree / 60);
  $minutes = $duree % 60;
  $duree = new \DateTime();
  $duree->setTime($heure, $minutes);

  // On appelle notre méthode, pour retrouver tous les films concernés :
  $films = $filmRepository->findFilmWhereDurationLowerThan($duree);

  // On les affiche.
  return $this->render('film/index.html.twig', [
      'films' => $films,
  ]);
}
```

## Activité
[C'est par ici !](<08 Activité 4.md>)