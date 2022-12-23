<?
CModule::AddAutoloadClasses(
    '', // не указываем имя модуля
    array(
        // ключ - имя класса с простанством имен, значение - путь относительно корня сайта к файлу
        'Bitrix\Task\TaskTable' => '/local/php_interface/classes/tasktable.php',
    )
);