# API BLOG SYMFONY API PLATFORM

## démarrer un projet symfony

### Démarer une base de donnée
Requirements :
- ORM doctrine : composer require orm
- makerBundle : composer require maker
- docker : symfony console make:docker:database (mysql : database)
on lance  docker-compose up -d database
dans le cas ou la database n'est pas créé on lance
````
symfony console doctrine:database:create
````
 les entités :
 - Articles : titre, contenu, createdAt, catégorie
 - Catégories : name
 - user

 composer require security   