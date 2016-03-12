<?php
namespace PhakeBuilder;

/**
 * Git Helper Class
 *
 * This class helps with running git commands.  The commands
 * are not actually executed, but returned as strings.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Git extends BaseCommand
{

    /**
     * Log format for getting current commit hash
     */
    const LOG_FORMAT_HASH = '-1 --pretty=format:"%h"';

   /**
    * Log format for generating changelogs
    */
    const LOG_FORMAT_CHANGELOG = '--reverse --no-merges --pretty=format:"* %<(72,trunc)%s (%ad, %an)" --date=short';

    /**
     * Git command string
     */
    protected $command = 'git';

    /**
     * Get abbreviated hash of current commit
     *
     * Thanks to comments on:
     * https://gist.github.com/mamchenkov/930900
     *
     * @return string
     */
    public function getCurrentHash()
    {
        $result = $this->command . ' log ' . self::LOG_FORMAT_HASH;
        return $result;
    }

    /**
     * Get the name of current branch
     *
     * Thanks to:
     * http://stackoverflow.com/questions/6245570/how-to-get-current-branch-name-in-git
     *
     * @return string
     */
    public function getCurrentBranch()
    {
        $result = $this->command . ' rev-parse --abbrev-ref HEAD';
        return $result;
    }

    /**
     * Generate a changelog between two versions
     *
     * Some possible values for the format parameter:
     * * --no-merges --format=%B
     * * --pretty=%s
     *
     * @param  string $from   From reference
     * @param  string $to     (Optional) To reference, HEAD assumed
     * @param  string $format Format
     * @return string
     */
    public function changelog($from, $to = 'HEAD', $format = self::LOG_FORMAT_CHANGELOG)
    {
        $result = $this->command . ' log ' . $from . '..' . $to . ' ' . $format;
        return $result;
    }

    /**
     * Checkout given target
     *
     * @param  string $target Target to checkout
     * @return string
     */
    public function checkout($target)
    {
        $result = $this->command . ' checkout ' . $target;
        return $result;
    }

    /**
     * Pull remote
     *
     * @param  string $remote Remote reference
     * @param  string $branch Branch reference
     * @return string
     */
    public function pull($remote = null, $branch = null)
    {
        $result = $this->command . ' pull ' . $remote . ' ' . $branch;
        return $result;
    }

    /**
     * Push remote
     *
     * @param  string $remote Remote reference
     * @param  string $branch Branch reference
     * @return string
     */
    public function push($remote = null, $branch = null)
    {
        $result = $this->command . ' push ' . $remote . ' ' . $branch;
        return $result;
    }
}
