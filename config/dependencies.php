<?php

$builder = new DI\ContainerBuilder();

$builder->addDefinitions([
    \PDO::class => function (): \PDO {
        return \HackbartPR\Config\ConnectionCreator::create();
    }
]);

$container = $builder->build();
return $container;
