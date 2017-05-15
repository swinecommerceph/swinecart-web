<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Console\Command;
use App\Mail\SwineCartBreederAccreditationExpiration;

use DB;
use Carbon\Carbon;

class BreederAccreditationNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'breeder:accreditationexpire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifies breeders with expiring accreditation through email';

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
     * Handles sending of emails to breeders with expiring accreditation
     * @return mixed
     */
    public function handle()
    {
        $expirationAlert1 = Carbon::now()->subYear(1)->subMonth(5)->toDateString();
        $expirationAlert2 = Carbon::now()->subYear(1)->subMonth(1)->toDateString();
        $expirationAlert3 = Carbon::now()->subYear(1)->toDateString();
        $expirationAlert4 = Carbon::now()->toDateString();

        $breedersGroup1 = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_id','=', 2)
                            ->join('breeder_user','breeder_user.id', '=', 'users.userable_id')
                            ->select('breeder_user.id as breeder_id', 'users.name as username' ,'email', 'latest_accreditation', 'status_instance')
                            ->where('status_instance','=','active')
                            ->where('latest_accreditation','=',$expirationAlert1)
                            ->get();

        $breedersGroup2 = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_id','=', 2)
                            ->join('breeder_user','breeder_user.id', '=', 'users.userable_id')
                            ->select('breeder_user.id as breeder_id', 'users.name as username', 'email', 'latest_accreditation', 'status_instance')
                            ->where('status_instance','=','active')
                            ->where('latest_accreditation','=',$expirationAlert2)
                            ->get();

        $breedersGroup3 = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_id','=', 2)
                            ->join('breeder_user','breeder_user.id', '=', 'users.userable_id')
                            ->select('breeder_user.id as breeder_id', 'users.name as username', 'email', 'latest_accreditation', 'status_instance')
                            ->where('status_instance','=','active')
                            ->where('latest_accreditation','=',$expirationAlert3)
                            ->get();

        $breedersGroup4 = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_id','=', 2)
                            ->join('breeder_user','breeder_user.id', '=', 'users.userable_id')
                            ->select('users.id as user_id', 'breeder_user.id as breeder_id', 'users.name as username', 'email', 'latest_accreditation', 'status_instance', 'notification_date')
                            ->where('status_instance','=','active')
                            ->where('notification_date','=',$expirationAlert4)
                            ->get();

        foreach ($breedersGroup1 as $breederGr1) {
            $type = 0;
            $expiration = Carbon::parse($breederGr1->latest_accreditation)->addYear()->format('l jS \\of F Y h:i:s A');
            Mail::to($breederGr1->email)
                ->queue(new SwineCartBreederAccreditationExpiration($type, $breederGr1->username, $breederGr1->email, $expiration));
        }
        foreach ($breedersGroup2 as $breederGr2) {
            $type = 1;
            $expiration = Carbon::parse($breederGr2->latest_accreditation)->addYear()->format('l jS \\of F Y h:i:s A');
            Mail::to($breederGr2->email)
                ->queue(new SwineCartBreederAccreditationExpiration($type, $breederGr2->username, $breederGr2->email, $expiration));
        }
        foreach ($breedersGroup3 as $breederGr3) {
            $type = 2;
            $expiration = Carbon::now()->format('l jS \\of F Y h:i:s A');
            $user = User::find($breederGr3->user_id);
            $user->blocked_at = Carbon::now();
            $user->save();
            Mail::to($breederGr3->email)
                ->queue(new SwineCartBreederAccreditationExpiration($type, $breederGr3->username, $breederGr3->email, $expiration));
        }
        foreach ($breedersGroup4 as $breederGr4) {
            $type = 0;
            $expiration = Carbon::parse($breederGr4->latest_accreditation)->addYear()->format('l jS \\of F Y h:i:s A');
            Mail::to($breederGr4->email)
                ->queue(new SwineCartBreederAccreditationExpiration($type, $breederGr4->username, $breederGr4->email, $expiration));
        }

    }
}
