<?php

$client_dir = getcwd()."/client";//папка, где лежит распакованный client.zip (ну файлы из него)
$cache_dir = getcwd()."/cache";//кеш
$block_size = 1024*1024;//Byte (1024 * 1024 = 1 MB) при изменении могут возникнуть проблемы с кешированием
$cache_enabled = false;//Использовать кеширование ? (при изменении размера блока удаляйте папку с кешем, т.к. автоочистка не предусмотрена)

$MT_ZIP_mode = true;
$MT_ZIP_file = "client.zip";