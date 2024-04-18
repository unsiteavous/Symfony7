# Activité 1 : twig

Vous allez construire une seconde route, pour faire votre entrainement twig :
1. Construisez une nouvelle méthode dans votre HomeController, qui sera appelée par la route "twig". 
2. Copiez-collez ce code dedans :
    ```php
    $user = [
        'nom' => 'Dupont',
        'prenom' => 'Jean',
        'age' => 32,
        'slogan' => '<center><b>Twig c\'est génial !</b></center>',
        'activated' => TRUE,
        'createdAt' => new DateTime('2020-12-21 15:27:30')
    ];
    ```
3. Rendez une vue nommée `home/exo1.html.twig`, et passez-lui le `$user`.

<br>

---

Dans le fichier `base.html.twig`, incluez le cdn de bootstrap (ou de tailwind, comme vous préférez, dans ce cas, c'est dans le bloc script qu'il faut le mettre).

Autour du bloc body, venez mettre une div avec comme class container, et des marges en haut et en bas.
<br>

---

Dans le fichier twig en question, copiez-collez ce code :

```twig
étendez le fichier base.html.twig

{% block title 'Activité twig' %}
  
{% block body %}

  incluez un nouveau fichier header.html.twig que vous créerez dans un dossier includes.

  utilisez set pour assigner à une variable NomComplet, le prénom et le nom de user.


  Faire un titre : Bonjour NomComplet


  Si les compte est activé, afficher ✅ Compte activé


  Afficher la date à laquelle l'user a été créé, au format suivant : jour mois Année.


  Formater la date avec format_datetime pour afficher le jour et le mois en entier en français, mais sans l'heure
  Vous aurez une commande à faire avec composer pour installer le package nécessaire.


  Pour chaque ligne de user, afficher la clé et la valeur dans un tableau.


  Afficher le slogan, avec les balises HTML éxecutées. Il faut savoir le faire, même s'il faut être très attentif à la sécurité, si c'est du code qui vient de l'utilisateur.



  Faites un bouton pour revenir sur la page home, avec url, puis un second avec path.
  Path est souvent utilisé pour les routes internes, et url pour les routes externes.

  

  Mettez ces deux liens dans une condition, qui écoute la route actuelle : Si la route est home, on n'affiche rien, sinon on les affiche.
  On mettra ensuite tout ça dans le fichier header.html.twig.
  

  
{% endblock %}
```

Et les formulaires ? On verra ça bientôt ! 😉

Rendez-vous pour la suite dans le [cours 3](<04 cours 3.md>).