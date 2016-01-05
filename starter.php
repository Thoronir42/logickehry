<?php
include __DIR__.'/libs/autoloader.php';

use config\Config;
use libs\SessionManager;


SessionManager::run();

$dispatcher = Config::createDispatcher();
$dispatcher->dispatch();
