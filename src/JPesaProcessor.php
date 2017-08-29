<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 2017-07-31
 * Time: 12:47 PM
 */

namespace FannyPack\JPesa;


use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Log;

class JPesaProcessor
{
    /**
     * @var Client
     */
    protected $client;

    CONST JPESA_ENDPOINT = "https://secure.jpesa.com/api.php";

    CONST JPESA_COMMAND = "command=jpesa";

    /**
     * JPesa action
     */
    protected $action;

    /**
     * JPesa username
     */
    protected $username;

    /**
     * JPesa password
     */
    protected $password;

    /**
     * @var Application
     */
    protected $app;

    /**
     * JPesaProcessor constructor.
     * @param Client $client
     * @param Application $app
     */
    public function __construct(Client $client, Application $app)
    {
        $this->client = $client;
        $this->app = $app;
        $this->setConfigurations();
    }

    protected function setConfigurations()
    {
        $this->username = $this->app['config']['jpesa.username'];
        $this->password = $this->app['config']['jpesa.password'];

        if (!$this->username)
            throw new \InvalidArgumentException("JPesa Username not specified");

        if (!$this->password)
            throw new \InvalidArgumentException("JPesa Password not specified");
    }

    /**
     * @param $from
     * @param $amount
     * @return array
     */
    public function deposit($from, $amount)
    {
        $data = [];
        $this->action = "deposit";
        $url = self::JPESA_ENDPOINT . "?" . self::JPESA_COMMAND . "&action=" . $this->action . "&username=" . $this->username . "&password=" . $this->password . "&IS_GET=3&number=" . $from . "&amount=" . $amount;

        try{
            $response = $this->client->get($url);
            $content = $response->getBody()->getContents();
            list($response_type, $message) = explode("]", $content);
            list(,$response_type) = explode("[", trim($response_type));
            $message = trim($message);
            switch (strtolower($response_type)){
                case 'success':
                    list($message, $transaction_id) = explode("|", $message);
                    $data["response"] = [
                        "success" => true,
                        "transaction_id" => $transaction_id,
                        "message" => $message
                    ];
                    break;
                case 'error':
                    $data["response"] = [
                        "success" => false,
                        "message" => $message
                    ];
                    break;
                default:
                    $data = ["response" => null];
                    break;
            }
            return $data;
        }catch (\Exception $e){
            return ["response" => null];
        }

    }

    /**
     * @param $transactionId
     * @return array
     */
    public function info($transactionId)
    {
        $this->action = "info";
        $url = self::JPESA_ENDPOINT . "?" . self::JPESA_COMMAND . "&action=" . $this->action . "&username=" . $this->username . "&password=" . $this->password . "&IS_GET=3&tid=" .$transactionId;
        try{
            $response = $this->client->get($url);
            $content = $response->getBody()->getContents();
            list($response_type, $message) = explode("]", $content);
            list(,$response_type) = explode("[", trim($response_type));
            $message = trim($message);
            switch (strtolower($response_type)){
                case 'success':
                    $data["response"] = [
                        "success" => true,
                        "message" => json_decode($message)
                    ];
                    break;
                case 'error':
                    $data["response"] = [
                        "success" => false,
                        "message" => $message
                    ];
                    break;
                default:
                    $data = ["response" => null];
                    break;
            }
            return $data;
        }catch (\Exception $e){
            return ["response" => null];
        }
    }

    /**
     * @param $to
     * @param $amount
     */
    public function withdraw($to, $amount)
    {
        # todo
    }
}