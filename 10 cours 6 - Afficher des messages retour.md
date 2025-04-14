# Cours 6 : afficher des messages à l'utilisateur

Lorsque l'utilisateur fait une action, il faut lui dire comment ça c'est passé. Est-ce que j'ai réussi à modifier mon mot de passe ? Est-ce que l'article a bien été ajouté ? Pourquoi est-ce que mon paiement a échoué ? Pourquoi n'ai-je pas accès à cette ressource ? Pourquoi le formulaire n'a pas pu aboutir ? ...

L'expérience utilisateur est un métier à part entière. Offir la meilleure expérience utilisateur c'est prendre soin de ses visiteurs, en leur permettant d'avoir une utilisation fluide de notre site.

**Les messages sont indispensables aujourd'hui.**

## Redirections
Dans le cas où la modification de notre film s'est bien passé, on a envie d'être redirigé vers la page de ce film, pour pouvoir voir le résultat. Pour cela, le controller n'utilisera pas `header()` comme nous avions l'habitude de le faire jusque là, mais `redirectToRoute()` :

```php
return $this->redirectToRoute('app_show_film', ['id' => $film->getId()]);
```

Cette méthode a besoin du nom de la route, pas de l'url, ni du fichier twig ! et elle a aussi besoin du paramètre que la route écoute. Ici, c'est l'Id du film.

Cela nous permet donc de rediriger l'utilisateur après une modification réussie. Mais ça ne donne aucune indication à l'utilisateur sur le fait que tout s'est bien passé ! 

## Messages
On pourrait mettre un message en plus dans les paramètres, comme on le faisait auparavant. Ajouter un message en GET, puis écouter l'url pour afficher le message.

Symfony nous propose de mettre le message en session, pour pouvoir le récupérer facilement une fois la redirection faite, et de le supprimer après la prochaine lecture. Une sorte de message à usage unique.

On fait cela grâce à `addflash()` :

```php
$this->addFlash('success','Le film a bien été modifié');
```

Ensuite, il faut l'afficher dans notre template :

```twig
{% for message in app.flashes('success') %}
  <div class="flash-success">
    {{ message | join('. <br>')}}
  </div>
{% endfor %}
```
Dans cet exemple, on récupère tous les messages qui sont des succès dans le tableau des flaches. et pour chacun d'eux, on les affiche, en les joignant les uns aux autres avec un retour à la ligne. Tout cela se retrouve dans la documentation de symfony et de twig.

## Pour aller plus loin... SPA ? 😇
Depuis la version 6.2 de Symfony, il y a un bundle (paquet) installé par défaut, qui permet de préférer faire autant que possible des requêtes fetch ou ajax, plutôt que de recharger la page.

Vous connaissez le but de la manœuvre... la SPA ! 

De plus en plus de sites, pour une meilleure expérience utilisateur, une plus grande rapidité d'utilisation, de fluidité, et d'économie de bande passante, choisissent de faire des requêtes asynchrones plutôt que de recharger toute la page à chaque action utilisateur.

Il nous est tout à fait possible de continuer à faire des requêtes fetch à la main bien entendu : symfony n'empêche jamais l'utilisation des langages natifs.

Mais on peut aussi utiliser UX Turbo, qui de toute manière est déjà présent sans que vous l'ayez demandé. 

Ce qui est très pratique, c'est que si la requête ne peut pas aboutir avec UX Turbo, automatique Symfony écoute la requête normale, et simulera un rechargement de la page. En gros si ça marche tant mieux, sinon on continue comme avant.

Ce qu'il faut bien avoir en tête avant de commencer, c'est qu'en implémentant ce genre de chose, on aura souvent tendance à quand même faire les routes pour les requêtes normales, au cas où quelque chose empêcherait UX Turbo de bien s'exécuter. (ça ressemble à faire deux fois le travail, et oui... mais chut ! C'est un peu comme faire des maquettes desktop et mobile, c'est long et fastidieux, mais c'est devenu incontournable...)

### Fonctionnement

[📜 Documentation symfony de UX Turbo](https://symfony.com/bundles/ux-turbo/current/index.html#usage)

Rappelez-vous :  
1. une personne rentre ses informations dans le formulaire de connexion.
2. Lors du clic sur le bouton de connexion, une requête Ajax part vers le serveur.
3. Le serveur analyse la requête et dans le cas où la personne a réussi sa connexion, il répond le html du dashboard.  
Comme on est dans une requête Ajax (ou fetch, c'est pareil), les redirections ou l'affichage d'une vue ne marche pas directement.
4. Le front reçoit le HTML, et remplace le formulaire de connexion par le code du dashboard. Il en profite aussi pour changer l'url, pour simuler un changement de page (qui n'a pas eu lieu, c'est JS qui simule).

Dans le cas d'un échec de connexion, JS récupère le message et l'affiche au bon endroit dans le formulaire.

Et bien avec UX Turbo c'est exactement pareil.

On va spécifier dans nos templates des zones qui attendent potentiellement des modifications asynchrones.

On va aussi prévoir des bouts de codes qu'on enverra, que symfony appelle des `streams`. 

Lorsqu'on reçoit une requête, le controller écoute si le format attendu par le front est le format permettant le stream, et si oui, on lui fera un render avec le bout de code qui va bien. 

Exemple en code :

Le template du formulaire de modification d'un film :

```twig
{% extends 'base.html.twig' %}

{% block title %}
	{{film.titre}}
{% endblock %}

{% block body %}

	<h3>{{ film.titre }}</h3>

	<turbo-frame id="success_message">
		{# Rien pour l'instant. #}
	</turbo-frame>

	{{ form(form) }}

{% endblock %}
```

On constate pour l'instant un simple ajout dans le template d'édition, d'une section `turbo-frame`. 

Dans un nouveau fichier, on écrit ce code :

```twig
<turbo-stream action="replace" target="success_message">
	<template>
		<div>Le film "{{ film.titre }}" a été créé !</div>
	</template>
</turbo-stream>

```
Ici on a une section `turbo-stream`, qui contient ce qui viendra remplacer grâce à l'action `replace` le `turbo-frame` du template précédent, lorsqu'on l'appelera. On remarque que la cible (`target`) a l'Id du `turbo-frame` d'au-dessus ! C'est important, c'est ce qui permettra à Turbo de savoir ce qu'il doit modifier.

On a un choix d'action avec turbo, et pas uniquement `replace`:  
[📜 Documentation de UX Turbo](https://turbo.hotwired.dev/handbook/streams).
- append,
- prepend,
- replace,
- remove, 
- refresh,
- ...

Si vous voulez comprendre le but des balises `template`, [c'est par là](https://developer.mozilla.org/fr/docs/Web/HTML/Element/template).

Et dans le controller, on vient ajouter ce code :

```php
// Si UX Turbo est activé :
if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
	// on envoie le bon Content-Type :
	$request->setRequestFormat(TurboBundle::STREAM_FORMAT);

	// Puis on répond un render classique, avec notre fichier de succès.
	return $this->render('film/success.html.twig', ['film' => $film]);
}
```

Et voici la méthode complète du controller :

```php
#[Route('film/{id}/edit', name: 'app_film_edit')]
public function update(Film $film, Request $request, EntityManagerInterface $em): Response
{
	$form = $this->createForm(FilmType::class, $film);
	$form->handleRequest($request);

	if ($form->isSubmitted() && $form->isValid()) {
		$film = $form->getData();

		if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
			$request->setRequestFormat(TurboBundle::STREAM_FORMAT);
			return $this->render('film/success.html.twig', ['film' => $film]);

		} else {

			$em->flush();
			$this->addFlash('success', 'Le film a bien été modifié');
			return $this->redirectToRoute('app_show_film', ['id' => $film->getId()]);

		}
	}

	return $this->render('film/edit.html.twig', ['film' => $film, 'form' => $form]);
}
```

En plus des avantages de l'asynchrone, on peut venir travailler à plusieurs endroits en même temps grâce à Turbo : En effet, si vous avez envie de changer trois ou quatre sections de votre page suite à une action utilisateur, il suffit de mettre plusieurs `turbo-frame` dans votre template de base, et dans le fichier qui sera appelé par le controller, vous pouvez ensuite mettre autant de sections `turbo-stream` que nécessaire. Elles seront toutes chargées et modifiées en même temps s'il y en a plusieurs ! 

Continuer avec le [cours 7](<11 cours 7 - Validation des données.md>).