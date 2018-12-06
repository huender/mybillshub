[![Build Status](https://travis-ci.org/HuenderBr/mybillshub.svg?branch=master)](https://travis-ci.org/HuenderBr/mybillshub)

# MyBillsHub

> * Symfony 3.4
> * PostgreSQL
> * PHP 7.2

# About

Only for study purposes.
Payments organizer, informing the access url to obtain the payment tickets. 
Possibility for single payments (specific day) and recurring payments (every month).
Notepad functionality, for comments purpose.

# Installation

```bash
# Clone repository
git clone https://github.com/HuenderBr/mybillshub.git

# Access project folder
cd mybillshub

# Install dependencies
composer install
php bin/console assets:install

#Prepare database
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force

# Create admin user with username admin and password admin
php bin/console fos:user:create admin admin@admin.com admin --super-admin

# Warmup cache
php bin/console cache:clear
php bin/console cache:warmup
sudo chmod 777 -R var/cache var/logs var/sessions
