<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use App\Mail\SwineCartProductNotification;
use App\Mail\SwineCartErrorMail;

use DB;
use Carbon\Carbon;

class ProductNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify breeders and users about product reservations';

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
     * Handles the sending of email for product reservations
     * @return mixed
     */
    public function handle()
    {
        $dateNeeded = DB::table('swine_cart_items')
                    ->join('transaction_logs', 'transaction_logs.product_id','=','swine_cart_items.product_id')
                    ->whereMonth('date_needed', Carbon::now()->month)
                    ->whereYear('date_needed',  Carbon::now()->year)
                    ->whereDay('date_needed', Carbon::now()->day-1)
                    ->get();

        $reservation = DB::table('product_reservations')
                    ->whereMonth('expiration_date', Carbon::now()->month)
                    ->whereYear('expiration_date',  Carbon::now()->year)
                    ->whereDay('expiration_date', Carbon::now()->day-1)
                    ->get();
        if(count($dateNeeded) > 0){
            foreach ($dateNeeded as $date) {
                $type = 0;
                $product = Product::where('id', $date->product_id)->first();
                $user = User::where('userable_type', 'App\Models\Breeder')->where('userable_id', $date->breeder_id)->first();
                if($user!=null){
                    if($product==null){
                        $errorcode = 0;
                        Mail::to($user->email)
                            ->queue(new SwineCartErrorMail($errorcode));
                    }else{
                        $date->date_needed = Carbon::parse($date->date_needed)->format('l jS \\of F Y h:i:s A');
                        Mail::to($user->email)
                            ->queue(new SwineCartProductNotification($type, $user, $product->name, $date));
                    }
                }else{
                    continue;
                }
            }
        }
        if(count($reservation)>0){
            foreach ($reservation as $date) {
                $type = 1;
                $product = Product::where('id', $date->product_id)->first();
                $user = User::where('userable_type', 'App\Models\Customer')->where('userable_id', $date->customer_id)->first();
                if($user!=null){
                    if($product==null){
                        $errorcode = 0;
                        Mail::to($user->email)
                            ->queue(new SwineCartErrorMail($errorcode));
                    }else{
                        $date->expiration_date = Carbon::parse($date->expiration_date)->format('l jS \\of F Y h:i:s A');
                        Mail::to($user->email)
                            ->queue(new SwineCartProductNotification($type, $user, $product->name, $date));
                    }
                }else{
                    continue;
                }

            }
        }

    }
}
