##Evaluation candidats
====
###But de l'exercice
Ce dépôt github contient l'arborescence d'un projet web.
Le but de l'exercice est de créer en 1h environ une ébauche de site ecommerce simple en se servant des composants déjà installés et des exemples d'utilisation déjà écrits.
Il n'est pas nécessaire que le site fonctionne correctement à la fin du temps imparti (mais pourquoi pas ? :) ). 
Le but est de viser la simplicité, la clarté dans le code et l'efficacité. 


Les composants qu'il faut développer sont :
* une page d'accueil présentant un listing des catégories de produits actives
* une page produit présentant un produit actif d'une manière structurée (titre / catégorie / image / texte / prix)
* une page categorie produit présentant les produits de cette catégorie
* une page panier permettant de visualiser son panier puis s'identifier pour donner un adresse de livraison et valider la commande (pas de paiement)
* une page espace client accessible seulement en mode loggué affichant un message de bienvenue

Les produits sont structurés de la manière suivante :
* id
* nom
* id de catégorie (pas de multi-categorie pour simplifier)
* image
* texte
* prix
* actif
Vous aurez à créer cette table dans la BDD.

Les tables suivantes sont déjà créées et vous permettront de créer les classes DB_DataObject associées :
* categories pour la classe Category
* address pour la classe Address
* orders pour la classe Order
* orders_products pour la classe OrderProduct


###Installation
Vous trouverez un fichier composer.json avec des paquets déjà listés vous permettant d'implémenter certaines briques fonctionnelles.  
Vous pouvez très bien en installer d'autres (sans installer symfony bien sur :) ).  

Les 2 seuls impératifs sont d'utiliser les paquets suivants :
* Auth pour gérer l'authentification des utilisateurs sur le site. Vous trouverez un exemple d'utilisation de la classe dans /www/index.php et /application/bootstrap.php. Doc : https://pear.php.net/package/Auth/docs
* DB_DataObject pour gérer la couche ORM du site. Vous trouverez un exemple d'utilisation de la classe DB_DataObject dans /application/models/User.class.php. Le fichier index.php contient également un petit exemple d'utilisation que vous pourrez reprendre. Doc : https://pear.php.net/package/DB_DataObject/docs

Le document root du site se trouvera dans le répertoire /www  
Le fichier /www/index.php appelle un fichier bootsrap.php qui initialise certains éléments du site, notamment l'autoloader pour les classes.  
Vous pouvez donc créer des classes directement dans le dossier /application/models.  
Une classe MyDataObject est présente dans le répertoire et fait le lien entre la classe DB_DataObject et vos classes tout en implémentant l'interface Iterator, permettant de pouvoir parcourir les résultats d'une reqûete.  
Une classe Tools existe et permet éventuellement d'y définir des éléments annexes, utiles pour le projet (mais vous pouvez le gérer comme vous le souhaitez)  

Le repository git comporte une seule branche 'master' mais il serait bien de créer une branche 'develop' en local sur laquelle vous ferez vos modifications et que vous commiterez sur le dépôt une fois le test terminé.

###Acces BDD :
La base de données est une BDD MySQL et se trouve sur le serveur 192.168.1.12, accessible par phpmyadmin à l'adresse http://192.168.1.12/phpmyadmin  
La base se nomme : evalphp  
Le user : evalphp  
Le passwd : evalphp  

Vous serez jugé sur l'ensemble de l'exercice (pas que le code), ce n'est pas grave si tout n'est pas fait. L'important est de bien soigner ce que vous arriverez à faire.

Bon courage !