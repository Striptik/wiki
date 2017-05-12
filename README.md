wiki
====

Projet de wiki


Pré-requis
--------
* Composer
* Node

Initialisation
--------
* Clonez le projet git via HTTP / SSH
* Générez le virtualhost avec pour racine /web
* Générez les dépendances :
```shell
composer install
```
* Placez vous dans le répertoire /web/integration
```shell
cd web/integration
```
* Générez les modules d'integration :
```shell
npm install
```
* Compilez le scss :
```shell
gulp dist
```
* Lancez le watch :
```shell
gulp watch
```
* Codez !

_Projet développé par BAILLEUX Robin, LOISELEUR Kevin, RODRIGUES Mathieu & SIMONIN Alexandre.