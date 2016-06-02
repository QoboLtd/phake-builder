<?php
namespace PhakeBuilder;

/**
 * MySQL Helper Class
 *
 * This class helps with running MySQL commands.  The commands
 * are not actually executed, but returned as strings.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class MySQL extends BaseCommand
{

    /**
     * MySQL command string
     */
    protected $command = 'mysql';

    /**
     * DSN
     */
    protected $dsn = array();

    public function setDSN(array $dsn = array())
    {
        $defaulDSN = array(
            'host' => 'localhost',
            'port' => 3306,
            'user' => 'root',
            'pass' => '',
            'name' => '',
        );

        if (is_arraY($dsn)) {
            $this->dsn = array_merge($defaulDSN, $dsn);
        }
    }

    public function getDSN()
    {
        return $this->dsn;
    }

    public function findReplace($find, $replace)
    {
        $result = $this->command;
        if (isset($this->dsn['host'])) {
            $result .= ' -h ' . escapeshellarg($this->dsn['host']);
        }
        if (isset($this->dsn['port'])) {
            $result .= ' --port ' . escapeshellarg($this->dsn['port']);
        }
        if (isset($this->dsn['user'])) {
            $result .= ' -u ' . escapeshellarg($this->dsn['user']);
        }
        if (isset($this->dsn['pass'])) {
            $result .= ' -p ' . escapeshellarg($this->dsn['pass']);
        }
        if (isset($this->dsn['name'])) {
            $result .= ' -n ' . escapeshellarg($this->dsn['name']);
        }

        $result .= ' -s ' . escapeshellarg((string)$find);
        $result .= ' -r ' . escapeshellarg((string)$replace);

        return $result;
    }

    public function query($query)
    {
        $result = $this->command;
        if (!empty($this->dsn['host'])) {
            $result .= ' -h ' . escapeshellarg($this->dsn['host']);
        }
        if (!empty($this->dsn['port'])) {
            $result .= ' -P ' . escapeshellarg($this->dsn['port']);
        }
        if (!empty($this->dsn['user'])) {
            $result .= ' -u ' . escapeshellarg($this->dsn['user']);
        }
        if (!empty($this->dsn['pass'])) {
            $result .= ' -p' . escapeshellarg($this->dsn['pass']);
        }
        if (!empty($this->dsn['name'])) {
            $result .= ' ' . escapeshellarg($this->dsn['name']);
        }
        $result .= ' -e ' . escapeshellarg((string) $query);

        return $result;
    }

    public function import($file)
    {
        return $this->query("SOURCE $file");
    }

    public function grant($tables, $user, $password = null, $access = 'ALL')
    {
        // If only the database name is given, apply grant to all tables
        if (false === strpos($tables, '.')) {
            $tables .= '.*';
        }

        // If only the username is given, apply grant to all hosts
        if (false === strpos($user, '@')) {
            $user = '"' . $user . '"@"%"';
        }

        $query = "GRANT $access ON $tables TO $user";
        if (!empty($password)) {
            $query .= ' IDENTIFIED BY "' . $password . '"';
        }

        return $this->query($query);
    }

    public function revoke($tables, $user, $access = 'ALL')
    {
        // If only the database name is given, apply revoke to all tables
        if (false === strpos($tables, '.')) {
            $tables .= '.*';
        }

        // If only the username is given, apply revoke to all hosts
        if (false === strpos($user, '@')) {
            $user = '"' . $user . '"@"%"';
        }

        $query = "REVOKE $access ON $tables FROM $user";

        return $this->query($query);
    }

    public function fileAllow($user)
    {
        return $this->grant('*', $user, null, 'FILE');
    }

    public function fileDeny($user)
    {
        return $this->revoke('*', $user, 'FILE');
    }
}
