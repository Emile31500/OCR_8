Avalable in Fench / English

# ToDoList
## Français
### Contexte
Ce projet a été réalisé dans le cadre de ma formation open classrooms.
Vous venez d’intégrer une startup dont le cœur de métier est une application permettant de gérer ses tâches quotidiennes. L’application a dû être développée à toute vitesse pour permettre de montrer à de potentiels investisseurs que le concept est viable.
Le choix du développeur précédent a été d’utiliser le framework PHP Symfony.
Votre rôle ici est donc d’améliorer la qualité de l’application. La qualité est un concept qui englobe bon nombre de sujets : on parle souvent de qualité de code, mais il y a également la qualité perçue par l’utilisateur de l’application ou encore la qualité perçue par les collaborateurs de l’entreprise, et enfin la qualité que vous percevez lorsqu’il vous faut travailler sur le projet.

### Contexte Technique
php > 7.4.33
Mysql 8.0.31
Symfony 5.5.6
Composer 2.6.6

### Comment l'installer 

Impoter le projet depuis github. Une fois télécharger déplacer le jusqu'à la racine de votre serveur web.
```$ git clone https://Emile31500/OCR_8```
```$ mv OCR_8 path/to/your/web/server/root/```

Importer toute les dépendances nécessaire pour faire marcher l'application 
```$ composer install```

Créer un fichier .env et configurez les informations nécessaire pour faire marcher l'application Les principales sont :
 - La configuration de la base de données (DATABASE_URL)
 - L'environnement (APP_ENV)
 - Le mode de débogage des tests (XDEBUG_MODE)=coverage
 - La clé de chiffrement de des mots de passe (APP_SECRET)

```
DATABASE_URL="mysql://{user_name}:{user_password}@{db_ip}:{db_port}/todo?serverVersion=8&charset=utf8mb4"
APP_ENV=test
APP_DEBUG=true
XDEBUG_MODE=coverage
APP_SECRET={une_chaine_de_caractere_entre}
```
Ensuite il vous faudra exécuter les trois commandes suivantes pour : 
 - Créer la base de donnée
 - Créer les tables de la base de données
 - Créer des données test de la base de  données

```
$ php bin/console doctrine:database:create```

$ php bin/console doctrine:schema:update --force``` 

$ php bin/console doctrine:fixtures:load
```

## English
### Context
This project was carried out as part of my OpenClassrooms training.
You have just joined a startup whose core business is an application for managing daily tasks. The application had to be developed quickly to demonstrate to potential investors that the concept is viable.
The previous developer's choice was to use the PHP Symfony framework.
So, your role here is to improve the quality of the application. Quality is a concept that encompasses many topics: we often talk about code quality, but there is also the quality perceived by the application's users, the quality perceived by the company's collaborators, and finally the quality you perceive when you have to work on the project.

### Technical Context
php > 7.4.33
Mysql 8.0.31
Symfony 5.5.6
Composer 2.6.6

### How to Install

Import the project from GitHub. Once downloaded, move it to the root of your web server.
```$ git clone https://Emile31500/OCR_8```
```$ mv OCR_8 path/to/your/web/server/root/```

Import all the necessary dependencies to run the application.
```$ composer install```

Create a .env file and configure the necessary information to run the application. The main ones are:
- Database configuration (DATABASE_URL)
- Environment (APP_ENV)
- Debugging mode for tests (XDEBUG_MODE)=coverage
- Encryption key for passwords (APP_SECRET)

```
DATABASE_URL="mysql://{user_name}:{user_password}@{db_ip}:{db_port}/todo?serverVersion=8&charset=utf8mb4"
APP_ENV=test
APP_DEBUG=true
XDEBUG_MODE=coverage
APP_SECRET={a_string_of_characters}
```

Then you will need to execute the following three commands to:
- Create the database
- Create the tables of the database
- Create test data for the database

```
$ php bin/console doctrine:database:create```

$ php bin/console doctrine:schema:update --force``` 

$ php bin/console doctrine:fixtures:load
```

### Test part

you'll find all the test at 127.0.0.1:8000/tests/index..html

To start test exec the command

```
$ vendor/bin/phpunit Test/
```

To start test and generate html coverage exec the command

```
$ vendor/bin/phpunit Test/ --coverage-html public/tests/
```
