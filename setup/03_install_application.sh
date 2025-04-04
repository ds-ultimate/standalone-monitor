mysql_user_pass="XXXXXXXXX"
mysql_user_name="monitoring_user"
mysql_db_name="monitoring_db"

echo "please set passwords"
exit



mysql -u root <<-EOF
CREATE DATABASE $mysql_db_name;

CREATE USER '$mysql_user_name'@'localhost' IDENTIFIED BY '$mysql_user_pass';
GRANT ALL PRIVILEGES ON $mysql_db_name.* TO '$mysql_user_name'@'localhost';
FLUSH PRIVILEGES;
EOF


apt install git
cd /var/www
sudo -u www-data cp git clone https://github.com/ds-ultimate/standalone-monitor.git


mysql  -p $mysql_db_name < /var/www/standalone-monitor/setup/03_db_layout.sql


XXXX TODO XXXXX



cd /var/www/standalone-monitor
sudo -u www-data cp .env.example .env
sed -i "s/DB_DATABASE=laravel/DB_DATABASE=$mysql_db_name/g" .env
sed -i "s/DB_USERNAME=root/DB_USERNAME=$mysql_user_name/g" .env
sed -i "s/DB_PASSWORD=/DB_PASSWORD=$mysql_user_pass/g" .env


sudo -u www-data php artisan key:generate
sudo -u www-data php artisan migrate
