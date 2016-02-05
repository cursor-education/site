<?php
namespace app\helpers;

class SmsEntity {
    public $to;
    public $text;
}

class SmsService {

    /**
     *
     * @param \Silex\Application $app
     */
    public function __construct(\Silex\Application $app) {
        $this->app = $app;
    }

    /**
     *
     */
    public function newMessage() {
        return new SmsEntity;
    }

    /**
     *
     */
    private function formatMobileNumber($number) {
        // 067...
        if (preg_match('/^0[1-9]{2}/', $number)) {
            return '+38'.$number;
        }

        // 8067...
        if (preg_match('/^80[1-9]{2}/', $number)) {
            return '+3'.$number;
        }

        return $number;
    }

    /**
     *
     */
    public function send(SmsEntity $message) {
        $client = new \SoapClient('http://turbosms.in.ua/api/wsdl.html');

        $auth = array( 
            'login' => $this->app['config.model']->getByKey('sms.login'),
            'password' => $this->app['config.model']->getByKey('sms.password'),
        );
        $result = $client->Auth($auth);

        $sms = array(
            'sender' => 'CURSOR',
            'destination' => $this->formatMobileNumber($message->to),
            'text' => $message->text,
        );

        $result = $client->SendSMS($sms);

        try {
            if (count($result->SendSMSResult->ResultArray) != 2) {
                throw new \Exception('The sms response is not valid');
            }
        }
        catch (\Exception $e) {
            return false;
        }

        return true;
    }
}