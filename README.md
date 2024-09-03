# podval.core

# Установка модуля

Для установки модуля - папку podval.core необходимо перенести в /local/modules/.Затем в админке коробки необходимо установить этот модуль в вкладке модулей. Должно выдать успешную установку модуля.


# Работа с модулем

Вся логика находится в папке lib, в соответствии с правилами битрикс. в папке Core находится все ядро системы и в него залезать нужно только на тестовой коробке для исправления или допиливания модуля, на боевых там нечего делать!



Все основные настройки работы ядра и его модулей находятся в settings.php. Там указывается какие модули используются. К примеру миграции, боты и т.д.Все страницы указываются в views/pages/{name_page}. Внутри уже указываются файлы index.php, scripts.js, styles.css и vue.js. Эти файлы подключаются автоматически, если вы размещаете что-то еще, подключайте отдельно в index.php. Роуты в свою очередь уже сами сформируются при загрузке страницы.

К каждой странице, автоматически привязывается контроллер если вы создали такой. Они находятся в Controllers/Pages/{NamePage.php}. Класс необходимо наследовать от \Podval\Core\Classes\Controller и реализовать необходимые методы. Этот контроллер доступен в index.php как $this->controller; Метод Entrance выполняется перед отрисовкой и обработкой индексного файла.

В папке Models указываются модели для работы с БД, создано это для работ с собственными таблицами. Создаете класс который наследует \Podval\Core\Classes\Model для связи с таблицей нужно указать её имя в свойстве protected static ?string $table Так же необходимо реализовать метод getArrayFields который вернет поля в соответствии с типами которые определены в ядре в виде Podval\Core\Database\Field.

В Listeners указываются классы которые наследуют \Podval\Core\Interfaces\Listener и реализуют метод в соответствии с названиями событий битрикса.