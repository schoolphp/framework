![School-PHP](https://raw.githubusercontent.com/schoolphp/library/master/Installer/install/skins/img/logo2.jpg)
School-PHP FrameWork: "Fox and Wolf"
===========================

## Установка 

1.а) Создайте новый проект в PHPStorm, выберите способ создания `COMPOSER` и установите пакет `schoolphp/framework`.
2.б) Как альтернатива можно запустить команду:
```bash
composer create-project schoolphp/framework <project-path>
```

1.б) Альтернативная установка через git: запускаем команды через `Terminal` в PHPStorm, быстрый доступ находится слева внизу:
```bash
git init
git pull https://github.com/schoolphp/framework
```

2) Подключаем `bower`. Открываем конфиг `ctrl+alt+s`: 
```bash
Languages & Frameworks -> JavaScript -> Bower
```
выбираем bower.json из проекта.

3) Устанавливаем все пакеты `bower`: открываем `Terminal` и вводим команду: `bower install`

4) Подключаем `composer`. Открываем конфиг `ctrl+alt+s`: 
```bash
Tools -> Command Line Tool Support
```
И добавляем через `+` - `composer` с галочкой на `global` 
> **Примечание:** Если уже установлен, то повторно подключать не надо!

5) Устанавливаем все пакеты `composer`:
```bash
Tools -> Run Command
```
Альтернативная комбинация `ctrl+shift+x` , и вводим команду: `c install`


## Настройка
1) Необходимо настроить `MySQL` , а именно `Создать новую Базу Данных` и `Нового пользователя`.

2) Запустить `install.php`, ввести данные.

3) Запускаем `Проект`

## Важные особенности
Не стоит бояться файла `install.php`, так как установки не будет, если она уже была выполнена ранее!

## Обновление проекта
- Обновление библиотек bower: открываем `Terminal` и вводим команду: `bower update`;
- Обновление библиотек composer: открываем `Tools -> Run Command` и вводим команды:
```bash
c clear-cache
c update
```
- Обновление ядра проекта: открываем `Terminal` и запустите git команду:
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