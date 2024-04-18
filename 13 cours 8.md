# Fabrication du CRUD
Nous avons vu jusque là différentes commandes qui nous permettaient de construire le CRUD, avec un peu de code à la main au milieu, pour faire toutes les routes, les méthodes, les vues, ...

## Rappels

voici les différentes étapes que nous suivions jusque-là :

1. Création de l'entité
    ```
    symfony console make:entity
    ```
2. Création de la migration et migration en BDD
   ```
   symfony console make:migration

   symfony console doctrine:migrations:migrate
   ```
3. Création du controller (et de sa vue associée)
    ```
    symfony console make:controller
    ```
4. création du formulaire
    ```
    symfony console make:form
    ```
5. À la main, création 
   1. des différentes routes,
    ```php
    #[Route('/classification/{id}', name: 'nom_route', methods: ['GET'])]
    ```
   2. des différentes méthodes du controller
    ```php
    public function show(Classification $classification): Response
    {
        return $this->render('classification/show.html.twig', [
            'classification' => $classification,
        ]);
    }
    ```
   3. des différentes vues
   ```twig
   {% extends 'base.html.twig' %}

    {% block title %}Classification{% endblock %}

    {% block body %}
        <h1>Classification</h1>

        <table class="table">
            <tbody>
                <tr>
                    <th>Id</th>
                    <td>{{ classification.id }}</td>
                </tr>
                <tr>
                    <th>Nom</th>
                    <td>{{ classification.nom }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ classification.description }}</td>
                </tr>
            </tbody>
        </table>

        <a href="{{ path('app_classification_index') }}">Retour à la liste</a>

        <a href="{{ path('app_classification_edit', {'id': classification.id}) }}">éditer</a>

        {{ include('classification/_delete_form.html.twig') }}
    {% endblock %}
   ```

Déjà cela nous a fait gagner un temps considérable, mais aussi nous a permis de construire notre app sans se soucier de questions de sécurité, de portabilité de l'application entre l'environnement de dev et de prod, de maintenabilité, ...

## Toujours plus vite

Il existe cependant une commande qui va nous permettre de faire tout ce qu'on a fait au-dessus d'un seul coup :

```bash
# création de l'entité
symfony console make:entity

# création de la migration puis migration
symfony console make:migration
symfony console d:m:m

# la commande magique
symfony console make:crud
```

C'est tellement puissant que c'en est déconcertant, n'est-ce pas ? 😉

Attention, il y a toujours des petites améliorations à apporter, ce n'est pas parce que le code est généralisé qu'il est parfait du premier coup.


