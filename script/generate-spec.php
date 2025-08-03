<?php

declare(strict_types=1);

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/ExtensionsDocs.php';

$extensionsDocs = new \Script\ExtensionsDocs();

$extensions = $extensionsDocs->getExtensions(); // or: $extensionsDocs->withDetails()->getExtensions();

