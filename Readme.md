# ArtGallery

Site pour l'exposition en ligne des oeuvres d'un artiste.  
Demande d'une artiste pour exposer ses oeuvres sur le web.  
Activités non rétribuées à titre d'exercice et de pratique.  
Inspiration/Reproduction
du [live coding de YoanDev](https://www.youtube.com/playlist?list=PLxEJ5uJLOPDys4MgOz78lci7e7g5GoolQ)

## Environnement de développement

### Pré Requis :

- PHP 8
- Composer
- Symfony CLI
- Docker
- Docker-compose

Pour vérifier les pré-requis avec Symfony CLI :

```bash
symfony check:requirements
```

### Installer les "librairies"

```bash
composer install
```

### Lancer l'environnement de développement

```bash
symfony serve -d
```

### Créer la BDD

```bash
symfony console doctrine:database:create
```

### Créer les tables en BDD via les migrations

```bash
symfony console doctrine:migrations:migrate
```

### Ajouter des fixtures en BDD

```bash
symfony console doctrine:fixtures:load
```