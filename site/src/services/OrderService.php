<?php
namespace app\helpers;

class OrderEntity {
    public $source;
    public $name;
    public $email;
    public $phone;
}

class OrderService {
    //
    private $debug = false;

    /**
     *
     * @param \Silex\Application $app
     */
    public function __construct(\Silex\Application $app) {
        $this->app = $app;
        $this->debug = $_REQUEST['debug'] == $this->app['config.model']->getByKey('debug.secret');
    }

    /**
     *
     */
    public function newOrder() {
        return new OrderEntity;
    }

    //
    public function add(OrderEntity $order) {
        $filename = sprintf('%s/web/%s',
            ROOT_DIR,
            $this->app['config.model']->getByKey('reg-form.file')
        );

        $order->phone = str_replace(' ', '', $order->phone);

        $data = array(
            str_pad(date('Y-m-d H:i:s'), 35),
            str_pad($order->source, 15),
            str_pad($order->name, 30),
            str_pad($order->email, 30),
            str_pad($order->phone, 20),
        );

        $data = join(' ', $data);
        $data = trim($data);

        $f = fopen($filename, 'aw');
        fwrite($f, $data."\n");
        fclose($f);

        $this->addPostAction($order);
    }

    // 
    public function addPostAction(OrderEntity $order) {
        if ($this->debug) {
            var_dump('addPostAction', $order);
        }

        $ok = $this->notifyCustomerByEmail($order);
        if ($this->debug) {
            var_dump('notifyCustomerByEmail', $ok);
        }

        $ok = $this->notifyCustomerBySms($order);
        if ($this->debug) {
            var_dump('notifyCustomerBySms', $ok);
        }

        $ok = $this->notifySupportByEmail($order);
        if ($this->debug) {
            var_dump('notifySupportByEmail', $ok);
        }

        $ok = $this->notifySupportBySms($order);
        if ($this->debug) {
            var_dump('notifySupportBySms', $ok);
        }

        $this->addToTrello($order);
    }

    // 
    private function notifyCustomerBySms(OrderEntity $order) {
        $message = $this->app['sms.service']->newMessage();

        $message->to = $order->phone;
        $message->text = "Привіт! Дякуємо за реєстрацію! Скоро вам зателефонує наш представник.";

        return $this->app['sms.service']->send($message);
    }

    // 
    private function notifyCustomerByEmail(OrderEntity $order) {
        $email = $this->app['email.service']->newEmail();

        $email->subject = 'Привіт!';

        $email->to = array();
        $email->to[$order->email] = $order->name;

        $email->body = $this->app['twig']->render('email/on-registration.html.twig', array(
            'name' => $order->name,
        ));

        $email->images = array(
            'image_logo_small' => ROOT_DIR.'/web/img/logo-small.png',
        );

        // foreach ($email->images as $key => $value) {
        //     $email->body = str_replace('{'.$key.'}', str_replace(ROOT_DIR.'/web','',$value), $email->body);
        // }
        // print($email->body);die;

        return $this->app['email.service']->send($email);
    }

    // 
    private function notifySupportByEmail(OrderEntity $order) {
        $email = $this->app['email.service']->newEmail();

        $email->subject = 'NEW ORDER';
        $email->replyTo = false;

        $toEmails = $this->app['config.model']->getByKey('orders.notify.to-email');
        $toEmails = explode(';', $toEmails);

        $email->to = $toEmails;

        $email->body = sprintf(
            "source: %s\nname: %s\nphone: %s",
            $order->source,
            $order->name,
            $order->phone
        );

        return $this->app['email.service']->send($email);
    }

    // 
    private function notifySupportBySms(OrderEntity $order) {
        $mobileNumbers = $this->app['config.model']->getByKey('orders.notify.to-mobile');
        $mobileNumbers = explode(';', $mobileNumbers);

        if ($this->debug) {
            var_dump('notifySupportBySms.mobileNumbers', $mobileNumbers);
        }

        if (!count($mobileNumbers)) {
            return false;
        }

        $ok = true;

        foreach ($mobileNumbers as $mobileNumber) {
            $message = $this->app['sms.service']->newMessage();
            
            $message->to = $mobileNumber;
            $message->text = sprintf(
                "NEW ORDER! %s\n%s %s",
                $order->source,
                $order->name,
                $order->phone
            );

            $ok = $ok && $this->app['sms.service']->send($message);

            if ($this->debug) {
                var_dump('notifySupportBySms.mobileNumber.send', $mobileNumber, $ok);
            }
        }

        return $ok;
    }

    //
    public function addToTrello(OrderEntity $order) {
        $card = $this->app['trello.service']->newCard();
        $card->title = $order->name . ' ' . $order->phone;
        $card->name = $order->name;
        $card->phone = $order->phone;
        $card->email = $order->email;
        $card->boardId = $this->app['config.model']->getByKey('trello.board.orders.id');
        $card->listId = $this->app['config.model']->getByKey('trello.board.orders.list.id');

        $ok = $this->app['trello.service']->addCard($card);

        return $ok;
    }
}
