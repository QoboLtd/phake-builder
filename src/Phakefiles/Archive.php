<?php
/**
 * Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

// Archive related tasks
group('archive', function () {

    desc('Extract ZIP or TAR archive');
    task('extract', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: archive:extract (Extract ZIP or TAR archive)");

        $src = requireValue('EXTRACT_SRC', $app);
        $dst = requireValue('EXTRACT_DST', $app);

        try {
            \PhakeBuilder\Archive::extract($src, $dst);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
        printSuccess("SUCCESS!");
    });

    desc('Create ZIP or TAR archive');
    task('compress', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: archive:compress (Create ZIP or TAR archive)");

        $src = requireValue('COMPRESS_SRC', $app);
        $dst = requireValue('COMPRESS_DST', $app);

        try {
            \PhakeBuilder\Archive::compress($src, $dst);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
        printSuccess("SUCCESS!");
    });
});
