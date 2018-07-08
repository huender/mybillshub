# MyBillsHub

> * PHP 7
> * Symfony 3
> * Herokuapp

# About

Projeto apenas para fins de estudo.
Organizador de pagamentos por categoria, com possibilidade de informar a url de acesso para obtenção dos boletos de pagamentos. 
Além do informativo de data, para pagamentos únicos, assim como o dia específico, para pagamentos recorrentes.
Funcionalidade de bloco de notas, apenas para observações.

# Installation

```bash
# Clone repository
# Access project folder

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
