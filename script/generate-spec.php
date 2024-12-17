<?php

declare(strict_types=1);

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/ExtensionsDocs.php';

$documentor = new \Script\ExtensionsDocs();

$extensions = $documentor->getExtensions(); // or: $documentor->withDetails()->getExtensions();

