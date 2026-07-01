# WC26 Pronostics API

API REST développée avec **Symfony 7** permettant à un groupe d'amis de pronostiquer les scores des matchs de la Coupe du Monde 2026, avec calcul automatique des points une fois les résultats connus.

Ce projet est le pendant back-end de [WC2026 Hub](https://github.com/TonPseudo/wc2026-hub), une plateforme de data intelligence sur la Coupe du Monde 2026 construite en SvelteKit. Les deux projets forment un écosystème : WC2026 Hub couvre les statistiques et le storytelling autour du tournoi, tandis que WC26 Pronostics API gère la logique métier communautaire (comptes utilisateurs, pronostics, classements).

## Stack technique

- **Symfony 7** (LTS) — framework back-end PHP
- **Doctrine ORM** — mapping objet-relationnel et migrations de base de données
- **API Platform** — génération automatique de l'API REST (CRUD, pagination, documentation OpenAPI/Swagger)
- **MySQL** — base de données relationnelle

## Fonctionnalités

- Gestion des équipes, matchs (« rencontres ») et de leur avancement (scores, statut, phase de compétition)
- Système de pronostics : chaque utilisateur pronostique un score pour chaque rencontre
- Calcul automatique des points selon la précision du pronostic une fois le résultat réel connu
- API REST documentée, consommable par n'importe quel front (SvelteKit, mobile, etc.)

## Modèle de données

```
Utilisateur 1 ──── N Pronostic N ──── 1 Rencontre
                                          │
                                N ─────────┴───── 1  (x2 : domicile + extérieur)
                                       Equipe
```

| Entité | Rôle |
|---|---|
| `Equipe` | Une sélection nationale participant au tournoi |
| `Rencontre` | Un match entre deux équipes (date, scores, phase) |
| `Utilisateur` | Un participant du groupe de pronostics |
| `Pronostic` | Le score deviné par un utilisateur pour une rencontre donnée |

## Installation locale

### Prérequis

- PHP 8.2+
- Composer
- [Symfony CLI](https://symfony.com/download)
- MySQL (ex. via XAMPP)

### Étapes

```bash
git clone https://github.com/TonPseudo/wc26-pronostics-api.git
cd wc26-pronostics-api
composer install
```

Configurer la connexion à la base de données dans `.env` :

```
DATABASE_URL="mysql://root:@127.0.0.1:3306/pronostics_wc26?serverVersion=8.0&charset=utf8mb4"
```

Créer la base et appliquer les migrations :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

Lancer le serveur local :

```bash
symfony server:start
```

La documentation de l'API est alors accessible sur `http://127.0.0.1:8000/api`.

## Roadmap

- [x] Modélisation des entités (Equipe, Utilisateur, Rencontre, Pronostic)
- [x] Migrations et base de données
- [ ] Authentification JWT
- [ ] Service de calcul de points
- [ ] Fixtures (données de test : équipes et matchs du tournoi)
- [ ] Tests PHPUnit sur la logique métier

## Auteur

Ilyès Mouhsini — étudiant BUT Informatique, IUT de Vélizy-Rambouillet (UVSQ)
