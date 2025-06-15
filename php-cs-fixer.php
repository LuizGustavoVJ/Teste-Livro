<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/app'); // Altere para o diretório do seu código, ex: /src

return (new Config())
    ->setRules([
        '@PSR12' => true,
    ])
    ->setFinder($finder);
