<?php
/**
 * @var Aura\Di\Container $di The dependency injection container.
 */
$logger = $di->get('logger');

// default log to {$PROJECT_PATH}/tmp/log
$stream = $di->params['Aura\Project_Kernel\ProjectKernel']['base']
        . DIRECTORY_SEPARATOR . 'tmp'
        . DIRECTORY_SEPARATOR . 'log';
$logger->pushHandler($di->newInstance('Monolog\Handler\StreamHandler', array(
    'stream' => $stream,
)));
