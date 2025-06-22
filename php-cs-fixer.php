<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ );

return (new Config())
    ->setRules([
        '@PSR12' => true,
    ])
    ->setFinder($finder);
