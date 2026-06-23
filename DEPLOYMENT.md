# Déploiement sparklin.io

## Architecture
- **Hébergement** : OVH (PHP 8.x)
- **Base de données** : Supabase (PostgreSQL)
- **Emails** : Brevo (API transactionnelle)
- **Source** : GitHub → deploy FTP OVH via GitHub Actions

## Prérequis
1. PHP 8.1+ avec extensions `curl`, `json`, `mbstring`
2. Fichier `.env` à la racine (voir `.env.example`)
3. Table `sparklin_i18n` créée dans Supabase (voir `sparklin_i18n.sql`)

## Déploiement
```bash
# Local
php -S localhost:8000

# Production
# GitHub Actions → FTP vers OVH (configuré dans .github/workflows/deploy.yml)
```

## Structure
```
/                        → index.php (homepage)
/spark-1/                → index.php (produit)
/spark-plus/             → index.php (produit)
/spark-go-e/             → index.php (produit)
/spark-pilot/            → index.php (produit)
/app/                    → index.php (application)
/cas/pme|collab|...      → index.php (cas clients)
/blog/                   → index.php (listing)
/blog/slug/              → index.php (article)
/livre-blanc/            → index.php (formulaire)
/contact/                → index.php (formulaire)
/support/                → index.php (FAQ)
/evenements/             → index.php
/a-propos/               → index.php
/admin-blog/             → index.php (CMS)
/api/                    → PHP endpoints (remplace netlify functions)
/includes/               → env.php, supabase.php, i18n.php
/assets/                 → css, js, images
```
