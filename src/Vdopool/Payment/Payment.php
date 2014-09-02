<?php
namespace Vdopool\Payment;

use Guzzle\Http\Exception\ClientErrorResponseException;
use Illuminate\Foundation\Application;

class Payment {

  protected $app;

  public function __construct(Application $app)
  {
    $this->app = $app;
  }

  /**
   * 充值
   *
   * @param array $data 交易需要的数据
   *
   * @return string
   * @author Me
   **/
  public function trade($data)
  {
    $sign = Helper::hmacSign('POST', $data, $this->app['config']->get('payment::secret_key'));
    $data['signature'] = $sign;

    $client = new \GuzzleHttp\Client;

    $request = $client->createRequest('POST', $this->app['config']->get('payment::trade_url'));
    $postBody = $request->getBody();

    // $postBody is an instance of GuzzleHttp\Post\PostBodyInterface
    foreach ($data as $k => $v) {
      $postBody->setField($k, $v);
    }
    // Send the POST request
    $response = $client->send($request);
    return $response->getBody();
  }

  /**
   * 查询订单
   *
   * @param array $data 查询需要的数据
   *
   * @return string
   * @author Me
   **/
  public function query($data)
  {
    $sign = Helper::hmacSign('GET', $data, $this->app['config']->get('payment::secret_key'));
    $data['signature'] = $sign;

    $url = $this->app['config']->get('payment::query_url') . '?' . Helper::createLinkstring($data);

    $client = new \GuzzleHttp\Client;

    $response = $client->get($url);

    // Send the GET request
    return $response->getBody();
  }
}

