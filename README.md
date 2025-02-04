# Doc2Wheels - Guide Collaboratif
pour mes coco:
Ce guide explique comment configurer l’environnement de travail.

    ∧＿∧
　 (｡･ω･｡)つ━☆・*。
  ⊂/　 /　   ・゜
　 しーＪ　　　     °。+ * 。　
　　　　　                      .・゜
　　　　　                      ゜              ｡ﾟﾟ･｡･ﾟﾟ。
　　　　                                      　ﾟ。　  ｡ﾟ
                                              　ﾟ･｡･ﾟ

---

## Étape 1 : Cloner le dépôt Git
Si c'est votre première fois sur le projet, clonez-le :
```bash
git clone https://github.com/TON_USER_GITHUB/Doc2Wheels.git
cd Doc2Wheels
```

Vérifiez que le projet est bien téléchargé :
```bash
ls -lah
```

---

## Étape 2 : Configurer Apache et PostgreSQL

### Installer les dépendances
```bash
sudo apt update && sudo apt install apache2 postgresql php php-pgsql libapache2-mod-php
```

### Démarrer les services
```bash
sudo systemctl start apache2
sudo systemctl start postgresql
```

### Créer la base de données
```bash
sudo -u postgres psql
```
Dans PostgreSQL :
```sql
CREATE DATABASE doc2wheels;
\c doc2wheels;
```
Importer le schéma SQL si disponible :
```bash
psql -U postgres -d doc2wheels -f database/schema.sql
```

---

## Étape 3 : Configurer Apache pour le projet
Déplacez le projet dans `/var/www/html/` :
```bash
sudo mv ~/Doc2Wheels /var/www/html/
sudo chown -R www-data:www-data /var/www/html/Doc2Wheels
sudo chmod -R 755 /var/www/html/Doc2Wheels
```

Créer une configuration Apache :
```bash
sudo nano /etc/apache2/sites-available/doc2wheels.conf
```
Ajoutez ce contenu :
```
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/Doc2Wheels/public
    <Directory /var/www/html/Doc2Wheels/public>
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/doc2wheels_error.log
    CustomLog ${APACHE_LOG_DIR}/doc2wheels_access.log combined
</VirtualHost>
```

Activer le site et recharger Apache :
```bash
sudo a2ensite doc2wheels.conf
sudo systemctl reload apache2
```

---

## Étape 4 : Travailler avec Git

### Créer une nouvelle branche pour une fonctionnalité
```bash
git checkout -b feature-nouvelle-fonction
```

### Travailler sur le projet et enregistrer les modifications
```bash
git add .
git commit -m "Ajout de la nouvelle fonctionnalité"
```

### Envoyer les changements sur GitHub
```bash
git push origin feature-nouvelle-fonction
```

---

## Étape 5 : Récupérer les mises à jour de `main`
Avant de travailler, mettez à jour votre branche :
```bash
git checkout main
git pull origin main
git checkout feature-nouvelle-fonction
git merge main
```

---

## Étape 6 : Fusionner une fonctionnalité dans `main`
Une fois votre fonctionnalité prête :
```bash
git checkout main
git merge feature-nouvelle-fonction
git push origin main
```

Supprimez la branche devenue inutile :
```bash
git branch -d feature-nouvelle-fonction
git push origin --delete feature-nouvelle-fonction
```

---

## Automatisation du redémarrage des services
Si vous voulez éviter de devoir redémarrer Apache et PostgreSQL à chaque démarrage :
```bash
sudo systemctl enable apache2
sudo systemctl enable postgresql
```

---

## Problèmes fréquents et solutions

### Problème : `Permission denied` sur Git
Vérifiez que vous avez les bonnes permissions GitHub.
```bash
git remote -v
git pull origin main
```

### Problème : Apache ne charge pas le projet
Vérifiez si le service est actif :
```bash
sudo systemctl restart apache2
```

### Problème : La base de données est vide
Vérifiez si PostgreSQL tourne et recréez les tables :
```sql
\c doc2wheels;
\dt;
```
Si aucune table n’existe, importez le schéma :
```bash
psql -U postgres -d doc2wheels -f database/schema.sql
```

---

## Besoin d’aide ?
Si vous avez un problème, contactez un membre du projet ou ouvrez une issue sur GitHub.
