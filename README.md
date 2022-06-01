WTF
===

Здесь хранятся сырцы текущей версии имажинарии на базе движка Livestreet 1.0.3

Лицензия GPL.

Из репозитория исключены конфигурационные файлы 

- `config/config.local.php`
- `config/searchd.credentials.imaginaria`

Install
=======

```
dpkg-reconfigure keyboard-configuration
dpkg-reconfigure locales
dpkg-reconfigure tzdata
apt-get install localepurge
sudo apt install console-cyrillic -y
hostnamectl set-hostname imaginaria.ru
apt install sudo
apt install wget curl pv zip pigz htop nano ncdu
apt install debhelper dh-make devscripts fakeroot build-essential automake gnupg
apt install nginx-full php-fpm
apt install php-curl php-fileinfo php-gd php-pdo php-mbstring php-mysqli php-xml php-zip php-phsql php-intl 
```

Конфиг для мантикоры должен лежать в `/etc/manticonf/credentials.imaginaria`: 
```
#!/usr/bin/env bash

export DB_HOST="localhost"
export DB_BASE="imaginaria"
export DB_USER="imaginaria"
export DB_PASS="..."
# -eof-

```



Restore configs
```
cp /srv/Imaginaria.Config/config.local.php /path/to/config/config.local.php
cp /srv/Imaginaria.Config/plugins.dat /path/to/plugins/plugins.dat
```

+ примонтировать `cache` 
+ выставить права и владельца
+ 






