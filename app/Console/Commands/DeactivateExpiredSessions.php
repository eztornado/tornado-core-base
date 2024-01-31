<?php

namespace App\Console\Commands;

use App\Models\Core\Session;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\RedisTrait;
class DeactivateExpiredSessions extends Command
{
    use RedisTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deactivate-expired-sessions';

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
        $sesiones_caducadas = DB::select('SELECT id, TIMESTAMPDIFF(MINUTE,expires_at, CURRENT_TIMESTAMP()) tiempo  FROM sessions
            HAVING tiempo > 1');

        foreach($sesiones_caducadas as $s)
        {
            $sesion = Session::find($s->id);
            $sesion->is_alive = 0;
            $sesion->save();
        }
    }

}
