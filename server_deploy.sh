#!/bin/bash

# Enable maintenance mode
yes | php artisan down --force

# Create necessary directories
mkdir -p /home/nomilyskills/public_html/crm.burraqengineering.com/storage/app
mkdir -p /home/nomilyskills/public_html/crm.burraqengineering.com/storage/framework/cache
mkdir -p /home/nomilyskills/public_html/crm.burraqengineering.com/storage/framework/sessions
mkdir -p /home/nomilyskills/public_html/crm.burraqengineering.com/storage/framework/views
mkdir -p /home/nomilyskills/public_html/crm.burraqengineering.com/storage/logs
mkdir -p /home/nomilyskills/public_html/crm.burraqengineering.com/bootstrap/cache

# Generate artisan key --force does not exist
yes | php artisan key:generate

# Secure .env and other sensitive files
sudo chmod -R 775 /home/nomilyskills/public_html/crm.burraqengineering.com/
sudo chmod 444 /home/nomilyskills/public_html/crm.burraqengineering.com/.env
sudo chown -R root:root /home/nomilyskills/public_html/crm.burraqengineering.com/

# Set correct permissions for storage & bootstrap/cache
yes | chmod -R 777 /home/nomilyskills/public_html/crm.burraqengineering.com/storage/ /home/nomilyskills/public_html/crm.burraqengineering.com/bootstrap/cache

# Check PHP version
php --version

# Check if PHP 8.1 is installed
if ! php -v | grep -q "PHP 8.1"; then
  echo "PHP 8.1 is not installed. Exiting."
  exit 1
fi

# Check if Composer is installed, and install it if not
if ! command -v composer &> /dev/null; then
  echo "Composer is not installed. Installing Composer..."
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php composer-setup.php --install-dir=/usr/local/bin --filename=composer
  php -r "unlink('composer-setup.php');"
else
  echo "Composer is already installed."
  composer --version
fi

# Update Composer Dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-cache

# Laravel Seeders
# yes | php artisan db:seed --class=LanguageSeeder


# Avoid any node permission error
sudo chown -R root:root /home/nomilyskills/public_html/crm.burraqengineering.com/

# Create a custom link of public folder with storage folder
# yes | php artisan storage:link

# Run database migrations
yes | php artisan migrate --force

# Clear caches
php artisan cache:clear && php artisan config:clear && php artisan route:clear
php artisan view:clear && php artisan event:clear && php artisan clear-compiled
php artisan optimize:clear
php artisan cache:forget spatie.permission.cache


# Function to check if nvm is installed
check_nvm_installed() {
  if command -v nvm &> /dev/null; then
    echo "nvm is already installed."
    return 0
  else
    echo "nvm is not installed."
    return 1
  fi
}

# Function to install nvm
install_nvm() {
  echo "Installing nvm..."
  curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.5/install.sh | bash
  export NVM_DIR="$([ -z "${XDG_CONFIG_HOME-}" ] && printf %s "${HOME}/.nvm" || printf %s "${XDG_CONFIG_HOME}/nvm")"
  [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
  echo "nvm installed successfully."
}

Function to load nvm
load_nvm() {
  export NVM_DIR="$([ -z "${XDG_CONFIG_HOME-}" ] && printf %s "${HOME}/.nvm" || printf %s "${XDG_CONFIG_HOME}/nvm")"
  [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
  echo "nvm loaded successfully."
}

# Main script logic
if ! check_nvm_installed; then
  install_nvm
fi

# Ensure nvm is loaded
load_nvm

# Verify nvm installation
if command -v nvm &> /dev/null; then
  echo "nvm is ready to use."
  nvm --version
else
  echo "Failed to install or load nvm. Please check the installation manually."
  exit 1
fi

Ensure nvm is loaded
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

# Check if the correct Node.js version is installed
if [[ $(node -v) != "v20.18.3" ]]; then
  echo "Node.js version is NOT v20.18.3. Installing..."
  nvm install 20.18.3
fi

# Use the correct Node.js version
nvm use 20.18.3

#  To ensure node does not get any conflict; only on fresh or node upgrade time
# rm -rf node_modules
# rm -f package-lock.json
# npm cache clean --force
# npm cache clean --force --global
# rm -rf ~/.npm
# rm -rf ~/.nvm/.cache
# rm -rf /tmp/*

# Install Node.js dependencies
/root/.nvm/versions/node/v20.18.3/bin/npm install

# Fix npm audit issues
/root/.nvm/versions/node/v20.18.3/bin/npm audit fix --force

# Run in production mode
/root/.nvm/versions/node/v20.18.3/bin/npm run production

# make sure .env files are same
# APP_ENV=testing php artisan test --filter EnvFilesConsistencyTest

# Check project health
# php artisan health:check --no-notification

# Reset permissions for web server & FTP user
sudo chown -R nomilyskills:nomilyskills /home/nomilyskills/public_html/crm.burraqengineering.com/
sudo chmod -R 755 /home/nomilyskills/public_html/crm.burraqengineering.com/
sudo chmod -R 777 /home/nomilyskills/public_html/crm.burraqengineering.com/storage/
sudo chmod -R 777 /home/nomilyskills/public_html/crm.burraqengineering.com/storage/framework/cache/data
sudo chmod -R 777 /home/nomilyskills/public_html/crm.burraqengineering.com/bootstrap/cache
sudo chmod 444 /home/nomilyskills/public_html/crm.burraqengineering.com/.env

# echo "Stopping Horizon if running..."
# if /usr/local/bin/php artisan horizon:status >/dev/null 2>&1; then
#     /usr/local/bin/php artisan horizon:terminate || true
# fi

# yes | composer require laravel/horizon

# Run this only first time
# php artisan horizon:install
# php artisan migrate
# echo "📊 Publishing Horizon config..."
# /usr/local/bin/php artisan horizon:publish || true
# Run this only first time

# echo "🔍 Checking Supervisor..."
# if ! command -v supervisorctl >/dev/null 2>&1; then
#     echo "⚠️ Supervisor not found. Installing..."

#     if command -v dnf >/dev/null 2>&1; then
#         dnf install -y supervisor
#     else
#         yum install -y supervisor
#     fi

#     systemctl enable supervisord
#     systemctl start supervisord
# fi


# echo "📁 Installing Horizon Supervisor config..."
# echo "Confirm the location of supervisor location in the files section if it is supervisord.d it means /etc/supervisord.d/"
# yes | cp deploy/supervisor/horizon.conf /etc/supervisord.d/laravel-horizon.ini

# echo "🔄 Reloading Supervisor..."
# supervisorctl reread
# supervisorctl update

# echo "▶️ Starting Horizon via Supervisor..."
# supervisorctl start laravel-horizon || supervisorctl restart laravel-horizon

php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan event:cache && php artisan optimize
php artisan cache:clear


# Disable maintenance mode
php artisan up