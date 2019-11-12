#!/usr/bin/env bash

# Initialisation projet
# avec '--full' pour le profiler & twig & cie
# On pourra faire du ménage plus tard
symfony new --full apidemo
composer create-project symfony/website-skeleton apidemo


cd apidemo

# JMSSerializerBundle
# Pour un meilleur contrôle de la serialsation
# http://jmsyst.com/bundles/JMSSerializerBundle
composer require jms/serializer-bundle


# FOSRestBundle
# Pour la gestion des bases de l'API
# https://symfony.com/doc/master/bundles/FOSRestBundle/index.html
composer require friendsofsymfony/rest-bundle


# Configuration base de données
vi .env

# Création BDD
sf doctrine:database:create

# Création des entités
sf make:entity

# Mise à jour BDD
sf doctrine:schema:update --dump-sql
sf doctrine:schema:update --force

# Config prefix des routes
# https://symfony.com/doc/current/routing.html#route-groups-and-prefixes

# Controleur
sf make:controller

# Convention nommage
# https://symfony.com/doc/master/bundles/FOSRestBundle/5-automatic-route-generation_single-restful-controller.html#implicit-resource-name-definition


# Liste des routes
sf debug:router

# Désactivation CSRF (temporaire)
> config/packages/framework.yaml
    csrf_protection: false




# RUN
symfony server:start
sf server:start


# TESTS

# Init dépendances
php bin/phpunit

# Pour tester des URL
composer require --dev symfony/browser-kit

# Lancement des tests
php bin/phpunit





# NelmioApiDocBundle
# Pour la gestion de la documentation++ de l'API
# https://symfony.com/doc/current/bundles/NelmioApiDocBundle/index.html
composer require nelmio/api-doc-bundle


