<?php

use lithium\core\Libraries;
use lithium\core\Environment;

use Airbrake\Client;
use Airbrake\Configuration;
use Airbrake\Connection;
use Airbrake\EventHandler;
use Airbrake\Exception;
use Airbrake\Notice;
use Airbrake\Record;
use Airbrake\Version;

$_config = Libraries::get('li3_airbrake');

if (isset($_config['apiKey']) && !empty($_config['apiKey'])) {
    if (!isset($_config['notifyOnWarning']) || empty($_config['notifyOnWarning'])) {
        $_config['notifyOnWarning'] = (bool) (E_NOTICE & error_reporting());
    }
    if (!isset($_config['options'])) {
        $_config['options'] = array();
    }
    if (!isset($_config['options']['environmentName']) || empty($_config['options']['environmentName'])) {
        $_config['options']['environmentName'] = Environment::get();
    }
    if (!isset($_config['options']['projectRoot']) || empty($_config['options']['projectRoot'])) {
        $_config['options']['projectRoot'] = LITHIUM_APP_PATH;
    }

    // Setup Airbrake
    $config  = new Configuration($_config['apiKey'], $_config['options']);
    $client  = new Client($config);
    $handler = new EventHandler($client, (bool) $_config['notifyOnWarning']);

    // Apply Error handler
    set_error_handler(function($errno, $errstr, $errfile = null, $errline = 0, array $errcontext = array()) use ($handler) {
        $handler->onError($errno, $errstr, $errfile, $errline, $errcontext);
    });

    // Apply Exception handler
    // $previousExceptionHandler = set_exception_handler();
    // set_exception_handler(function($exception) use ($handler, $previousExceptionHandler) {
    //     $handler->onException($exception);
    //     if ($previousExceptionHandler) {
    //         call_user_func($previousExceptionHandler, $exception);
    //     }
    // });

    register_shutdown_function(array($handler, 'onShutdown'));
}

unset($_config);
