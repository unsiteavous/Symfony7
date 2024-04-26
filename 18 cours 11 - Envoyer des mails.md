# Envoyer des mails
Avec Symfony, on a déjà toute une série de choses installées pour nous permettre d'envoyer des mails. Lorsqu'on a choisi, lors de la création d'un utilisateur, que nous allions envoyer un mail de confirmation, on a eu une erreur, parce que nous n'avinos pas modifié notre `.env`.

## `.env`
Dans le `.env`, nous devons ajuster la ligne du `MAILER_DSN` :

voici plusieurs configurations possibles en fonction de vos envies :

### Vous avez configuré sendmail et votre `php.ini` sur votre ordinateur :

```yaml
###> symfony/mailer ###
MAILER_DSN=sendmail://default
###< symfony/mailer ###
```
### Vous avez configuré sendmail et votre `php.ini` sur votre ordinateur :

```yaml
###> symfony/mailer ###
MAILER_DSN=sendmail://default
###< symfony/mailer ###
```
### Vous avez configuré sendmail et votre `php.ini` sur votre ordinateur :

```yaml
###> symfony/mailer ###
MAILER_DSN=sendmail://default
###< symfony/mailer ###
```
