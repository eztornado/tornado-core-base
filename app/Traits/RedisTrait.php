<?php
namespace App\Traits;

use Illuminate\Redis\Connections\PhpRedisConnection;
use Illuminate\Support\Facades\Redis;

trait RedisTrait
{
    public function openRedisConnection() : PhpRedisConnection {
        $redis = Redis::connection();
        return $redis;
    }

}
