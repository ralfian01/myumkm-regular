<?php

$projectPath = __DIR__;
//Declare directories which contains php code
$scanDirectories = [
    $projectPath . '/../app/',
    $projectPath . '/../Acme/',
    $projectPath . '/../resources/views/',
    $projectPath . '/../routes/'
];
//Optionally declare standalone files
$scanFiles = [
    $projectPath . '/../helpers.php',
];
return [
    'composerJsonPath' => $projectPath . '/../../CI4_Cores/composer.json',
    'vendorPath' => $projectPath . '/../../CI4_Cores/vendor/',
    'scanDirectories' => $scanDirectories,
    'scanFiles' => $scanFiles
];
