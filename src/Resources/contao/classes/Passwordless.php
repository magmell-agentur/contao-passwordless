<?php

namespace Magmell\Contao;

use Contao\Config;
use Contao\Email;
use Contao\Environment;
use Contao\Input;
use Contao\PasswordlessModel;
use Contao\User;

/**
 * Class Passwordless
 * @package Magmell\Contao
 *
 * @property User $user
 * @property string $oneTimePassword
 * @property PasswordlessMessage $message
 */
class Passwordless
{

    /**
     * Passwordless constructor.
     */
    public function __construct()
    {
        $this->message = PasswordlessMessage::getInstance();
    }

    /**
     * @param string $username
     * @param mixed $credentials
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function checkCredentials($username, $credentials, $user)
    {
        $this->user = $user;

        if (!$this->user->email)
        {
            $this->message->setErrorMessage('Found user is missing email address.');
        }

        $password = Input::post('oneTimePassword');

        if ($password) {
            $this->oneTimePassword = $password;
            return $this->authenticate();
        }

        $this->generateOneTimePassword();
        $this->sendEmail();
        $this->user->loginAttempts -= 1; // so generating one time password does not count as unsuccessful login attempt
        $this->user->save();
        $this->message->setInfoMessage('Login link is sent to your email address.');

        return false;
    }

    protected function authenticate()
    {
        $passwordlessModel = PasswordlessModel::findByUsername($this->user->username);

        if ($passwordlessModel
            && !$passwordlessModel->used
            && $passwordlessModel->password === $this->oneTimePassword
            && $passwordlessModel->isAlive())
        {
            $passwordlessModel->used = true;
            $passwordlessModel->save();
            return true;
        }

        $link = $this->getLoginPageLink();

        if ($passwordlessModel->used)
        {
            $this->message->setErrorMessage(sprintf("Link already used once, generate a new one <a href=\"%s\">HERE</a>", $link));
        }

        if ($passwordlessModel->password !== $this->oneTimePassword)
        {
            $this->message->setErrorMessage("Wrong login link.");
        }

        if (!$passwordlessModel->isAlive())
        {
            $this->message->setErrorMessage(sprintf("Link expired, generate a new one <a href=\"%s\">HERE</a>", $link));
        }

        return false;
    }

    /**
     * @throws \Exception
     */
    protected function generateOneTimePassword()
    {
        $passwordlessModel = PasswordlessModel::findByUsername($this->user->username);

        if (!$passwordlessModel)
        {
            $passwordlessModel = new PasswordlessModel();
            $passwordlessModel->username = $this->user->username;
        }

        $passwordlessModel->refreshLogin()->save();

        $this->oneTimePassword = $passwordlessModel->password;
    }

    protected function sendEmail()
    {
        $objEmail = new Email();
        $objEmail->from = Config::get('passwordless_emailFrom');
        $objEmail->fromName = Config::get('passwordless_emailFromName') ?: 'Contao';
        $objEmail->subject = Config::get('passwordless_emailSubject') ?: 'Login';

        $login = sprintf("%s?username=%s&oneTimePassword=%s",
            (!strpos(Environment::get('uri'), '?') ? Environment::get('uri') : substr(Environment::get('uri'), 0, strpos(Environment::get('uri'), "?"))),
            $this->user->username,
            $this->oneTimePassword

        );

        $objEmail->html = $login; // TODO: use template
        $objEmail->sendTo($this->user->email);
    }

    /**
     * @return string
     */
    protected function getLoginPageLink()
    {
        $link = !strpos(Environment::get('uri'), '?') ?
            Environment::get('uri') :
            substr(Environment::get('uri'), 0, strpos(Environment::get('uri'), "?"));

        if (Input::get('username'))
        {
            $link .= '?username=' . Input::get('username');
        }

        return $link;
    }
}
