<?php
// Archive related tasks
group('archive', function () {

    desc('Extract ZIP or TAR archive');
    task('extract', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: archive:extract (Extract ZIP or TAR archive)");

        $src = \PhakeBuilder\Utils::getCurrentDir() . requireValue('EXTRACT_SRC', $app);
        $dst = \PhakeBuilder\Utils::getCurrentDir() . requireValue('EXTRACT_DST', $app);

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

        $src = \PhakeBuilder\Utils::getCurrentDir() . requireValue('COMPRESS_SRC', $app);
        $dst = \PhakeBuilder\Utils::getCurrentDir() . requireValue('COMPRESS_DST', $app);

        try {
            \PhakeBuilder\Archive::compress($src, $dst);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
        printSuccess("SUCCESS!");
    });
});
