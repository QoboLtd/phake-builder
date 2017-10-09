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
namespace PhakeBuilder;

/**
 * Archive Helper Class
 *
 * This class helps with compressing and extracting archives.
 * All the heavy lifting is done by the zetacomponents/archive library.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Archive
{

    /**
     * Extract a ZIP or TAR archive
     *
     * @param  string $src Path to the archive file
     * @param  string $dst Path to the destination
     * @return boolean
     */
    public static function extract($src, $dst)
    {
        $result = false;

        $archive = \ezcArchive::open($src);
        $archive->extract($dst);
        $result = true;

        return $result;
    }

    /**
     * Compress a ZIP or TAR archive
     *
     * This functionality is not implemented yet. This is just a placeholder.
     *
     * @todo   Populate this placeholder
     * @param  string $src Path to file or folder to be compressed
     * @param  string $dst Path tot he resulting archive file
     * @return boolean
     */
    public static function compress($src, $dst)
    {
        throw new \RuntimeException("Cannot compress [$src] to [$dst]. This functionality is not implemented yet");
    }
}
