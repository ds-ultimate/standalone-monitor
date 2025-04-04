mysql_rootpass="XXXXXX"

echo "please set passwords"
exit


apt install apache2 mariadb-server php libapache2-mod-php php-mysql sudo

#mysql_secure_installation
mysql -u root <<-EOF
UPDATE mysql.user SET Password=PASSWORD('$mysql_rootpass') WHERE User='root';
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
DELETE FROM mysql.user WHERE User='';
DELETE FROM mysql.db WHERE Db='test' OR Db='test_%';
FLUSH PRIVILEGES;
EOF
