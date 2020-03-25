<?php

namespace Magmell\Contao;

use Contao\Session;

/**
 * Class PasswordlessSessionMessage
 * @package Magmell\Contao
 */
class PasswordlessMessage
{
    /**
     * @var static|null
     */
    private static $instance = null;

    /**
     * @var Session
     */
    protected $session;

    /**
     * PasswordlessMessage constructor.
     */
    private function __construct() {
        $this->session = Session::getInstance();
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new static;
        }

        return self::$instance;
    }

    /**
     * @return bool
     */
    public function hasAny()
    {
        return !empty($this->getErrors())
            || !empty($this->getInfos())
            || !empty($this->getConfirms());
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->session->get('passworless_message_errors');
    }

    /**
     * @return array
     */
    public function getInfos()
    {
        return $this->session->get('passworless_message_infos');
    }

    /**
     * @return array
     */
    public function getConfirms()
    {
        return $this->session->get('passworless_message_confirms');
    }

    /**
     * @param string $message
     */
    public function setErrorMessage(string $message)
    {
        $errors = $this->session->get('passworless_message_errors') ?: [];
        $errors[] = $message;
        $this->session->set('passworless_message_errors', $errors);
    }

    /**
     * @param string $message
     */
    public function setInfoMessage(string $message)
    {
        $infos = $this->session->get('passworless_message_infos') ?: [];
        $infos[] = $message;
        $this->session->set('passworless_message_infos', $infos);
    }

    /**
     * @param string $message
     */
    public function setConfirmMessage(string $message)
    {
        $confirms = $this->session->get('passworless_message_confirms') ?: [];
        $confirms[] = $message;
        $this->session->set('passworless_message_confirms', $confirms);
    }

    /**
     * @return string|null
     */
    public function shiftError()
    {
        $errors = $this->getErrors();
        $message = array_shift($errors);
        $this->session->set('passworless_message_errors', $errors);
        return $message;
    }

    /**
     * @return string|null
     */
    public function shiftInfo()
    {
        $infos = $this->getInfos();
        $message = array_shift($infos);
        $this->session->set('passworless_message_infos', $infos);
        return $message;
    }

    /**
     * @return string|null
     */
    public function shiftConfirm()
    {
        $confirms = $this->getConfirms();
        $message = array_shift($confirms);
        $this->session->set('passworless_message_confirms', $confirms);
        return $message;
    }
}
