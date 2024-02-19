<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ShowTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'show-table {table?} {recordsNum?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show columns and, if available, the last x records of a specific table or list all tables if no table is provided.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->argument('table');
        $recordsNum = $this->argument('recordsNum');

        if ($table) {
            // If a specific table is provided, show its columns
            if (Schema::hasTable($table)) {
                $columns = Schema::getColumnListing($table);
                $this->info(implode(' | ', $columns));

                // Fetch the last 10 records from the table
                $records = DB::table($table)->orderBy('created_at', 'desc')->take($recordsNum ?? 10)->get();

                if ($records->count() > 0) {
                    // Display the last 10 records
                    $this->info($this->formatRecords($records, $columns));
                } else {
                    $this->info("No records found in table '$table'.");
                }
            } else {
                $this->error("Table '$table' does not exist.");
            }
        } else {
            // If no specific table is provided, list all tables
            $tables = DB::select('SHOW TABLES');
        
            if (count($tables) > 0) {
                $tableNames = array_map('current', $tables);
                $this->info(implode(' | ', $tableNames));
            } else {
                $this->info("No tables found in the database.");
            }
        }
    }

    /**
     * Format records for display.
     *
     * @param \Illuminate\Support\Collection $records
     * @param array $columns
     * @return string
     */
    protected function formatRecords($records, $columns)
    {
        $formattedRecords = collect($records)->map(function ($record) use ($columns) {
            return '[' . implode('] [', array_map(function ($column) use ($record) {
                return $record->$column;
            }, $columns)) . ']';
        });

        return $formattedRecords->implode(PHP_EOL);
    }
}