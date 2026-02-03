<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/lang',
        __DIR__ . '/modules',
        __DIR__ . '/public',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets()  // Uncomment this to use PHP version specific rules
    ->withTypeCoverageLevel(5)    // Increase type coverage checking
    ->withDeadCodeLevel(5)        // Detect unused code
    ->withCodeQualityLevel(5);  
