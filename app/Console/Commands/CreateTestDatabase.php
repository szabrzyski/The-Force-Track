<?php

namespace App\Console\Commands;

use App\Models\User;
use Exception;
use Illuminate\Console\Command;

class CreateTestDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testDatabase:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating test database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        try {
            User::factory()->hasIssues(3)->hasComments(10)->count(50)->create();
            $this->info('Test database created.');
        } catch (Exception $exception) {
            $this->error('Test database creation failed.');
            $this->warn($exception->getMessage());
        }
    }
}
