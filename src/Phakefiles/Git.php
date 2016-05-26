<?php
require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// Git related tasks
group(
    'git', function () {

        desc('Git checkout');
        task(
            'checkout', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Git checkout");

                $git = new \PhakeBuilder\Git(getValue('SYSTEM_COMMAND_GIT', $app));
                try {
                    $currentBranch = doShellCommand($git->getCurrentBranch(), null, true);
                    printDebug("Current branch: [$currentBranch]");

                    $requiredBranch = getValue('GIT_BRANCH', $app);
                    if (empty($requiredBranch)) {
                        throw new \RuntimeException("Skipping git checkout - no required branch given");
                    }
                    if ($currentBranch == $requiredBranch) {
                        throw new \RuntimeException("Skipping git checkout - already on branch [$requiredBranch]");
                    }
                    doShellCommand($git->checkout($requiredBranch));
                } catch (\Exception $e) {
                    printWarning($e->getMessage());
                }
            }
        );

        desc('Git changelog');
        task(
            'changelog', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Git changelog");

                $oldHash = requireValue('GIT_OLD_HASH', $app);
                $newHash = requireValue('GIT_NEW_HASH', $app);
                printInfo("Old hash: $oldHash");
                printInfo("New hash: $newHash");

                $git = new \PhakeBuilder\Git(getValue('SYSTEM_COMMAND_GIT', $app));

                $changelog = doShellCommand($git->changelog($oldHash, $newHash), null, true);
                $changelog = $changelog ?: 'Not available';

                printInfo("Changelog\n$changelog");
            }
        );

        desc('Git pull');
        task(
            'pull', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Git pull");

                $changelog = '';

                $git = new \PhakeBuilder\Git(getValue('SYSTEM_COMMAND_GIT', $app));
                $oldHash = doShellCommand($git->getCurrentHash(), null, true);

                $remote = getValue('GIT_REMOTE', $app);
                $branch = getValue('GIT_BRANCH', $app);
                doShellCommand($git->pull($remote, $branch));

                $newHash = doShellCommand($git->getCurrentHash(), null, true);
                $changelog = doShellCommand($git->changelog($oldHash, $newHash), null, true);
                printDebug("Either not a git repo or no remotes configured");

                $changelog = $changelog ?: 'Not available';
                printInfo("Changelog\n$changelog");
            }
        );

        desc('Git push');
        task(
            'push', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Git push");

                $remote = getValue('GIT_REMOTE', $app);
                $branch = getValue('GIT_BRANCH', $app);

                $git = new \PhakeBuilder\Git(getValue('SYSTEM_COMMAND_GIT', $app));
                doShellCommand($git->push($remote, $branch));
            }
        );
    }
);