# Cours 11 : Envoyer des mails
Avec Symfony, on a déjà toute une série de choses installées pour nous permettre d'envoyer des mails. Lorsqu'on a choisi, lors de la création d'un utilisateur, que nous allions envoyer un mail de confirmation, on a eu une erreur, parce que nous n'avinos pas modifié notre `.env`.

## `.env`
Dans le `.env`, nous devons ajuster la ligne du `MAILER_DSN` :

voici plusieurs configurations possibles en fonction de vos envies :


### Vous avez envie de travailler avec maildev :

```yaml
###> symfony/mailer ###
MAILER_DSN=smtp://localhost:1025
###< symfony/mailer ###
```
### Vous avez envie de contacter directement votre serveur mail (sans passer par php.ini ) :

```yaml
###> symfony/mailer ###
MAILER_DSN=smtp://adresseMail:motDePasse@serveur:port

# Exemple avec google :
# Le mot de passe d'application contient des espaces : remplacez-les par %20
MAILER_DSN=smtp://adresseMail:mot%20De%20Passe%20Application@smtp.gmail.com:587
###< symfony/mailer ###
```

### Définir un mot de passe d'application dans Google :
[📜 Documentation](https://support.google.com/accounts/answer/185833?sjid=14123049008653734251-EU)
[Lien de l'endroit dans google](https://myaccount.google.com/apppasswords)

>### Vous avez configuré sendmail et votre `php.ini` sur votre ordinateur :
>
> ```yaml
> ###> symfony/mailer ###
> MAILER_DSN=native://default
> ###< symfony/mailer ###
> ```
> Cette version n'est pas recommandée par symfony, et moi-même me suis confronté à plusieurs difficultés avec ça. Je vous conseille une des deux précédentes.
> <br>
> <br>


## Modifier le messenger

Dans symfony, il y a pleins de choses qui sont prêtes à nous aider. Bon dans notre ça, ça nous dérange plus qu'autre chose, on va devoir commenter une ligne qui empêche l'exécution de l'envoi des mail :

Dans le fichier `config/packages/messenger.yaml`, commentez cette ligne :

```yaml
# Symfony\Component\Mailer\Messenger\SendEmailMessage: async
```

Et ça y est, vous pouvez envoyer des mails ! 

Continuer avec le [cours 12 et les fixtures](<20 cours 12 - Fixtures.md>).