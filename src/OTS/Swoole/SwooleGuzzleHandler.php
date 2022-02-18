<?php

/**
 * This file is part of MangaToon server projects.
 */
namespace Aliyun\OTS\Swoole;

use \GuzzleHttp\RequestOptions;
use Hyperf\Pool\SimplePool\Connection;
use Swoole\Coroutine;
use \Swoole\Coroutine\Http\Client;
use \Psr\Http\Message\RequestInterface;
use \GuzzleHttp\Promise\FulfilledPromise;
use \GuzzleHttp\Psr7\Uri;
use \GuzzleHttp\Psr7\Response;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\ApplicationContext;

class SwooleGuzzleHandler {

  /**
   * @Inject
   * @var StdoutLoggerInterface
   */
  protected $logger;

  /**
   * Swoole 协程 Http 客户端
   *
   * @var \Swoole\Coroutine\Http\Client
   */
  private $client;
  /**
   * @var Connection
   */
  private $client_connection;

  /**
   * 配置选项
   *
   * @var array
   */
  private $settings = [];

  /**
   * @var RequestInterface
   */
  private $request = null;

  /**
   * @var Response
   */
  private $response = null;


  /**
   * Sends an HTTP request.
   *
   * @param RequestInterface $request Request to send.
   * @param array $options Request transfer options.
   *
   * @return PromiseInterface
   */
  public function __invoke(RequestInterface $request, array $options) {
    $this->logger = ApplicationContext::getContainer()->get(StdoutLoggerInterface::class);
    $this->response = null;
    $this->request = $request;
    $uri = $request->getUri();
    $this->logger->debug("about to request " . $uri);
    $isLocation = false;
    $count = 0;
    do {
      $pool = SwooleGuzzlePoolManager::manager()->pool($uri);
      $this->client_connection = $pool->get();
      $this->client = $this->client_connection->getConnection();
      $this->logger->debug("got connection from ots pool"
        . " cid=" . Coroutine::getCid() . ","
        . " current={$pool->getCurrentConnections()},"
        . " channel={$pool->getConnectionsInChannel()} " . $uri);

      // method
      if($isLocation) {
        $this->client->setMethod('GET');
      } else {
        $this->client->setMethod($request->getMethod());
      }

      // body
      if(!$isLocation) {
        $this->client->setData((string)$request->getBody());
      }

      // 其它处理
      $this->parseSSL($request, $options);
      $this->parseProxy($request, $options);
      $this->parseNetwork($request, $options);

      // headers
      $headers = [];
      foreach($request->getHeaders() as $name => $value) {
        $headers[$name] = implode(',', $value);
      }
      // 带有 Content-Length 时，少数奇葩服务器会无法顺利接收 post 数据
      if(isset($headers['Content-Length'])) {
        unset($headers['Content-Length']);
      }
      $this->client->setHeaders($headers);

      // 设置客户端参数
      if(!empty($this->settings)) {
        $this->client->set($this->settings);
      }

      // 发送
      $path = $uri->getPath();
      if('' === $path) {
        $path = '/';
      }
      $query = $uri->getQuery();
      if('' !== $query) {
        $path .= '?' . $query;
      }

      try {
        $this->client->execute($path);
        $response = $this->getResponse();
        $statusCode = $response->getStatusCode();
      } catch(\Throwable $e) {
        $this->client = null;
        if($this->client_connection != null) {
          $this->client_connection->release();
          $this->client_connection = null;
        }
        throw $e;
      }

      if((301 === $statusCode || 302 === $statusCode) && $options[RequestOptions::ALLOW_REDIRECTS] && ++$count <= $options[RequestOptions::ALLOW_REDIRECTS]['max']) {
        // 自己实现重定向
        $uri = new Uri($response->getHeaderLine('location'));
        $isLocation = true;
      } else {
        break;
      }

    } while(true);
    return new FulfilledPromise($response);
  }

  private function parseSSL(RequestInterface $request, array $options) {
    if(($verify = $options['verify'])) {
      $this->settings['ssl_verify_peer'] = true;
      if(is_string($verify)) {
        $this->settings['ssl_cafile'] = $verify;
      }
    } else {
      $this->settings['ssl_verify_peer'] = false;
    }

    $cert = isset($options['cert']) ? $options['cert'] : [];
    if(isset($cert[0])) {
      $this->settings['ssl_cert_file'] = $cert[0];
    } else if(isset($this->settings['ssl_cert_file'])) {
      unset($this->settings['ssl_cert_file']);
    }

    $key = isset($options['key']) ? $options['key'] : [];
    if(isset($key[0])) {
      $this->settings['ssl_key_file'] = $key[0];
    } else if(isset($this->settings['ssl_key_file'])) {
      unset($this->settings['ssl_key_file']);
    }
  }

  private function parseProxy(RequestInterface $request, array $options) {
    $proxy = isset($options['proxy']) ? $options['proxy'] : [];
    if(isset($proxy['no']) && \GuzzleHttp\is_host_in_noproxy($request->getUri()->getHost(), $proxy['no'])) {
      if(isset($this->settings['http_proxy_host'])) {
        unset($this->settings['http_proxy_host'], $this->settings['http_proxy_port'], $this->settings['http_proxy_user'], $this->settings['http_proxy_password']);
      }
      return;
    }
    $scheme = $request->getUri()->getScheme();
    $proxyUri = isset($proxy[$scheme]) ? $proxy[$scheme] : null;
    if(null === $proxyUri) {
      if(isset($this->settings['http_proxy_host'])) {
        unset($this->settings['http_proxy_host'], $this->settings['http_proxy_port'], $this->settings['http_proxy_user'], $this->settings['http_proxy_password']);
      }
      return;
    }
    $proxyUri = new Uri($proxyUri);
    $userinfo = explode(':', $proxyUri->getUserInfo());
    if(isset($userinfo[1])) {
      list($username, $password) = $userinfo;
    } else {
      $username = $userinfo[0];
      $password = null;
    }
    $this->settings['http_proxy_host'] = $proxyUri->getHost();
    $this->settings['http_proxy_port'] = $proxyUri->getPort();
    $this->settings['http_proxy_user'] = $username;
    $this->settings['http_proxy_password'] = $password;
  }

  private function parseNetwork(RequestInterface &$request, array $options) {
    // 用户名密码认证处理
    $auth = isset($options['auth']) ? $options['auth'] : [];
    if(isset($auth[1])) {
      list($username, $password) = $auth;
      $auth = base64_encode($username . ':' . $password);
      $request = $request->withAddedHeader('Authorization', 'Basic ' . $auth);
    }
    // 超时
    if(isset($options['timeout']) && $options['timeout'] > 0) {
      $this->settings['timeout'] = $options['timeout'];
    } else if(isset($this->settings['timeout'])) {
      $this->settings['timeout'] = -1;
    }
  }

  private function getResponse() {
    if(empty($this->response)) {
      $headers = isset($this->client->headers) ? $this->client->headers : [];
      if(isset($headers['set-cookie'])) {
        $headers['set-cookie'] = $this->client->set_cookie_headers;
      }
      if($this->client->statusCode <= 0) {
        $message = "error " . socket_strerror($this->client->errCode) . " when request url:"
          . "\n" . $this->request->getUri()
          . "\nstatus code is: " . $this->client->statusCode . " "
          . "error code is: " . $this->client->errCode . "(" . socket_strerror($this->client->errCode) . ")"
          . "\nbody length is: " . strlen($this->client->body)
          . "\n";
        $this->client->close();
        $this->client_connection->close();
        $this->client_connection->release();
        throw new \RuntimeException($message);
      }
      $this->response = new \GuzzleHttp\Psr7\Response($this->client->statusCode, $headers, $this->client->body);
      $this->client = null;
      $this->client_connection->release();
      $this->client_connection = null;

      if(!$this->logger){
        $this->logger = ApplicationContext::getContainer()->get(StdoutLoggerInterface::class);
      }
      $pool = SwooleGuzzlePoolManager::manager()->pool($this->request->getUri());
      $this->logger->debug("released connection to ots pool"
        . " cid=" . Coroutine::getCid() . ","
        . " current={$pool->getCurrentConnections()},"
        . " channel={$pool->getConnectionsInChannel()} " . $this->request->getUri());
    }
    return $this->response;
  }
}
