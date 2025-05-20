<?php

require_once __DIR__ . '/vendor/autoload.php';

use Parse\parseJson;

echo "Composer работает!\n";

// Если есть функция parseJson, то вызовем её (с неправильным аргументом, чтобы не ругалась)
if (function_exists('Parse\parseJson')) {
    echo "Функция parseJson найдена!\n";
    // parseJson(); //  Закомментируй эту строку, чтобы не было ошибки
} else {
    echo "Функция parseJson НЕ найдена!\n";
}
