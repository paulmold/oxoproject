<?php

use config\Config;
use model\DbConnection;

spl_autoload_register(function ($class) {
    $file = str_replace('\\', '/', $class . '.php');
    if (file_exists($file)) {
        require $file;
    }
});

(new Config(__DIR__ . '/.env'))->load();

function logMessage(string $message) {
    file_put_contents(__DIR__ . "/" . getenv("LOG_FILE"), $message, FILE_APPEND);
}

/**
 * @throws Exception
 */
function saveJob(string $jobHtml) {
    $dom = new DOMDocument();
    $dom->loadHTML($jobHtml);

    $name = $dom->getElementsByTagName("h2")[0]->nodeValue;
    if (!$name) {
        logMessage("found job without name, skipping entry\n");
        return;
    }
    logMessage("found job \"" . $name . "\"\n");
    $description = $dom->getElementsByTagName("p")[0]->nodeValue;
    if (!$description) {
        logMessage("job description missing\n");
    }
    $tableValues = $dom->getElementsByTagName("td");
    $expiration = $tableValues[1]->nodeValue;
    if (!$expiration) {
        logMessage("job expiration date missing\n");
    }
    $openings = $tableValues[3]->nodeValue;
    if (!$openings) {
        logMessage("job openings missing\n");
    }
    $companyName = $tableValues[5]->nodeValue;
    if (!$companyName) {
        logMessage("job company name missing\n");
    }
    $professionName = $tableValues[7]->nodeValue;
    if (!$professionName) {
        logMessage("job profession missing\n");
    }

    $companyId = (new \model\Company())->name($companyName)->add();
    $professionId = (new \model\Profession())->name($professionName)->add();

    (new \model\Job())
        ->name($name)
        ->description($description)
        ->openings($openings)
        ->expiration($expiration)
        ->company_id($companyId)
        ->profession_id($professionId)
        ->save();

    logMessage("saved job to database\n");
}

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

logMessage("---Import script started--- \n");

if (!$fileName || !file_exists(__DIR__ . '/' . $fileName)) {
    logMessage("!!!import file not found (" . __DIR__ . '/' . $fileName . ") \n");
    die ("-- no file found \n");
}

logMessage("import file found \n");

$fileData = function () use ($fileName) {
    $file = fopen(__DIR__ . '/' . $fileName, 'r');

    if (!$file) {
        die('file does not exist or cannot be opened');
    }

    while (($line = fgets($file)) !== false) {
        yield $line;
    }

    fclose($file);
};

DbConnection::getInstance()->connection()->begin_transaction();

$jobHtml = '';
$start = false;
logMessage("started reading file line by line\n");
foreach ($fileData() as $line) {
    if (str_contains($line, 'div class="job"')) {
        $start = true;
    }
    if ($start) {
        $jobHtml .= $line . "\n";
        if (str_contains($line, '</div>')) {
            saveJob($jobHtml);
            $jobHtml = '';
            $start = false;
        }
    }
}

logMessage("---Started database cleanup---\n");
(new \model\Job())->cleanup();
logMessage("cleanup completed\n");

DbConnection::getInstance()->connection()->commit();

