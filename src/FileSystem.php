<?php
namespace PhakeBuilder;

use \Symfony\Component\Filesystem\Filesystem as FS;
use \Heartsentwined\FileSystemManager\FileSystemManager;

/**
 * File class
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class FileSystem
{

    const DEFAULT_DIR_MODE  = 0775;
    const DEFAULT_FILE_MODE = 0664;

    /**
     * Symfony Filesystem component instance
     */
    protected static $fs;

    public static function __callStatic($method, $args)
    {
        if (is_null(self::$fs)) {
            self::$fs = new FS;
        }

        return call_user_func_array(array(self::$fs, $method), $args);
    }

    /**
     * Get default directory mode
     *
     * Return a fallback default for directory mode
     *
     * @return numeric
     */
    protected static function getDefaultDirMode()
    {
        return self::DEFAULT_DIR_MODE;
    }

    /**
     * Get default file mode
     *
     * Return a fallback default for file mode
     *
     * @return numeric
     */
    protected static function getDefaultFileMode()
    {
        return self::DEFAULT_FILE_MODE;
    }

    /**
     * Get default user
     *
     * Return a fallback default for user (assumses owner of current process)
     *
     * @return string
     */
    protected static function getDefaultUser()
    {
        $processUser = posix_getpwuid(posix_geteuid());
        return $processUser['name'];
    }

    /**
     * Get default group
     *
     * Return a fallback default for group (assumses owner of current process)
     *
     * @return string
     */
    protected static function getDefaultGroup()
    {
        $processGroup = posix_getgrgid(posix_getegid());
        return $processGroup['name'];
    }

    /**
     * Make directory (recursively)
     *
     * Thanks to: http://stackoverflow.com/questions/6229353/permissions-with-mkdir-wont-work
     *
     * @param  string  $path Path to make
     * @param  numeric $mode Permission mask
     * @return boolean True on success, false otherwise
     */
    public static function makeDir($path, $mode = null)
    {
        $mode = $mode ? self::valueToOct($mode) : self::getDefaultDirMode();
        return self::mkdir($path, $mode);
    }

    /**
     * Remove file or folder (recursively)
     *
     * Thanks to: http://stackoverflow.com/a/15111679/151647
     *
     * @param  string $path Path to remove
     * @return boolean True on success, false otherwise
     */
    public static function removePath($path)
    {
        $result = false;

        if (is_dir($path)) {
            $it = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $path,
                    \FilesystemIterator::SKIP_DOTS
                ),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($it as $item) {
                $result = $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
            }
            $result = rmdir($path);
        } else {
            $result = unlink($path);
        }

        return $result;
    }

    /**
     * Change permissions on path
     *
     * @param  string  $path      Path to chmod
     * @param  numeric $dirMode   Mode to set for folders (octal)
     * @param  numeric $fileMode  Mode to set for files (octal)
     * @param  boolean $recursive Recurse into path or no (default: yes)
     * @return boolean True on success, false otherwise
     */
    public static function chmodPath($path, $dirMode = null, $fileMode = null, $recursive = true)
    {
        $result = false;

        $dirMode = $dirMode ?: self::getDefaultDirMode();
        $fileMode = $fileMode ?: self::getDefaultFileMode();

        $result = is_dir($path) ? chmod($path, self::valueToOct($dirMode)) : chmod($path, self::valueToOct($fileMode));
        if ($recursive && is_dir($path)) {
            // Folders first
            foreach (FileSystemManager::dirIterator($path) as $item) {
                $result = chmod($item, self::valueToOct($dirMode));
            }
            // Files next
            foreach (FileSystemManager::fileIterator($path) as $item) {
                $result = chmod($item, self::valueToOct($fileMode));
            }
        }

        return $result;
    }

    /**
     * Change user ownership on path
     *
     * @param  string  $path      Path to chown
     * @param  string  $user      User to change ownership to
     * @param  boolean $recursive Recurse into path or no (default: yes)
     * @return boolean True on success, false otherwise
     */
    public static function chownPath($path, $user, $recursive = true)
    {
        $result = false;

        $user = $user ?: self::getDefaultUser();
        if ($recursive) {
            $result = FileSystemManager::rchown($path, $user);
        } else {
            $result = chown($path, $user);
        }

        return $result;
    }

    /**
     * Change group ownership on path
     *
     * @param  string  $path      Path to chgrp
     * @param  string  $group     Group to change ownership to
     * @param  boolean $recursive Recurse into path or no (default: yes)
     * @return boolean True on success, false otherwise
     */
    public static function chgrpPath($path, $group, $recursive = true)
    {
        $result = false;

        $group = $group ?: self::getDefaultGroup();
        if ($recursive) {
            $result = FileSystemManager::rchown($path, $group);
        } else {
            $result = chgrp($path, $user);
        }

        return $result;
    }

    /**
     * Download remote file using CURL
     *
     * @param  string $src URL to file
     * @param  string $dst Filepath to the local result
     * @return boolean True on success, false otherwise
     */
    public static function downloadFile($src, $dst)
    {
        $result = false;

        $fh = fopen($dst, 'w');
        if (!is_resource($fh)) {
            return $result;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $src);
        curl_setopt($curl, CURLOPT_FILE, $fh);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $result = curl_exec($curl);
        curl_close($curl);
        fclose($fh);

        return $result;
    }

    /**
     * Convert a given value to octal
     *
     * Thanks to: http://stackoverflow.com/questions/13112934/ishex-and-isocta-functions
     *
     * @param  string|numeric $value String, decimal integer, or octal value
     * @return numeric Octal value
     */
    protected static function valueToOct($value)
    {
        $result = $value;

        // If the value is a string in a form '0777', then extract octal value
        if (is_string($value) && (strpos($value, '0') === 0)) {
            $value = intval($value, 8);
        }

        // If the value is not octal, convert
        if (decoct(octdec($value)) <> $value) {
            $result = base_convert((string) $value, 10, 8);
        }

        return $value;
    }

    /**
     * Return iterator (iteratable) from path
     *
     * @param  string  $path      Path to file or folder
     * @param  boolean $recursive Recurse into path or not
     * @return RecursiveIteratorIterator|array
     */
    protected static function getIteratorFromPath($path, $recursive = true)
    {
        $result = null;

        if ($recursive && is_dir($path)) {
            $result = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $path,
                    \FilesystemIterator::SKIP_DOTS
                ),
                \RecursiveIteratorIterator::SELF_FIRST
            );
        } else {
            $result = array(new \SplFileInfo($path));
        }

        if (is_object($result) && !$result->valid()) {
            $result = array(new \SplFileInfo($path));
        }

        return $result;
    }
}
