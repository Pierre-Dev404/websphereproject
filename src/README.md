Documentation de l'application dashboard
----------------------------------------
    Formation à la piscine

---
Pattern MVC
---
Au lieu d'avoir un fichier un fichier monolithique mélangeant html et php, on découpe le traitement des données (model), des affichages de données (view), des gestionnaires de pagination (controler).

---
Liste des répertoires et fichiers
---
    src/
        assets/ : sources templates + css + img + js
        class/ : classes PHP Article, Categorie, Comment, User
        controler/ : controleurs par page appelant modèles et vues(pattern MVC)
        model/ : traitement des données de formulaires (pattern MVC)
        system/ : 
            init.php (connexion bdd, init twig, tout ce qui est commun à toute l'appli)
            autoload.php (chargement auto des classes PHP)
        templates/ : view découpées avec twig basées sur des layout pour le front et le back (pattern MVC)
        vendor/ : librairies installées avec composer (twig, phpunit)
        admin.php : controleur principal d'accès au backoffice
        index.php : controleur principal d'accès au frontoffice
        composer.json : liste des librairies installées avec composer avec versions, généré automatiquement
    tests

Amélioration pour sécurité
---
- Renommer admin.php
- Actuellement tout utilisateur inscrit en bdd accède au backoffice
    Il faudrait pouvoir
        - Afficher des liens de pages dans le menu (backoffice) en fonction du type d'utilisateur connecté (admin, lecteur, rédacteur, client, fournisseur, etc)
        => Possible avec twig, exemple
        {% if type_status_user = "admin" %}
            <a href="pagedadmin.html">Mapage privée</a>
        {% endif %}
        - Bloquer l'affichage d'une page à un utilisateur qu in'a pas le droit de l'afficher, voir le renvoyer vers une page d'erreur  
        <?php
            if($id_status_user =="admin){
                echo $twig->render('mapageadmin.html);
            }
            else{
                echo $twig->render('mapagederreur.html);
                // ou header('location: index.php');
            }
- utiliser un ou des fichiers .htaccess

Lexique
---
- backoffice pour webmaster
- frontoffice pour internautes
- backend = traitement/manipulation des données (traitement formulaires, classes, méthodes, bdd, fonctions, etc)
- frontend = tout ce qui est visuel
- Différence dev front ou back ou fullstack ci dessus