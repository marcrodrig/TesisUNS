<?php

namespace Database\Seeders;

use JeroenZwart\CsvSeeder\CsvSeeder;

class DataSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = '/database/seeds/csvs/Dataset.csv';
        $this->tablename = 'data';
        $this->delimiter=',';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Recommended when importing larger CSVs
        \DB::disableQueryLog();
        parent::run();
    }
}
