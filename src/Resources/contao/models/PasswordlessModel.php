<?php

namespace Contao;

/**
 * Class PasswordlessModel
 * @package Contao
 *
 * @property int $tstamp
 * @property int $ttl
 * @property string $username
 * @property string $password
 * @property bool $used
 */
class PasswordlessModel extends Model
{
    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_passwordless';

    /**
     * @param string $strUsername
     * @param array $arrOptions
     * @return PasswordlessModel
     */
    public static function findByUsername(string $strUsername, array $arrOptions = [])
    {
        $t = static::$strTable;
        return static::findOneBy(["$t.username=?"], [$strUsername], $arrOptions);
    }

    /**
     * @param string $username
     * @return $this
     * @throws \Exception
     */
    public function refreshLogin($username = '')
    {
        if ($username)
        {
            $this->username = $username;
        }

        if (!$this->username) {
            throw new \Exception("Missing 'username' property value");
        }

        $this->tstamp = time();
        $this->ttl = Config::get('passwordless_ttl') ?: 600; // 10min by default
        $this->password = bin2hex(openssl_random_pseudo_bytes(255));
        $this->used = false;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAlive()
    {
        return time() <= $this->tstamp + $this->ttl;
    }
}
