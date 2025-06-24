# Cours 15 : JWT Token

Lorsqu'on gère une api, certaines ressources ne sont parfois accessibles que sous réserve d'une connexion. Pour cela, il faut que l'on puisse reconnaitre la personne qui tente d'accéder à la ressource. 

Or, comme la requête est émise par un front qui n'est pas en connexion directe, les sessions PHP ne fonctionnent pas : impossible de garder une trace de la personne entre le moment où elle se connecterait, et le moment où elle reviendrait ensuite demander quelque chose au serveur.

Pour pallier à ce problème, on peut utiliser les JWT Token. C'est un token qui contient diverses informations sur la personne, encodées (attention ce n'est pas sécurisé). Le front le reçoit lors de la connexion de la personne, et doit le donner à chaque requête.

Lorsque le back reçoit ce token, elle le vérifie, et s'il est valide, il exécute la requète, sinon il bloque tout et demande une reconnexion.

## Mise en place du JWT Token
[📜 Documentation de symfony](https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/index.html)
[📜 Documentation de Api Platform](https://api-platform.com/docs/symfony/jwt/)

### 1. Installer le bundle lexik/jwt-authentication-bundle

```bash
composer require lexik/jwt-authentication-bundle

# Générer les clés qui vont avec : 
php bin/console lexik:jwt:generate-keypair

# Si cette commande ne marche pas (erreur "error:80000003:system library::No such process")
# Faites les suivantes :
mkdir ./config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
# Gardez votre mot de passe, pour le mettre dans le .env plus bas.

```
Vérifier que dans `/config/bundles.php`, il y ait cette ligne, ou l'ajouter : 

```php
return [
    //...
    Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle::class => ['all' => true],
];
```

### 2. Paramétrer Symfony 

Dans le `.env`, ajouter ces lignes :
```env
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=CELUI_QUE_VOUS_AVEZ_CHOISI
```

Dans le fichier `/config/packages/lexik_jwt_authentication.yaml` (le créer s'il n'existe pas): 

```yml
# config/packages/lexik_jwt_authentication.yaml
lexik_jwt_authentication:
    secret_key: '%env(resolve:JWT_SECRET_KEY)%' # required for token creation
    public_key: '%env(resolve:JWT_PUBLIC_KEY)%' # required for token verification
    pass_phrase: '%env(JWT_PASSPHRASE)%' # required for token creation
    token_ttl: 3600 # in seconds, default is 3600
```

Dans le fichier `/config/packages/security.yaml` modifier comme suit : 

```yml
# config/packages/security.yaml
security:
    enable_authenticator_manager: true # Only for Symfony 5.4
    # ...

    firewalls:
        login:
            pattern: ^/api/login
            stateless: true
            json_login:
                check_path: /api/login
                success_handler: lexik_jwt_authentication.handler.authentication_success
                failure_handler: lexik_jwt_authentication.handler.authentication_failure

        api:
            pattern:   ^/api
            stateless: true
            jwt: ~

    access_control:
        - { path: ^/api/login, roles: PUBLIC_ACCESS }
        - { path: ^/api,       roles: IS_AUTHENTICATED_FULLY }

```

Enfin, dans le fichier `/config/routes.yaml` :

```yml
# config/routes.yaml
api_login:
    path: /api/login
    methods: [POST]
```


## Se connecter par API
À présent pour se connecter, il suffit d'utiliser une requête POST (ajax, fetch ou postman/insomnia pour tester), construire ainsi : 

- il faut préciser dans les headers que le 'Content-Type' est 'application/json'
- Dans le body, il faut passer les infos ainsi :
```json
{
    "username":"votre email", // si c'est l'email que vous avez choisi pour permettre la connexion
    "password":"password"
}
```
Les noms `username` et `password` sont importants.

Si tout se passe bien, vous recevez un token, qui se présente sous cette forme : 

```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3NTA3Nzg0NTIsImV4cCI6MTc1MDgxNDQ1Miwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiY29udGFjdEB1bnNpdGVhdm91cy5mciJ9.QUfALM-wzUj2Dx3ZSa013IyvPKxha6pMqtHb9yyTefHB4dL4OECheCDyg5Jqe3qqlNNXhiZAKuAz5EwPHuRvHP6SCKicwh1Tk-xkwya2Jj3KxqJh_bbMyNeCcITHbvNNQhC1tuMnWoXwjCn6QCeTMgReBUyXkoZS9a9hAoH7cfF55XDhrpwDtRnigUDzBx0rFxiGgf3kQTb3hvYBx8xU99LOQcJMt2v8OhtbmJw0CvE_IlWPnMsv7QcD871_wOF0z8b0ruT5ufOQeTa5jEzhhlnRmr1Y6pRRMgBQrBoEkHLNa4ayE7_st7CQYi6PoAykTCbNIFifeMo6WvKlWDJHioeHajShkj_o3gX0UkB64S0V18k_f8R0nhzAd3yQkdYyxiLfyDUa1rnsR7SpWBOKT7ZgsU_RfcXq1QEkoxyNDKHxUo9oi1FMHa4NuioPibowstoRHI_swp5WkiwtOTrGZBXMl7bpizmoKwSrSKDFpXp6MvdZQrJhOXK13uExCOtWjEnZ0-1KbBKXbw53JZa5FWBMGGV4lft1Rd8ni1JPQI4kuXkN7Gf8L5ZbKyrTdKyqLkN7Aepwl9NST778KFfm7HDJzkYCj-qo94lAHXuX-1NEr4SsvoJhjSubC87o3w-A3Kd_H3EUIK-G6ZIjl1T6or0udxZfwbCahj3IklGkyZE"
}
```
Si vous voulez vérifier les infos qui sont présentes dans le token (et remarquer que tout le monde peut lire ces infos, copiez-collez votre token dans un site comme https://jwt.io/).

## Effectuer des requêtes avec le JWT token

Maintenant pour toutes les requêtes qui nécessitent le token, vous devez le donner.
Cela se passe dans un header de la requête :

```
Authorization: Bearer eyJ0eXAiOiJKV1QiL...
```