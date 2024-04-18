# Cours 7 : Validation des données

Dans notre controller, nous mettons cette ligne :

```php
if ($form->isSubmitted() && $form->isValid()) {
  // ...
}  
```
Mais ça veut dire quoi, `isValid()` ?  
Comment Symfony est-il capable de savoir si le formulaire soumis est bien valide ?

Et bien c'est parce qu'on va le lui dire, il a beau être super fort, il ne peut pas vérifier les champs sans nos indications pour l'aider.

Pour ça, on va utiliser des `Constraint`.  
[📜 Documentation Symfony](https://symfony.com/doc/current/validation.html#constraints)

Par exemple, pour s'assurer que le titre ne fera pas moins de 5 caractères, et pas plus de 255 (limite imposée par la base de données), on peut ajouter cette assertion :

```php
// Ne pas oublier d'ajouter le use adéquat :
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Length(min: 5, minMessage:"Le message doit faire au minimum 5 caractères.", max: 255 , maxMessage:"Le titre doit faire au plus 255 caractères")]
#[ORM\Column(length: 255, nullable:false)]
private ?string $titre = null;
```

On voit que dans une assertion, on va avoir une valeur liée à la règle à respecter, et un `message`, qui va nous permmettre de spécifier l'erreur à afficher à l'utilisateur s'il ne respecte pas les attentes.

Un autre exemple, si on ne veut pas qu'une quantité puisse être négative :

```php
#[Assert\Positive(message:'La quantité doit être supérieure à 0.')]
#[ORM\Column()]
private ?int $quantite = null;
```

## Chaque entité est unique
Si on a envie de s'assurer qu'on ne puisse pas avoir deux films avec le même titre, ou deux utilisateurs avec le même mail, ou autre, on a besoin de mettre des assertions générales, à l'entité, et pas seulement au champ.

C'est important de dissocier l'unicité de l'entité, aux règles qui concernent juste un champ de formulaire. Les deux ne sont pas gérés par les mêmes organes de symfony : la validation des champs est gérée par le validator, l'unicité des entités est gérée par doctrine (puisqu'il faut se référer à la base de données).

Juste avant la classe, on viendra donc mettre les assertions nécessaires :

```php
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

// On spécifie par quel propriété l'entité doit être unique.
// On peut mettre plusieurs UniqueEntity à la suite.
#[UniqueEntity(fields: 'titre', message: 'Ce titre est déjà utilisé')]
class Film
{
  // ...
}

```

## Champ non nul
Si on ne veut pas qu'un champ soit laissé vide, on va pouvoir utiliser l'assertion `#[Assert\NotBlank()]`. Attention, il y a aussi une assertion NotNull(), mais elle vérifie uniquement si la valeur est égale à null, et pas si c'est une chaine de caractère vide, un tableau vide, ...

Petite subtilité, sur certains champs, et particulièrement les champs de texte, si on ne met rien dans le champ, le formulaire va appeler le setter de notre entité pour lui passer `null`, et pas `''`. Or, si on regarde nos setters, ils attendent forcément une string, et pas un paramètre nullable. Ce qui est normal, puisqu'on ne veut pas que le paramètre soit nul ! 

Le problème, c'est que la validation intervient **Après** l'appel des setters... Donc ça va nous faire une méchante erreur qui nous dira qu'on essaie de donner à notre setter un null alors qu'il attend une string... 

Pour pallier à cette erreur, on doit aller modifier le FormType, en ajoutant ce qu'on veut recevoir si le champ est laissé vide, avec `empty_data` :

```php
class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod("POST")
            ->add('titre', TextType::class, [
                'empty_data' => ''
            ])
            // ...
```

Et oui, même si symfony c'est génial, il reste toujours des trucs bizarres et relous... 

> Prenez bien la mesure de cela :   
> **Si vous ne faites pas ce genre de chose, le premier hacker qui passe et qui a envie de tester des trucs pour faire tomber votre appli fera apparaître une erreur symfony, plutôt qu'un joli message d'erreur.**
>
> Et même sans parler de hacker, c'est très bizarre, quand on est un utilisateur lambda, qu'on soumet un formulaire en pensant qu'un champs est facultatif, et que ça casse le site, plutôt que de nous faire apparaître une erreur gérées pour l'UX.

Une autre option pourrait être de venir dans le setter, et d'ajouter un ? devant le typage, par exemple :

```php
public function setTitre(?string $titre): static
{
    $this->titre = $titre;

    return $this;
}
```

Depuis PHP 8, cela signifie qu'on attend soit null soit une string.

## Pour aller plus loin ... Construire ses propres règles de validation

Il y a des cas où vous avez envie de valider les données selon vos propres critères. Dans ce cas, symfony vous permet de créer vos propres validateurs, et de les appeler ensuite de la même manière que les autres.

Cela se fera grâce à une ligne de commande :
```bash
symfony console make:validator
```
Cela va vous construire deux fichiers, qui vont vous permettre de créer vous-même votre contrainte. Je ne vais pas rentrer dans le détail ici, voici juste un exemple de contrainte par rapport à une liste antispam :

**[📜 Lire la doc à ce sujet](https://symfony.com/doc/current/validation/custom_constraint.html)**

Dans le premier fichier, nommé `BlocSpam.php` :
```php
<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;


#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BlocSpam extends Constraint
{
    public $message = 'Vous ne pouvez pas utiliser "{{ spam }}" dans votre titre.';
    public $blocList = ['spam', 'viagra', 'bitcoin'];

    public function __construct($blocList =null, $message = null, array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->message = $message ?? $this->message;
        $this->blocList = $blocList ?? $this->blocList;
    }
}
```

Dans le second fichier, nommé `BlocSpamValidator.php` :
```php
<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class BlocSpamValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /** @var BlocSpam $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = strtolower($value);

        foreach ($constraint->blocList as $spam) {
            if (str_contains($value, $spam)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ spam }}', $spam)
                    ->addViolation();
            }
        }
    }
}

```
Et enfin, dans mon entité Film, voici comment j'ajoute ma nouvelle validation :

```php

// [...]
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as SelfAssert;

class Film
{
    #[Assert\NotBlank(message:"Le titre ne peut pas être vide.")]
    #[SelfAssert\BlocSpam()]
    #[ORM\Column(length: 255, nullable:false)]
    private ?string $titre = null;
```

