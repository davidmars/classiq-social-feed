#Classiq Social Feed
Classiq Social Feed est une librairie qui permet à Classiq d'aller chercher des posts 
sur les réseaux sociaux (Facebook uniquement pour le moment), de les mettre en cache (grace à redbean) 
et de les exploiter de manière unifiée.

##Configuration

```composer
  
  //dans composer...
  
  "require": {
    "davidmars/pov-2018": "dev-master",
    "davidmars/classiq": "dev-master",
    "davidmars/classiq-social-feed": "dev-master"
  },
  
  ...
  
  "repositories": [
      {
        "type": "vcs",
        "url": "https://github.com/davidmars/pov-2018"
      },
      {
        "type": "vcs",
        "url": "https://github.com/davidmars/Classiq"
      },
      {
        "type": "vcs",
        "url": "https://github.com/davidmars/localization"
      },
      {
        "type": "vcs",
        "url": "https://github.com/davidmars/classiq-social-feed"
      }
```

```php
//Dans votre config...

//configurez vos identifiants
\ClassiqSocialFeed\KEYS::setFacebookKeys("api id","api secret");

//installer les vues par défaut
\ClassiqSocialFeed\ClassiqSocialFeed::installViews();

```

```php
//Dans votre config de blocks rajoutez
"blocks/fb-feed" qui est un block vous permettant de tester des imports de page facebook
```

```php
//pour mettre à jour vos flux allez sur l'url:
http://mon-site.com/v/cron-update-feeds
```