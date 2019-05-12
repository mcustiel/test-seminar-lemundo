<?php

return [
    'databaseDsn' => 'sqlite:' . BASE_PATH . DIRECTORY_SEPARATOR . getenv('DATABASE'),
    'debugMode' => strtolower((string) getenv('DEBUG_MODE')) === 'true',
    'cachePath' => getenv('CACHE_PATH')
];
