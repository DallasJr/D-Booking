# Gestion de Propriétés et Réservations Laravel

Ce projet est une application web développée en Laravel permettant de gérer des propriétés immobilières, ainsi que la réservation de celles-ci. L’interface d’administration utilise **Filament** pour la gestion des propriétés et des réservations.

## Fonctionnalités principales

- Gestion complète des propriétés (création, édition, suppression)
- Upload et gestion des images des propriétés
- Affichage des propriétés disponibles avec leurs détails (nom, description, ville, prix, image)
- Système de réservation avec sélection des dates de début et de fin
- Vérification des conflits de réservation (dates déjà réservées)
- Calcul automatique du prix total en fonction du nombre de nuits
- Ajout d’une note personnalisée à la réservation
- Suppression automatique des images associées lors de la suppression d’une propriété
- Filtrage dans la liste des propriétés par nom et par dates
- Interface admin moderne via Filament

## Technologies utilisées

- PHP 8.3.6
- Composer 2.8.9
- MySQL
- Node.js & NPM
- Laravel 12.19.3
- Laravel Breeze
- Filament 3.3.29
- TailwindCSS
- Livewire 3.6.3
- Carbon

## Installation

1. **Cloner le dépôt**

```bash
git clone https://github.com/DallasJr/D-Booking
cd D-Booking
```
 
2. **Installer les dépendances**

```bash
composer install
npm install
```

3. **Configurer l’environnement**

Renommer .env.example en .env et configure la connexion à la base de données et **définir le port APP_URL**.
```bash
APP_NAME=D-Booking
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=root
DB_PASSWORD=votremotdepasse
```

4. **Générer la clé d’application**

```bash
php artisan key:generate
```

5. **Migrer la base de données**

```bash
php artisan migrate
```

6. **Importer /setup/laravel_db**

Via **phpmyadmin** dans le serveur **laravel_db** et l'onglet **Importer** sélectionner */setup/laravel_db.sql*.


7. **Les images de base**

Déplacer les 3 images dans /setup/ vers /storage/app/public/properties/

8. **Lancer le serveur backend**

```bash
php artisan serve
```

9. **Lancer le serveur frontend**

Dans un autre terminal:

```bash
npm run dev
```

## Utilisation

**Comptes:**

- Compte admin: admin@admin.com:admin
- Compte client: client@client.com:client123

Vous pouvez évidemment créer autant de compte client.

- Connectez-vous à l’interface d’administration via https://localhost:8000/admin.
- Gérez les propriétés dans le panneau Filament.
- Les utilisateurs peuvent consulter les propriétés via https://localhost:8000 et effectuer des réservations en choisissant des dates disponibles.

