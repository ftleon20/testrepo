<?php

namespace App\Console\Commands\Products;

use Illuminate\Console\Command;
use App\Products\Trilion;
use App\Cron;
use App\Product;

class TrilionRetrieveTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trailion_isin4d:retrieve_tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to run retrieve tickets method(s)';

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
     * @return mixed
     */
    public function handle()
    {
        Cron::calc_turnover_winover(Product::$provider_wallet['trilion_isin4d']);
        Trilion::get_bet_detail();
    }
}
