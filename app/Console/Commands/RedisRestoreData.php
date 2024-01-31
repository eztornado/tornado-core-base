<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\RedisTrait;
class RedisRestoreData extends Command
{
    use RedisTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:redis-restore-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $files = Storage::disk('local')->files('cache/');
        $redis = $this->openRedisConnection();
        //reiniciamos redis
        $redis->command('FLUSHALL');
        $i = 1;
        $tam = sizeof($files);
        foreach($files as $f){
            $fichero = Storage::disk('local')->get($f);
            $fichero = json_decode($fichero);
            echo "[".$i."/".$tam."]Insertando en redis: ".$fichero->key."\n";
            $redis->set($fichero->key, json_encode($fichero->content));
            $i++;
        }
    }

}
