<?php
// Archive related tasks
group('archive', function () {

    desc('Extract ZIP or TAR archive');
    task('extract', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Extract ZIP or TAR archive");

        $src = requireValue('EXTRACT_SRC', $app);
        $dst = requireValue('EXTRACT_DST', $app);

        try {
            \PhakeBuilder\Archive::extract($src, $dst);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    });

    desc('Create ZIP or TAR archive');
    task('compress', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Create ZIP or TAR archive");

        $src = requireValue('COMPRESS_SRC', $app);
        $dst = requireValue('COMPRESS_DST', $app);

        try {
            \PhakeBuilder\Archive::compress($src, $dst);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    });

});
