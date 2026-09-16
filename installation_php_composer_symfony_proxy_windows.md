# Installer PHP, Composer et Symfony sur un PC Windows avec proxy

Ce guide fonctionne sans droits administrateur, avec un PHP copié dans le dossier utilisateur et un proxy universitaire. Il est conçu pour être utilisé principalement avec `cmd`, mais les commandes PowerShell sont également indiquées.

## 1. Préparer PHP dans le dossier utilisateur

Copier le dossier PHP dans :

```text
C:\Users\<identifiant>\Php
```

Dans un terminal `cmd`, créer une configuration PHP personnelle :

```cmd
cd /d %USERPROFILE%\Php
copy php.ini-development php-perso.ini
```

Ouvrir le fichier :

```cmd
notepad %USERPROFILE%\Php\php-perso.ini
```

Dans `php-perso.ini`, vérifier ou modifier les lignes suivantes :

```ini
extension_dir = "ext"
extension=curl
extension=openssl
allow_url_fopen = On
```

Les lignes `extension=curl` et `extension=openssl` ne doivent pas commencer par `;`.

Vérifier que les extensions sont chargées :

```cmd
%USERPROFILE%\Php\php.exe -c %USERPROFILE%\Php\php-perso.ini -m | findstr /C:"curl" /C:"openssl"
```

La commande doit afficher :

```text
curl
openssl
```

## 2. Installer Scoop (gestionnaire de paquets sans droits admin)

Ouvrir PowerShell et exécuter :

```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser

iwr -useb get.scoop.sh | iex
```

Scoop s'installe dans le dossier utilisateur. Fermer et rouvrir PowerShell pour que les changements de PATH soient pris en compte.

## 3. Installer la CLI Symfony avec Scoop

Toujours dans PowerShell :

```powershell
scoop install symfony-cli
```

Vérifier l'installation :

```powershell
symfony --version
```

## 4. Ajouter PHP au PATH

### 4.1. Pour la session courante (cmd)

Dans `cmd` :

```cmd
set PATH=%PATH%;%USERPROFILE%\Php
```

Vérifier :

```cmd
php -v
```

### 4.2. Pour la session courante (PowerShell)

Dans PowerShell :

```powershell
$env:Path = "$env:USERPROFILE\Php;$env:Path"
```

Vérifier :

```powershell
php -v
```

## 5. Télécharger Composer

Se placer dans son dossier utilisateur :

```cmd
cd /d %USERPROFILE%
```

Configurer le proxy pour la fenêtre `cmd` en cours :

```cmd
set HTTP_PROXY=http://iut1-srv-prr.univ-grenoble-alpes.fr:3128
set HTTPS_PROXY=http://iut1-srv-prr.univ-grenoble-alpes.fr:3128
```

Télécharger Composer :

```cmd
curl -o composer.phar https://getcomposer.org/composer.phar
```

Vérifier l'installation :

```cmd
php -c %USERPROFILE%\Php\php-perso.ini %USERPROFILE%\composer.phar --version
```

## 6. Créer un projet Symfony

Dans `cmd` :

```cmd
cd /d %USERPROFILE%

set PATH=%PATH%;%USERPROFILE%\Php
set HTTP_PROXY=http://iut1-srv-prr.univ-grenoble-alpes.fr:3128
set HTTPS_PROXY=http://iut1-srv-prr.univ-grenoble-alpes.fr:3128

php -c %USERPROFILE%\Php\php-perso.ini %USERPROFILE%\composer.phar create-project symfony/skeleton test
```

Le projet est créé dans :

```text
%USERPROFILE%\test
```

Pour créer un nouveau projet avec un autre nom, remplacer `test` par le nom souhaité.

## 7. Ajouter des paquets Symfony

Toujours dans `cmd` :

```cmd
cd /d %USERPROFILE%\test

set PATH=%PATH%;%USERPROFILE%\Php
set HTTP_PROXY=http://iut1-srv-prr.univ-grenoble-alpes.fr:3128
set HTTPS_PROXY=http://iut1-srv-prr.univ-grenoble-alpes.fr:3128
```

Ajouter MakerBundle :

```cmd
php -c %USERPROFILE%\Php\php-perso.ini %USERPROFILE%\composer.phar require symfony/maker-bundle --dev
```

Ajouter Doctrine / ORM :

```cmd
php -c %USERPROFILE%\Php\php-perso.ini %USERPROFILE%\composer.phar require symfony/orm-pack
```

Ajouter Twig pour une application web avec templates :

```cmd
php -c %USERPROFILE%\Php\php-perso.ini %USERPROFILE%\composer.phar require symfony/twig-pack
```

## 8. Lancer le serveur PHP local (méthode recommandée)

Depuis le dossier du projet :

```cmd
cd /d %USERPROFILE%\test
%USERPROFILE%\Php\php.exe -c %USERPROFILE%\Php\php-perso.ini -S localhost:8000 -t public
```

Ouvrir ensuite dans le navigateur :

```text
http://localhost:8000
```

ou, pour éviter tout forçage HTTPS :

```text
http://127.0.0.1:8000
```

## 9. Utiliser la CLI Symfony (`symfony serve`)

Une fois PHP dans le PATH et la CLI Symfony installée :

```cmd
cd /d %USERPROFILE%\test
symfony serve --no-tls
```

Ouvrir l'URL HTTP affichée par la commande (généralement `http://127.0.0.1:8000`).

Si tu veux un wrapper simple pour toujours utiliser ton PHP personnalisé et le proxy, tu peux créer un fichier `composer-etu.bat` dans `%USERPROFILE%` :

```cmd
notepad %USERPROFILE%\composer-etu.bat
```

Contenu :

```bat
@echo off
set PATH=%PATH%;%USERPROFILE%\Php
set HTTP_PROXY=http://iut1-srv-prr.univ-grenoble-alpes.fr:3128
set HTTPS_PROXY=http://iut1-srv-prr.univ-grenoble-alpes.fr:3128
php -c %USERPROFILE%\Php\php-perso.ini %USERPROFILE%\composer.phar %*
```

Utilisation :

```cmd
composer-etu create-project symfony/skeleton mon_projet
composer-etu require symfony/maker-bundle --dev
```

Pour `symfony`, une fois installé via Scoop et PHP dans le PATH, tu peux l'utiliser directement :

```cmd
symfony serve --no-tls
symfony console cache:clear
symfony console make:controller HomeController
```

## À retenir

- Travailler principalement avec `cmd` pour ces commandes.
- Dans chaque nouvelle fenêtre `cmd`, exécuter :

  ```cmd
  set PATH=%PATH%;%USERPROFILE%\Php
  set HTTP_PROXY=http://iut1-srv-prr.univ-grenoble-alpes.fr:3128
  set HTTPS_PROXY=http://iut1-srv-prr.univ-grenoble-alpes.fr:3128
  ```

- Exécuter les commandes `composer require` dans le dossier du projet, celui qui contient `composer.json`.
- PHP doit avoir les extensions `curl` et `openssl` activées dans `php-perso.ini`.
- Pour le serveur de dev, privilégier :

  ```cmd
  %USERPROFILE%\Php\php.exe -c %USERPROFILE%\Php\php-perso.ini -S localhost:8000 -t public
  ```

  et ouvrir `http://127.0.0.1:8000` ou `http://localhost:8000` en HTTP.
- La CLI Symfony peut être utilisée avec `symfony serve --no-tls`, mais nécessite parfois des ajustements (désactiver TLS, mettre à jour `symfony-cli`).