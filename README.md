WTF
===

Здесь хранятся сырцы текущей версии имажинарии на базе движка Livestreet 1.0.3

Лицензия GPL.

Из репозитория исключен конфигурационный файл `config/config.local.php`

В данный момент код в репозитории очень-очень `legacy`.

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



Restore configs
```
cp /srv/Imaginaria.Config/config.local.php /path/to/config/config.local.php
cp /srv/Imaginaria.Config/plugins.dat /path/to/plugins/plugins.dat
```

+ примонтировать `.cache` 
+ выставить права и владельца
+ 

Update
======

```
rm -rf ./.cache/compiled/*
rm -rf ./.cache/assets/*
```

See: https://stackoverflow.com/questions/47470271/what-does-remote-actually-do-in-git-submodule-update-remote





