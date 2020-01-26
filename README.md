# TP Symfony

### PHP INI
* Il faut activer l'extension `gd2` qui sert pour la génération de captcha.
* Il faut activer l'extention `mysql_pdo` pour la connexion à la base de données.

### Installation avec docker:
* `./bin/start.sh` - lance le container mysql 5.7.
* `composer install` - installe les dépendances.
* `npm i` - installe les dépendances npm (webpack encore).
* `npm run watch` - lance la compilation SCSS avec webpack (avec hotreload).
* `symfony serve` - lance l'instance HTTP.

### Sans docker:
* Lancer un serveur MySQL en version 5.7+.
* `composer install` - installe les dépendances.
* `npm i` - installe les dépendances npm (webpack encore).
* `npm run watch` - lance la compilation SCSS avec webpack (avec hotreload).
* `symfony serve` - lance l'instance HTTP.

### Variable d'environnement:
J'ai utilisé un compte gmail pour l'envoit des mails n'ayant pas de serveur SMTP.

### Les fixtures:
`php bin\console doctrine:fixtures:load -q` - ajoute les données basiques en base de données.

### TODO Bonus
* top vente sur toutes les pages sur la droite : requête DQL avec agrégat, dans un repo (ligne commande ?), controlleur imbriqué (comme pour le panier dans la home page) et on l'ajoute au layout.
* ~~une variable de session avec la monaie courrante, menu déroulant pour changer de monnaie, créer un pipe twig currency_convert~~
* ~~back office admin seulement.~~
* ~~passer une comande -> envoi un mail à l'acheteur.~~
* ~~Captcha : symfony-captcha-bundle pour l'inscription.~~