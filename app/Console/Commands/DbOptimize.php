<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DbOptimize extends Command
{
    protected $signature = 'db:optimize';

    protected $description = 'Optimize all database tables';

    public function handle()
    {
        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            $tableName = reset($table);

            $this->line("Optimizing table: $tableName");

            DB::statement("OPTIMIZE TABLE $tableName");
        }

        $this->info('All tables have been optimized successfully.');
    }
}
