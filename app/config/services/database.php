<?php
use Phalcon\Db\Adapter\Pdo\Mysql as MySQL;
use Phalcon\Mvc\Model\Manager as ModelManager;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\MetaData\Redis as ModelMetaRedis;
use Phalcon\Cache\Backend\Redis as RedisCache;
use Phalcon\Cache\Frontend\Data as FrontendCache;

/**
 * ==============================================================
 * Database Connection
 * =============================================================
 */
$di->set('db', function () use ($di, $config, $eventsManager) {
    $eventsManager->attach('db', new Middleware\Database());

    $database = new MySQL((array) $config->get('database'));
    $database->setEventsManager($eventsManager);

    return $database;
});

/**
 * ==============================================================
 * Model Manager
 * =============================================================
 */
$di->set('modelsManager', function () {
    Model::setup(['ignoreUnknownColumns' => true]);
    return new ModelManager();
});

/**
 * ==============================================================
 * Model Meta Data (Uses Redis)
 * =============================================================
 */
$di->set('modelsMetadata', function () use ($redis) {
    return new ModelMetaRedis([
        "lifetime" => 3600,
        "redis"    => $redis,
    ]);
});

/**
 * ==============================================================
 * ORM And Front-end Caching
 * =============================================================
 */
$di->set('modelsCache', function () use ($redis) {

    // Cache data for one day by default
    // It's cleared using fabfile for a deploy
    $frontCache = new FrontendCache([
        "lifetime" => 86400,
    ]);

    // Redis connection settings
    return new RedisCache($frontCache, [
        "redis" => $redis,
    ]);

});
