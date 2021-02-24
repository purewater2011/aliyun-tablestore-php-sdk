<?php

/**
 * This file is part of MangaToon server projects.
 */
namespace Aliyun\OTS\Swoole;

use Psr\Http\Message\UriInterface;
use Hyperf\Pool\SimplePool\Pool;
use Mangatoon\Hyperf\Extend\Compatibility\Compatibility;
use Swoole\Coroutine\Http\Client;


class SwooleGuzzlePoolManager {

  private static $instance;
  private $pools = [];

  public static function manager() {
    if(empty(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private function getUriPort(UriInterface $uri): int {
    $port = $uri->getPort();
    if(null === $port) {
      if('https' === $uri->getScheme()) {
        $port = 443;
      } else {
        $port = 80;
      }
    }
    return $port;
  }


  /**
   * @return Pool
   */
  public function pool(UriInterface $uri) {
    $key = $uri->getHost() . ':' . $this->getUriPort($uri);
    if(empty($this->pools[$key])) {
//      SwooleLogger::debug('about to create ots guzzle pool for ' . $key);
      $this->pools[$key] = new Pool(Compatibility::container(), function() use ($uri, $key) {
//        SwooleLogger::debug('about to create ots guzzle connection for ' . $key);
        return new Client($uri->getHost(), $this->getUriPort($uri), 'https' === $uri->getScheme());
      }, [
        'max_connections' => 200,
        'connect_timeout' => 1,
        'wait_timeout' => 0.01,
        'max_idle_time' => 60,
      ]);
    }
    return $this->pools[$key];
  }

  /**
   * 关闭连接池
   */
  public function close() {
//    SwooleLogger::debug("about to close ots pool manager with " . count($this->pools) . " pools");
    self::$instance = null;
    foreach($this->pools as $key => $pool) {
      /** @var Pool $pool */
      $pool->flush();
    }
    $this->pools = [];
  }


  public function info(): ?\stdClass {
    $result = new \stdClass();
    $result->pools_count = count($this->pools);
    $result->connections_count = 0;
    $result->pools = [];
    foreach($this->pools as $key => $pool) {
      /** @var Pool $pool */
      $result->pools[] = [
        'key' => $key,
        'info' => [
          'current_connections' => $pool->getCurrentConnections(),
          'waiting_connections' => $pool->getConnectionsInChannel(),
        ],
      ];
    }
    return $result;
  }

  public function __destruct() {
    $this->close();
  }

}