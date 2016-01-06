![School-PHP](https://raw.githubusercontent.com/schoolphp/library/master/Installer/install/skins/img/logo2.jpg)
School-PHP FrameWork: "Fox and Wolf"
===========================

## Установка 

Создайте новый проект в PHPStorm, выберите способ создания `COMPOSER` и установите пакет `schoolphp/framework`.
> **Note:** Как альтернатива можно запустить команду:
```bash
$ composer create-project schoolphp/framework <project-path>
```

## Установка через git
Запускаем команды:
```bash
git init
git pull https://github.com/schoolphp/framework
```

## Настройка
1) Необходимо настроить `MySQL` , а именно `Создать новую Базу Данных` и `Нового пользователя`.

2) Запустить `install.php`, ввести данные.

3) Запускаем `Проект`

## Важные особенности
Не стоит бояться файла `install.php`, так как установки не будет, если она уже была выполнена ранее!

## Обновление ядра
Если Вы узнали, что ядро получило изменения, то запустите git команду:
```bash
git pull https://github.com/schoolphp/framework
```
> **Примечания:** Если просят удалить файлы, то были нарушены принципы Фреймворка, так как нельзя лезть в файлы ядра!

## Дополнительно:
Сократить `git pull` можно, если заранее указать указать репозиторий:
```bash
git config remote.origin.url https://github.com/schoolphp/framework
```

И дальше обновлять просто командой `git pull`.