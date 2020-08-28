<?php


use PHPUnit\Framework\TestCase;
use Saulmoralespa\Credibanco\Client;

class CredibancoTest extends TestCase
{
    public $credibanco;

    protected function setUp()
    {
        $dotenv = Dotenv\Dotenv::createMutable(__DIR__ . '/../');
        $dotenv->load();

        $user = $_ENV['USER'];
        $password = $_ENV['PASSWORD'];

        $this->credibanco = new Client($user, $password);
        $this->credibanco->sandboxMode(true);

    }

    public function testRegister()
    {
        $params = [
            'amount' => '100000',
            'currency' => '170', //170 COP, 840 USD
            'orderNumber' => time(),
            'returnUrl' => 'https://server/applicaton_context/success.html',
            'failUrl' => 'https://server/applicaton_context/fail.html'
        ];
        $response = $this->credibanco->register($params);
        var_dump($response);
        $this->assertArrayHasKey('orderId', $response);
    }

    public function testGetOrderStatusExtended()
    {
        $params = [
            'orderNumber' => 13454,
            'orderId' => '7689c2c5-59bb-7707-8cb0-d0a100b10081'
        ];

        $response = $this->credibanco->getOrderStatusExtended($params);
        var_dump($response);
        $this->assertArrayHasKey('paymentAmountInfo', $response);
    }
}