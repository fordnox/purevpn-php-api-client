<?php

/**
 * A simple wrapper for the PureVPN API.
 *
 * @package Fordnox
 * @author  Andrius Putna <fordnox@gmail.com>
 * @license MIT
 */

namespace Fordnox\Purevpn;

class Account
{
    /**
     * @var string - STANDARD,SSTP,HIGH-BW
     */
    protected $type = 'STANDARD';

    /**
     * @var string - #30,#90,#180,#365,#default
     */
    protected $period = '#365';

    /**
     * @var null
     */
    protected $username = null;

    /**
     * @var null
     */
    protected $password = null;

    /**
     * @var string - disabled/enabled/deleted
     */
    protected $status = 'deleted';

    /**
     * @param null $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param null $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param null $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }

    /**
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    public function isEnabled()
    {
        return ($this->getStatus() == 'enabled');
    }
}