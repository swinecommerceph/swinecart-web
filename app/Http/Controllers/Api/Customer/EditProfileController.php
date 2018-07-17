<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EditProfileController extends Controller
{
    public function __construct() 
    {
        $this->middleware('jwt:auth');
        $this->middleware('jwt:role:customer');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }

    public function me(Request $request)
    {
        $customer = $this->user->userable;

        if($customer) {
            return response()->json([
                'message' => 'Customer Me successful!',
                'data' => $customer
            ], 200);
        }
        else return response()->json([
            'error' => 'Customer does not exist!',
        ], 404);
    }

    public function getFarmAddresses(Request $request)
    {
        $farmAddresses = $customer->farmAddresses;

        if($farmAddresses) {
            return response()->json([
                'message' => 'Get Farm Addresses successful!',
                'data' => $farmAddresses
            ], 200);
        }
        else return response()->json([
            'error' => 'Farm Addresses does not exist!',
        ], 404);

    }

    private function getProvinces()
    {
        return collect([
            // Negros Island Rregion
            'Negros Occidental' => 'Negros Occidental',
            'Negros Oriental' => 'Negros Oriental',
            // Cordillera Administrative Region
            'Mountain Province' => 'Mountain Province',
            'Ifugao' => 'Ifugao',
            'Benguet' => 'Benguet',
            'Abra' => 'Abra',
            'Apayao' => 'Apayao',
            'Kalinga' => 'Kalinga',
            // Region I
            'La Union' => 'La Union',
            'Ilocos Norte' => 'Ilocos Norte',
            'Ilocos Sur' => 'Ilocos Sur',
            'Pangasinan' => 'Pangasinan',
            // Region II
            'Nueva Vizcaya' => 'Nueva Vizcaya',
            'Cagayan' => 'Cagayan',
            'Isabela' => 'Isabela',
            'Quirino' => 'Quirino',
            'Batanes' => 'Batanes',
            // Region III
            'Bataan' => 'Bataan',
            'Zambales' => 'Zambales',
            'Tarlac' => 'Tarlac',
            'Pampanga' => 'Pampanga',
            'Bulacan' => 'Bulacan',
            'Nueva Ecija' => 'Nueva Ecija',
            'Aurora' => 'Aurora',
            // Region IV-A
            'Rizal' => 'Rizal',
            'Cavite' => 'Cavite',
            'Laguna' => 'Laguna',
            'Batangas' => 'Batangas',
            'Quezon' => 'Quezon',
            // Region IV-B
            'Occidental Mindoro' => 'Occidental Mindoro',
            'Oriental Mindoro' => 'Oriental Mindoro',
            'Romblon' => 'Romblon',
            'Palawan' => 'Palawan',
            'Marinduque' => 'Marinduque',
            // Region V
            'Catanduanes' => 'Catanduanes',
            'Camarines Norte' => 'Camarines Norte',
            'Sorsogon' => 'Sorsogon',
            'Albay' => 'Albay',
            'Masbate' => 'Masbate',
            'Camarines Sur' => 'Camarines Sur',
            // Region VI
            'Capiz' => 'Capiz',
            'Aklan' => 'Aklan',
            'Antique' => 'Antique',
            'Iloilo' => 'Iloilo',
            'Guimaras' => 'Guimaras',
            // Region VII
            'Cebu' => 'Cebu',
            'Bohol' => 'Bohol',
            'Siquijor' => 'Siquijor',
            // Region VIII
            'Southern Leyte' => 'Southern Leyte',
            'Eastern Samar' => 'Eastern Samar',
            'Northern Samar' => 'Northern Samar',
            'Western Samar' => 'Western Samar',
            'Leyte' => 'Leyte',
            'Biliran' => 'Biliran',
            // Region IX
            'Zamboanga Sibugay' => 'Zamboanga Sibugay',
            'Zamboanga del Norte' => 'Zamboanga del Norte',
            'Zamboanga del Sur' => 'Zamboanga del Sur',
            // Region X
            'Misamis Occidental' => 'Misamis Occidental',
            'Bukidnon' => 'Bukidnon',
            'Lanao del Norte' => 'Lanao del Norte',
            'Misamis Oriental' => 'Misamis Oriental',
            'Camiguin' => 'Camiguin',
            // Region XI
            'Davao Oriental' => 'Davao Oriental',
            'Compostela Valley' => 'Compostela Valley',
            'Davao del Sur' => 'Davao del Sur',
            'Davao Occidental' => 'Davao Occidental',
            'Davao del Norte' => 'Davao del Norte',
            // Region XII
            'South Cotabato' => 'South Cotabato',
            'Sultan Kudarat' => 'Sultan Kudarat',
            'North Cotabato' => 'North Cotabato',
            'Sarangani' => 'Sarangani',
            // Region XIII
            'Agusan del Norte' => 'Agusan del Norte',
            'Agusan del Sur' => 'Agusan del Sur',
            'Surigao del Sur' => 'Surigao del Sur',
            'Surigao del Norte' => 'Surigao del Norte',
            'Dinagat Islands' => 'Dinagat Islands',
            // ARMM
            'Tawi-tawi' => 'Tawi-tawi',
            'Basilan' => 'Basilan',
            'Sulu' => 'Sulu',
            'Maguindanao' => 'Maguindanao',
            'Lanao del Sur' => 'Lanao del Sur'
        ])->sort();
    }
}
