<?php
require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// Filesystem related tasks
group(
    'file', function () {

        desc('Process template file');
        task(
            'process', ':builder:init', function ($app) {
                printSeparator();
                printInfo('Processing template');

                $src = requireValue('TEMPLATE_SRC', $app);
                $dst = requireValue('TEMPLATE_DST', $app);

                $template = new \PhakeBuilder\Template($src);
                $placeholders = $template->getPlaceholders();
                if (empty($placeholders)) {
                    printWarning("Template file [$src] has no placeholders");
                } else {
                    $data = array();
                    foreach ($placeholders as $placeholder) {
                        $data[$placeholder] = getValue($placeholder, $app);
                    }
                }

                $bytes = $template->parseToFile($dst, $data);
                if (!$bytes) {
                    throw new \RuntimeException("Failed to write to template destination [$dst]");
                }
                printSuccess("SUCCESS! Processed $bytes bytes.");
            }
        );

        desc('Create empty file or update timestamp of existing');
        task(
            'touch', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Touching file");

                $file = requireValue('TOUCH_PATH', $app);
                $result = touch($file);
                if (!$result) {
                    throw new \RuntimeException("Failed to touch file");
                }
                printSuccess("SUCCESS!");
            }
        );

        desc('Create symbolic link');
        task(
            'link', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Creating link");
                $src = requireValue('LINK_SRC', $app);
                $dst = requireValue('LINK_DST', $app);
                $result = symlink($src, $dst);
                if (!$result) {
                    throw new \RuntimeException("Failed to create symbolic link");
                }
                printSuccess("SUCCESS!");
            }
        );

        desc('Rename file or folder');
        task(
            'mv', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Renaming file or folder");

                $src = requireValue('MV_SRC', $app);
                $dst = requireValue('MV_DST', $app);
                $result = rename($src, $dst);
                if (!$result) {
                    throw new \RuntimeException("Failed to rename file or folder");
                }
                printSuccess("SUCCESS!");
            }
        );

        desc('Recursively remove file or folder');
        task(
            'rm', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Removing file or folder");
                $path = requireValue('RM_PATH', $app);
                $result = \PhakeBuilder\FileSystem::removePath($path);
                if (!$result) {
                    throw new \RuntimeException("Failed to remove path");
                }
                printSuccess("SUCCESS!");
            }
        );

        desc('Create folder');
        task(
            'mkdir', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Creating folder");

                $path = requireValue('MKDIR_PATH', $app);
                $mode = getValue('MKDIR_MODE', $app);
                $result = \PhakeBuilder\FileSystem::makeDir($path, $mode);
                if (!$result) {
                    throw new \RuntimeException("Failed to create directory");
                }
                printSuccess("SUCCESS!");
            }
        );

        desc('Change permissions on path');
        task(
            'chmod', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Changing permissions");

                $path = requireValue('CHMOD_PATH', $app);
                $dirMode = getValue('CHMOD_DIR_MODE', $app);
                $fileMode = getValue('CHMOD_FILE_MODE', $app);
                $result = \PhakeBuilder\FileSystem::chmodPath($path, $dirMode, $fileMode);
                if (!$result) {
                    throw new \RuntimeException("Failed to change permissions");
                }
                printSuccess("SUCCESS!");
            }
        );

        desc('Change user ownership on path');
        task(
            'chown', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Changing user ownership");

                $path = requireValue('CHOWN_PATH', $app);
                $user = getValue('CHOWN_USER', $app);
                $result = \PhakeBuilder\FileSystem::chownPath($path, $user);
                if (!$result) {
                    throw new \RuntimeException("Failed to change user ownership");
                }
                printSuccess("SUCCESS!");
            }
        );

        desc('Change group ownership on path');
        task(
            'chgrp', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Changing group ownership");

                $path = requireValue('CHGRP_PATH', $app);
                $group = getValue('CHGRP_GROUP', $app);
                $result = \PhakeBuilder\FileSystem::chgrpPath($path, $group);
                if (!$result) {
                    throw new \RuntimeException("Failed to change group ownership");
                }
                printSuccess("SUCCESS!");
            }
        );

        desc('Download file from URL');
        task(
            'download', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Download file");

                $src = requireValue('DOWNLOAD_SRC', $app);
                $dst = requireValue('DOWNLOAD_DST', $app);
                $result = \PhakeBuilder\FileSystem::downloadFile($src, $dst);
                if (!$result) {
                    throw new \RuntimeException("Failed to download file");
                }
                printSuccess("SUCCESS!");
            }
        );
    }
);