<?php
/**
 * Sami source documentation tool
 *
 * To generate/update documentation, run:
 * $ ./vendor/bin/sami.php update sami.config.php
 */

try {
    Dotenv::load(__DIR__ . DIRECTORY_SEPARATOR . '..');
}
catch (\Exception $e) {
    echo $e->getMessage();
    exit(1);
}

return new Sami\Sami('./src', array(
    'title'     => getenv('PROJECT_NAME'),
    'build_dir' => 'build/doc/source',
    'cache_dir' => 'build/doc/source/cache',
));
