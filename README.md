![School-PHP](https://raw.githubusercontent.com/schoolphp/library/master/Installer/install/skins/img/logo2.jpg)

School-PHP FrameWork: "Fox and Wolf"
===========================

## Установка 

0) Подготовка. Для начала нам потребуется `COMPOSER`. Если ранее через него уже была установка данного Frame Work, то необходимо очистить кэш. Для начала настроим PHPStorm в `settings` и `default settings`:
 - в разделе `Languages->PHP` указать PHP Language level: php 7 , CLI Interpreter: php 7
 - в разделе `Languages->PHP->Composer` указать PHP Interpreter: php 7.
 - в разделе `Tools->Command line tool support` или `ctrl+alt+s` и добавляем запись через `+` - `composer` с галочкой на `global` 

> **Примечание:** Если уже установлен, то повторно подключать не надо!

Теперь открываем `Tools->Run command...` или `ctrl+shift+x`. Где вводим команду по очистке кэша:
```bash
c clear-cache
```


1.а) Создайте новый проект в PHPStorm, выберите способ создания `COMPOSER` и установите пакет `schoolphp/framework`.
1.б) Как альтернативу можно запустить команду в `Tools->Run command...`:
```bash
c create-project schoolphp/framework C:/OpenServer/domains/newsite.ru/ 1.1.5
```
> **Примечание:** в данном случае мы указываем путь куда устанавливаем проект, а так же последним параметром указываем версию проекта. Последнюю версию можно увидеть тут:
https://github.com/schoolphp/framework/releases
Хочу заметить, что PHPStorm хранит кэш установок, поэтому через `FILE - NEW PROJECT` может находиться не самая свежая версия. Поэтому данный способ можно считать самым эффективным.

1.в) Альтернативная установка через git: запускаем команды через `Terminal` в PHPStorm, быстрый доступ находится слева внизу:
```bash
git init
git pull https://github.com/schoolphp/framework
```

2) Подключаем `bower`. Открываем конфиг `ctrl+alt+s`: 
```bash
Languages & Frameworks -> JavaScript -> Bower
```
выбираем bower.json из проекта. Устанавливаем все пакеты `bower`, для этого открываем `Terminal` и вводим команду:
```bash
bower install
```

3) Устанавливаем все пакеты `composer` - `Tools -> Run Command` и вводим команду:
```bash
c install
```

4) Устанавливаем следующий менеджер зависимостей NPM. Bootstrap и Font-Awesome перешли с `bower` на `NPM`. Открываем `Terminal`:
```bash
cd skins/components
npm install
cd ../..
```

5) В данной сборке временами я буду обновлять версию `bootstrap`, если же Вы хотите обновить до последней сборки Bootstrap самостоятельно, то необходимо будет собрать css файл из SASS вручную. Делается это следующим образом:

5.1) Установка Ruby + Sass компилятор:
 
https://www.ruby-lang.org/en/downloads/

Запускаете `Поиск` - `cmd` (откроется консоль, можно так же открыть через `Выполнить` - `cmd`), в ней пишете команду:
```bash
gem install sass
```

5.2) Настроить в PHPStorm можно двумя способами. Самый простой - открыть scss файл (scss файл аналогичен less), нам предложат добавить File Watchers сверху, жмём `YES` или:

`Settings` - `File Watchers` - `Добавить` - `SCSS` . Устанавливаем следующие настройки:
```bash
Arguments: --no-cache --update --style compressed $FileName$:$FileNameWithoutExtension$.min.css
Output paths to refresh: $FileNameWithoutExtension$.min.css:$FileNameWithoutExtension$.min.css.map
Immediate file synchronization: отключаем
```

5.3) Открываем файл `skins/components/node_modules/bootstrap/scss/bootstrap.scss` и из неё убираем строку `@import "reboot";` , Сохраняем файл. Получаем в данной папке bootstrap.min.css , он то нам и нужен! Либо укажите путь к файлу, либо скопируйте его в созданную ранее папку `skins/components/bootstrap`, так же в эту папку не забудьте скинуть `skins/components/node_modules/bootstrap/dist/js/bootstrap.min.js`

 

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