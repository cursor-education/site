<?php
namespace app\services;

class TrelloCardEntity {
    public $title;
    public $name;
    public $phone;
    public $email;
    public $position = 'top';
    public $labels;
    public $boardId;
}

class TrelloService {
    //
    private $debug = false;

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
    public function newCard() {
        return new TrelloCardEntity;
    }

    /**
     *
     */
    private function getClient() {
        $apiKey = $this->app['config.model']->getByKey('trello.api.key');
        $apiToken = $this->app['config.model']->getByKey('trello.api.token');

        $client = new \Trello\Client($apiKey);
        $client->setAccessToken($apiToken);

        return $client;
    }

    /**
     *
     */
    public function findCard(TrelloCardEntity $cardEntity) {
        $client = $this->getClient();

        $response = $client->get(\Trello\Client::MODEL_SEARCH, array(
            'query' => sprintf('is:open name:%s', $cardEntity->phone),
            'idBoards' => $cardEntity->boardId,
            'modelTypes' => 'cards',
            'cards_limit' => '1',
        ));

        if (isset($response['cards']) && count($response['cards']) > 0) {
            $card = $response['cards'][0];
            $cardEntity = $client->getCard($card['id']);

            return $cardEntity;
        }

        return false;
    }

    /**
     *
     */
    public function addCard(TrelloCardEntity $cardEntity) {
        $client = $this->getClient();

        if ($card = $this->findCard($cardEntity)) {
            if (false === strpos($card->desc, $cardEntity->email)) {
                $card->desc .= "\n" . sprintf('%s: email: %s', date('Y-m-d H:i:s'), $cardEntity->email);
            }

            if (false === strpos($card->name, $cardEntity->name)
             && false === strpos($card->desc, $cardEntity->name)
            ) {
                $card->desc .= "\n" . sprintf('%s: name: %s', date('Y-m-d H:i:s'), $cardEntity->name);
            }

            try {
                // move cart to top
                $card->pos = 'top';
                $card = $card->save();

                // add comment
                $url = sprintf('cards/%s/actions/comments', $card->id);
                $client->post($url, [
                    'text' => 'ping from customer'
                ]);

                return true;
            }
            catch (Exception $e) {
                //
            }
        }
        else {
            $board = $client->getBoard($cardEntity->boardId);

            $card = new \Trello\Model\Card($client);
            $card->name = $cardEntity->title;
            $card->desc = sprintf('email: %s', $cardEntity->email);
            $card->pos = $cardEntity->position;
            $card->labels = $cardEntity->labels;
            $card->idList = $cardEntity->listId;

            try {
                $card = $card->save();

                return true;
            }
            catch (Exception $e) {
                //
            }
        }

        return false;
    }
}
