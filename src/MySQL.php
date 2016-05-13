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
            $result .= ' -p ' . escapeshellarg($this->dsn['pass']);
        }
        if (!empty($this->dsn['name'])) {
            $result .= ' ' . escapeshellarg($this->dsn['name']);
        }
        $result .= ' -e ' . escapeshellarg((string) $query);

        return $result;
    }
}
