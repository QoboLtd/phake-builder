<?php
/**
 * Sami source documentation tool
 *
 * To generate/update documentation, run:
 * $ ./vendor/bin/sami.php update sami.config.php
 */

return new Sami\Sami('./src', array(
    'build_dir' => 'build/doc/source',
    'cache_dir' => 'build/doc/source/cache',
));
