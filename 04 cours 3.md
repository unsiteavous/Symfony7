# Doctrine
On a des routes, des controllers, des vues. Mais comment intéragissons-nous avec la base de données ?

Pour cela, symfony utilise un ORM (objet relational mapping), c'est-à-dire un objet qui va se positionner entre notre code et la base de données. C'est lui qui va contacter la base de données pour nous.

Concrètement, ça veut dire :
* Réduction du code à créer
* On n'écrit plus de SQL
* Pas besoin de se soucier de la BDD, des migrations, ....
* Possibilité de changer de système de base de données sans avoir à changer une ligne de code, c'est l'ORM qui change sa manière de parler aux BDD. ça rend notre projet beaucoup plus adaptable, évolutif et maintenable.

Mais les ORM ont aussi des limites : 
* difficulté à gérer (ou ne gèrent pas du tout) les requêtes complexes (jointures, imbrications, ...)
* Est parfois plus lent, plus complexe qu'une simple requête (encore une question d'artillerie lourde pour envoyer une fléchette...)

Un très bon article présente quelques manière de faire des requêtes lorsque l'ORM est limité : 
https://www.wanadevdigital.fr/56-comment-realiser-de-belles-requetes-sql-avec-doctrine/

---

Dans symfony, l'ORM utilisé est Doctrine.
C'est grâce à lui qu'on pourra contacter la BDD.

## Connexion à la Base de Données
Pour que Doctrine puisse se connecter, on doit lui donner les accès à la base de données. Cela se situe dans le fichier `.env` à la racine de notre architecture.

Nous utiliserons `mysql`. vous devez donc commenter la ligne postgresql, et décommenter la bonne.  
Créez une base de données pour l'exercice, et complétez la ligne du `.env` avec les bonnes infos.


## Création d'une entité
Doctrine se base sur nos entités pour :
- créer les tables en BDD
- faire le CRUD du repository associé

La prochaine étape est donc de créer une entité. 
Et bien là aussi, tout se fait avec une commande.

```bash
symfony console make:entity
```
la console nous guide ensuite avec des questions. Quand vous avez un doute, laissez la réponse par défaut.

Une fois que l'entité est créée, si on veut la compléter, on retape la même commande, on rerentre le même nom, et la console saura que cette entité existe déjà, et qu'on veut donc la reprendre. 
```bash
symfony console make:entity NomEntité

ou

symfony console make:entity
 # et on marque le nom comme la première fois lorsqu'il est demandé.

```

### Deux fichiers créés
Si on regarde ce que nous a fais la console, on remarque que deux fichiers ont été créé. une entité et le repository associé. 
Dans le repository, il semble ne rien avoir, mais si vous suivez le lien du `ServiceEntityRepository`, puis vous suivez le chemin de l'`EntityRepository` (chemin d'accès : `vendor\doctrine\orm\src\EntityRepository.php`), vous découvrirez toutes les méthodes possibles qu'on aura sur toutes nos entités :
* find()
* findAll()
* findBy()
* findOneBy()
* count()
* ...


Dans l'entité, on retrouve toutes les propriétés, les getters et les setters comme d'habitude, et on remarque que symfony a créé tout seul tout ça avec les bons typages.

On remarque également qu'il a ajouté des annotations, qui serviront à doctrine pour enregistrer les informations en base de données. 

Il nous est possible d'affiner ce mapping, afin d'être plus précis dans ce qu'on veut enregistrer en BDD.
Retrouvez tous les typages possibles dans la [📜 documentation de Doctrine](https://www.doctrine-project.org/projects/doctrine-orm/en/3.1/reference/attributes-reference.html).


## Enregistrement en Base de Données
Maintenant que nos entités sont créées, on va pouvoir mettre en place notre base de données.

Ce n'est pas grave si tout n'est pas en place dès le début, comme dans le précédent projet, nous pouvons avoir plusieurs fichiers de migration, pour faire évoluer le projet au fur et à mesure.

Pour faire une migration, on va faire la commande que la console nous soufflait à la fin de la création d'une entité :

```bash
symfony console make:migration
```
Cette commande crée un fichier qui contient le sql nécessaire à la réalisation des tables (ou des modifications) en BDD. 

Et maintenant que la migration est prête, on va pouvoir demander à doctrine de l'envoyer en BDD.

```bash
symfony console doctrine:migrations:migrate
ou
symfony console d:m:m # version avec sucre syntaxique
```
Cela aura pour effet de reporter en base de données tous les changements (ajouts, suppressions, modifications, ...) sur nos tables.

S'il y a plusieurs fichiers de migration, Doctrine les lit toujours dans l'ordre, du plus ancien au plus récent. Il est bien sûr possible de revenir en arrière (`rollback`), de n'exécuter qu'un seul fichier en donnant son nom, ... Lisez la documentation pour en savoir plus.

Si on va voir en base de données, on se rend compte que doctrine a créé toute seule les tables, mais aussi les tables intermédiaires, les clefs etrangères, ... Pas mal non ?

