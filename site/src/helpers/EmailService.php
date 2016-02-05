<?php
namespace app\helpers;

class EmailEntity {
    public $subject;
    public $to;
    public $replyTo;
    public $body;
    public $images = array();
}

class EmailService {
    //
    private $debug = false;

    /**
     *
     * @param \Silex\Application $app
     */
    public function __construct(\Silex\Application $app) {
        $this->app = $app;
        $this->debug = $_GET['debug'] == $this->app['config.model']->getByKey('debug.secret');

        $this->configureMailer();
    }

    /**
     *
     */
    public function newEmail() {
        return new EmailEntity;
    }

    /**
     *
     */
    public function send(EmailEntity $email) {
        $fromEmail = $this->app['config.model']->getByKey('email.robot.sender.email');
        $fromName = $this->app['config.model']->getByKey('email.robot.sender.name');

        $from = array();
        $from[$fromEmail] = $fromName;

        $message = \Swift_Message::newInstance()
            ->setSubject($email->subject)
            ->setFrom($from)
            ->setTo($email->to)
        ;

        // images
        foreach ($email->images as $key => $path) {
            $embeded = $message->embed(\Swift_Image::fromPath($path));
            $email->body = str_replace('{'.$key.'}', $embeded, $email->body);
        }

        // body
        if (strpos($email->body, '<html') == false) {
            $message->setBody($email->body);
        }
        else {
            $message->setBody($email->body, 'text/html');
        }

        if ($email->replyTo !== false) {
            $replyToEmail = $this->app['config.model']->getByKey('email.robot.reply-to');
            $message->setReplyTo(array($replyToEmail));
        }

        if ($this->debug) {
            var_dump('message', $email);
        }

        try {
            $ok = $this->app['mailer']->send($message);

            if ($ok != 1) {
                throw new \Exception('Email was not send');
            }
        }
        catch (\Exception $e) {
            if ($this->debug) {
                var_dump('Exception', $e->getMessage());
            }

            return false;
        }

        return true;
    }

    /**
     *
     */
    private function configureMailer() {
        $host = $this->app['config.model']->getByKey('email.robot.host');
        $this->app['mailer']->getTransport()->setHost($host);

        $port = $this->app['config.model']->getByKey('email.robot.port');
        $this->app['mailer']->getTransport()->setPort($port);

        $encryption = $this->app['config.model']->getByKey('email.robot.encryption');
        $this->app['mailer']->getTransport()->setEncryption($encryption);

        $authMode = $this->app['config.model']->getByKey('email.robot.auth_mode');
        $this->app['mailer']->getTransport()->setAuthMode($authMode);

        $username = $this->app['config.model']->getByKey('email.robot.username');
        $this->app['mailer']->getTransport()->setUsername($username);

        $password = $this->app['config.model']->getByKey('email.robot.password');
        $this->app['mailer']->getTransport()->setPassword($password);
    }
}