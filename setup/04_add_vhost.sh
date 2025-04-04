
mv /etc/apache2/ports.conf /etc/apache2/ports.conf.old

cat > /etc/apache2/ports.conf <<-EOF
# listening done in vhost
EOF


cat > /etc/apache2/sites-available/001-monitorserver.conf <<-EOF

<Directory /var/www/standalone-monitor>
        Options FollowSymLinks
        AllowOverride All
        Require all granted
</Directory>


Listen 3010
<VirtualHost *:3010>
	ServerAdmin webmaster@localhost
	DocumentRoot /var/www/standalone-monitor/data-input

	# Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
	# error, crit, alert, emerg.
	# It is also possible to configure the loglevel for particular
	# modules, e.g.
	#LogLevel info ssl:warn

	ErrorLog \${APACHE_LOG_DIR}/error-input.log
	CustomLog \${APACHE_LOG_DIR}/access-input.log combined
</VirtualHost>

Listen 127.0.0.1:3020
<VirtualHost 127.0.0.1:3020>
	ServerAdmin webmaster@localhost
	DocumentRoot /var/www/standalone-monitor/data-output

	# Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
	# error, crit, alert, emerg.
	# It is also possible to configure the loglevel for particular
	# modules, e.g.
	#LogLevel info ssl:warn

	ErrorLog \${APACHE_LOG_DIR}/error-output.log
	CustomLog \${APACHE_LOG_DIR}/access-output.log combined
</VirtualHost>
EOF

a2ensite 001-monitorserver.conf
a2enmod rewrite
systemctl restart apache2.service
