<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Carbon\Carbon;
use App\Console\Kernel;

class Ex_date extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ex_date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command deleted the ex products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

         $expiredProducts = Product::whereDate('ex_date', '<', $today)->get();
        foreach ($expiredProducts as $product) {
            $product->delete();
        }
    }
}
