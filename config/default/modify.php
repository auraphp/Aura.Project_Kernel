<?php
/**
 * @var Aura\Di\Container $di The dependency injection container.
 */
$logger = $di->get('logger');

// default log location is {$PROJECT_PATH}/tmp/log, determined relative to
// {$PROJECT_PATH}/vendor/aura/project-kernel/config/default/modify.php
$stream = dirname(dirname(dirname(dirname(dirname(__DIR__)))))
        . DIRECTORY_SEPARATOR . 'tmp'
        . DIRECTORY_SEPARATOR . 'log';
$logger->pushHandler($di->newInstance('Monolog\Handler\StreamHandler', array(
    'stream' => $stream,
)));
