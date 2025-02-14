# Pet Foster Connect

![Logo de PetFosterConnect](/public/images/logo.svg)

## Disclaimer

Ce dépôt contient une conversion en PHP 8 du projet PetFosterConnect, tout en préservant ses fonctionnalités et son architecture MVC, grâce au framework Symfony.

### Ce qu'il reste à implémenter (WIP)

- Création d'un nouvel animal (pour les associations connectées)
- Upload d'images (logo pour association, photo pour animaux)
- Fonction de recherche d'associations / d'animaux et filtrage des résultats
- Effectuer / Administrer les demandes d'accueil

## Présentation

Pet Foster Connect permet de mettre en relation des familles d’accueil pour les animaux avec des associations de protection animale.

PFC permet aux gens de jouer un rôle fondamental en accueillant des animaux en attendant leur adoption définitive afin de leur offrir une meilleure vie.

PFC a pour vocation de répondre à plusieurs besoins :

- Les animaux aimeraient bien un toit, et les gens aiment les animaux (en général)
- Permettre aux associations / refuges de communiquer sur les animaux nécessitant une place au chaud
- Permettre aux familles d'accueil de se faire connaître et de se mettre en relation avec les refuges / associations

## Technologies utilisées

Pour réaliser cette application, nous nous sommes servis de :

|   **Nom**      |     **Utilité**   |
| -------------- | ----------------- |
| VSCode | IDE |
| Symfony | Framework |
| PHP 8 | Langage principal |
| JavaScript ES6 | Langage scripts |
| composer | Gestionnaire de packages |
| PostgreSQL | Base de données |
| Doctrine| ORM |
| Twig | Templating |
| Tailwind | CSS |

## Contributions

- **Current Lead Dev & PHP Converter** :  Noyann Özmen
- **Original Scrum Master** : Laura Martin-Wortham
- **Original Git Master** : Maxime Lizere
- **Original Product Owner** : Samuel Juminer

## Installation

Rien de plus simple :

Clonez ce dépôt, et une fois sur votre machine :

- *npm install*
- *composer install*
- *symfony server:start*

Dans un terminal à part,

- *npm run watch*

Et en avant Guingamp !
