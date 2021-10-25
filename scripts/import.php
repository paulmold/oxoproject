<?php
spl_autoload_register();

function help() {
    $usage = 'Usage: php import.php [OPTION]... ' . "\n";
    $usage .= 'Example: php import.php -f=file' . "\n";
    $usage .= sprintf('%5s,  %-15s%-50s' . "\n", '-f', '--file', 'file to import');
    $usage .= sprintf('%5s,  %-15s%-50s' . "\n", '-h', '--help', 'print this help');
    die($usage);
}

$fileName = null;
foreach ($argv as $item) {
    if ($item == 'import.php') {
        continue;
    }
    $components = explode('=', $item);
    switch ($components[0]) {
        case '-f':
        case '--file':
            $fileName = $components[1];
            break;
        case '-h':
        case '--help':
            help();
            break;
    }
}

if (!$fileName || !file_exists($fileName)) {
    die ("-- no file found \n");
}



