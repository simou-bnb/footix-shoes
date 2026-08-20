#!/usr/bin/env bash
set -e

cd /workspace

echo "Installing PHP dependencies..."
composer install --no-interaction --prefer-dist

echo "Installing JS dependencies..."
npm install

if [ ! -f .env ]; then
    cp .env.example .env
fi

sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/" .env
sed -i "s/^# \?DB_HOST=.*/DB_HOST=mysql/" .env
sed -i "s/^# \?DB_PORT=.*/DB_PORT=3306/" .env
sed -i "s/^# \?DB_DATABASE=.*/DB_DATABASE=footix_shoes/" .env
sed -i "s/^# \?DB_USERNAME=.*/DB_USERNAME=root/" .env
sed -i "s/^# \?DB_PASSWORD=.*/DB_PASSWORD=root/" .env
grep -q '^DB_HOST=' .env || echo "DB_HOST=mysql" >> .env
grep -q '^DB_DATABASE=' .env || echo "DB_DATABASE=footix_shoes" >> .env
grep -q '^DB_USERNAME=' .env || echo "DB_USERNAME=root" >> .env
grep -q '^DB_PASSWORD=' .env || echo "DB_PASSWORD=root" >> .env

php artisan key:generate --force

echo "Building frontend assets..."
npm run build

echo "Waiting for MySQL to be ready..."
for i in $(seq 1 45); do
    if php artisan db:show > /dev/null 2>&1; then
        echo "MySQL is ready."
        break
    fi
    sleep 2
done

php artisan migrate --force
php artisan db:seed --class=WilayaSeeder --force
php artisan db:seed --class=DemoDataSeeder --force
php artisan storage:link || true

php artisan make:filament-user \
    --name="Admin" \
    --email="islembennabi7@gmail.com" \
    --password="footix2026" \
    --no-interaction || true

echo ""
echo "======================================================"
echo " Footix Shoes is ready."
echo " Start it with: php artisan serve --host=0.0.0.0 --port=8000"
echo " Then open the 'Ports' tab and set port 8000 to Public."
echo " Admin login: islembennabi7@gmail.com / footix2026"
echo "======================================================"
