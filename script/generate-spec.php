<?php

declare(strict_types=1);

use Script\ExtensionsDocs;

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/ExtensionsDocs.php';

$extensionsDocs = new ExtensionsDocs();

$extensions = $extensionsDocs->getExtensions(); // or: $extensionsDocs->withParamTypes()->getExtensions();

