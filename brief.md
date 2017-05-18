Wiki
====

Brief : réaliser un wiki à l'aide des technologies Angular (front) et Symfony (backend).

Fonctionnalités :
=================
* Se connecter / Se déconnecter
* S'inscrire 
* Roles
  * ADMIN : lire/créér/modifier/supprimer
  * USER : lire/créer (doit être validée)/modifier
  * ANON : lire
* Créer une page
* Modifier une page
  * Interface de saisie WYSIWYG
* Supprimer une page
* Historique des modifications
* Recherche un page
* Page profil

Fonctionnalités optionnelles :
==============================
* Insertion d'image
* Badge
* Chat
* Catégories
* Multilingue

Modèle
======

## User
* id
* status
* email
* password
* pseudonyme
* role
* created_at
* lastconnected_at

## UserStatistic
* user_id
* score

## Page
* id
* created_at
* updated_at
* slug (unique)

## PageRevision
* id
* page_id
* title
* status : online|pending_validation|canceled|draft
* content
* updated_by
* created_at
* updated_at

## Rating
* id
* revision_id
* rating
* user_id
* created_at
* updated_at

Routing API
===========
/api/v1/
## User
  * /user
    * POST /
    * GET /{id}
    * PUT /{id}
    * DELETE /{id}
    * POST /login
    * GET /logout

## Page
  * /page
    * POST /
    * GET /{slug}
    * PUT /{slug}
    * DELETE /{slug}
    * Last : GET /last?limit=10&offset=0
    * BestRated : GET /best_rated?limit=10&offset=0
    * Search : GET /search?q=QUERYlimit=10&offset=0

## PageRevision
  * /page/{page_slug}/revision
    * POST /
    * GET /{id}
    * PUT /{id}
    * DELETE /{id}
    * AllForPage GET /all?status=online

## Rating
* /page/{page_slug}/revision/{revision_id}/rate
    * POST /
    * GET /{id}
    * PUT /{id}
    * DELETE /{id}

Front (Angular)

===============

### Toutes les pages

* Champs de recherche
* Formulaire de login
* Lien de déconnexion

### /
* Derniers articles
* Meilleurs notés
* Page {home}
* Champs de recherche

### /search
* résultats de recherche

### /page/{slug}
* title, content (la dernière révision online), 
* rating (de la révision en cours)
* date de la révision

### /page/new
### /page/{slug}/edit
* formulaire d'édition de la page

### /page/{slug}/history
* utilisateurs ayant contribués