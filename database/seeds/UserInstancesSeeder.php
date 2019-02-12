<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserInstancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // For Customers
        $companyNames = [
            'John and Piolo Farms',
            'McJolly Farms',
            'Low Pigs Co.',
            'PICC',
            'PCAARRD Farms',
            'Great Pig Inc.',
            'Sharoen Fokfun',
            'Wellize Farms',
            'Great Pigs Dutchman',
            'General Pigs Co.',
            'Pork n Pork',
            'Slow Roast Piggery',
            'Master Piggery',
            'Pigz em Piggery',
            'PorKed',
            'Pig Masters',
            'Piggery1',
            'Piggery2',
            'PigPigPigging',
            'PigCARD',
            'PigPigPag',
            'PigPagPig'
        ];
        // For Administrator
        factory(App\Models\User::class, 2)->create()->each(function($user){
            $user->assignRole('admin');
            // Create Administrator Profile
            $administrator = factory(App\Models\Admin::class)->create();
            $administrator->users()->save($user);
        });

    	// For Customers
        factory(App\Models\User::class, 20)->create()->each(function($user){
            $faker = Faker\Factory::create();
            $user->assignRole('customer');
            $user->update_profile = 0;
            $user->email_verified = 1;

            $user->created_at = $faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now');
            $user->approved_at = $faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now');

            // Create Customer Profile
            $customer = factory(App\Models\Customer::class)->create();
            // Create Farm Address
            $farm = factory(App\Models\FarmAddress::class)->create();

            $customer->users()->save($user);
            $customer->farmAddresses()->save($farm);
        });

        // For Breeders
        factory(App\Models\User::class, 2)->create()->each(function($user)use($companyNames){
            $faker = Faker\Factory::create();
            $user->assignRole('breeder');
            $user->update_profile = 0;
            $user->email_verified = 1;
            $user->created_at = $faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now');
            $user->approved_at = $faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now');

            // Create Breeder Profile
            $breeder = factory(App\Models\Breeder::class)->create();
            // Create Farm Address. Override accreditation default values
            for ($i = 0; $i < 10; $i++) {
                $farm = factory(App\Models\FarmAddress::class)->create([
                    'accreditation_no' => random_int(500,1000),
                    'accreditation_status' => 'active',
                    'accreditation_date' => \Carbon\Carbon::now()->subYear(),
                    'accreditation_expiry' => \Carbon\Carbon::now()->addYear()
                ]);
                $breeder->farmAddresses()->save($farm);
            }
            
            $breeder->users()->save($user);
            // Change name if Breeder
            $user->name = $companyNames[$breeder->id-1];
            $user->save();

            // Create products as well
            // Initialization
            $rand = random_int(10,13);
            $types = ['sow', 'gilt', 'boar', 'semen']; // 4
            $breeds = ['largewhite', 'landrace', 'duroc', 'pietrain', 'landrace+duroc', 'largewhite+duroc', 'chesterwhite']; // 7
            for ($i = 0; $i < 50; $i++) {
                $randType = $types[random_int(0,3)];
                $randBreed = $breeds[random_int(0,6)];
                $product = new App\Models\Product;
                $image = new App\Models\Image;
                $image2 = new App\Models\Image;
                $video = new App\Models\Video;

                // Sow Duroc / Gilt Duroc Two images
                if(($randType == 'sow' || $randType == 'gilt') && $randBreed == 'duroc'){
                    $image->name = $randType.'_'.$randBreed.'1.jpg';
                    $image2->name = $randType.'_'.$randBreed.'2.jpg';
                    $image2->save();
                }

                // Boar/Semen Crossbreed
                elseif (($randType == 'boar' || $randType == 'semen') && $randBreed == 'landrace+duroc') {
                    $image->name = $randType.'_cb_landraceDuroc1.jpg';
                }

                // Sow / Gilt Crossbreed
                elseif (($randType == 'sow' || $randType == 'gilt') && $randBreed == 'largewhite+duroc') {
                    $image->name = $randType.'_cb_largewhiteDuroc1.jpg';
                }

                // Others
                elseif ($randBreed == 'chesterwhite') {
                    $image->name = $randType.'_chesterwhite1.jpg';
                }
                elseif (($randType == 'boar' && $randBreed == 'largewhite+duroc') ||
                        ($randType == 'sow' && $randBreed == 'landrace+duroc') ||
                        ($randType == 'gilt' && $randBreed == 'landrace+duroc') ||
                        ($randType == 'semen' && $randBreed == 'largewhite+duroc')) {
                            $i--;
                            continue;
                        }

                // General
                else $image->name = $randType.'_'.$randBreed.'1.jpg';

                $image->save();

                $video->name = 'sample_video.mp4';

                $product->farm_from_id = $farm->id;
                $product->primary_img_id = $image->id;
                $product->name = random_int(1000,3000);
                $product->type = $randType;
                $product->birthdate = date('Y-m-d', time() - (15 * (7 * 24 * 60 * 60)));
                $product->breed_id = App\Models\Breed::firstOrCreate(['name' => $randBreed])->id;
                $product->price = random_int(35000,100000)/1.0;
                $product->quantity = ($randType == 'semen') ? -1 : 1;
                $product->adg = random_int(760,1450);
                $product->fcr = random_int(10,30)/10.0;
                $product->backfat_thickness = random_int(90,200)/10.0;
                $product->other_details = '';
                $product->status = 'displayed';
                
                $breeder->products()->save($product);

                // Check if there is a second image
                if($image2->id) $product->images()->saveMany([$image, $image2]);
                else $product->images()->save($image);
                $product->videos()->save($video);
                
            }

        });

        // For Spectator
        factory(App\Models\User::class, 2)->create()->each(function($user){
            $user->assignRole('spectator');
            // Create Spectator Profile
            $spectator = factory(App\Models\Spectator::class)->create();
            $spectator->users()->save($user);
        });
    }
}
