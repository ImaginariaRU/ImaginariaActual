WTF
===

Здесь хранятся сырцы текущей версии имажинарии на базе движка Livestreet 1.0.3

Лицензия GPL.

Из репозитория исключен конфигурационный файл `config/config.local.php`

В данный момент код в репозитории очень-очень `legacy`.

Install
=======

```
git clone https://github.com/ImaginariaRU/ImaginariaLS.git .
git submodule init
git submodule update
composer install
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
git pull
git submodule init
git submodule update --remote
composer install
rm -rf ./.cache/compiled/*
rm -rf ./.cache/assets/*
```

See: https://stackoverflow.com/questions/47470271/what-does-remote-actually-do-in-git-submodule-update-remote





