<?php

use Illuminate\Database\Seeder;

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
        factory(App\Models\User::class, 2)->create()->each(function($user){
        	$user->assignRole('customer');
            $user->update_profile = 0;
            $user->email_verified = 1;

            // Create Customer Profile
            $customer = factory(App\Models\Customer::class)->create();
            // Create Farm Address
            $farm = factory(App\Models\FarmAddress::class)->create();

            $customer->users()->save($user);
            $customer->farmAddresses()->save($farm);
        });

        // For Breeders
        factory(App\Models\User::class, 5)->create()->each(function($user){
            $user->assignRole('breeder');
            $user->update_profile = 0;
            $user->email_verified = 1;

            // Create Breeder Profile
            $breeder = factory(App\Models\Breeder::class)->create();
            // Create Farm Address
            $farm = factory(App\Models\FarmAddress::class)->create();

            $breeder->users()->save($user);
            $breeder->farmAddresses()->save($farm);

            // Create up to 7 products as well
            // Initialization
            $rand = random_int(5,7);
            $types = ['sow', 'boar', 'semen']; // 3
            $breeds = ['largewhite', 'landrace', 'duroc', 'pietrain', 'landrace+duroc', 'largewhite+duroc', 'chesterwhite']; // 7
            for ($i = 0; $i < $rand; $i++) {
                $randType = $types[random_int(0,2)];
                $randBreed = $breeds[random_int(0,6)];
                $product = new App\Models\Product;
                $image = new App\Models\Image;
                $image2 = new App\Models\Image;
                $video = new App\Models\Video;

                if($randType == 'sow' && $randBreed == 'duroc'){ // Sow Duroc Two images
                    $image->path = 'images/product/'.$randType.'_'.$randBreed.'1.jpg';
                    $image2->path = 'images/product/'.$randType.'_'.$randBreed.'2.jpg';
                    $image2->save();
                }
                elseif (($randType == 'boar' || $randType == 'semen') && $randBreed == 'landrace+duroc') { // Boar/Semen Crossbreed
                    $image->path = 'images/product/'.$randType.'_cb_landraceDuroc1.jpg';
                }
                elseif ($randType == 'sow' && $randBreed == 'largewhite+duroc') { // Sow Crossbreed
                    $image->path = 'images/product/'.$randType.'_cb_largewhiteDuroc1.jpg';
                }
                elseif ($randBreed == 'chesterwhite') { // Others
                    $image->path = 'images/product/'.$randType.'_chesterwhite1.jpg';
                }
                else { // General
                    $image->path = 'images/product/'.$randType.'_'.$randBreed.'1.jpg';
                }
                $image->save();

                $video->path = 'videos/product/sample_video.avi';

                $product->farm_from_id = $farm->id;
                $product->primary_img_id = $image->id;
                $product->name = random_int(1000,3000);
                $product->type = $randType;
                $product->age = random_int(110,160);
                $product->breed_id = App\Models\Breed::firstOrCreate(['name' => $randBreed])->id;
                $product->price = random_int(35000,100000)/1.0;
                if($randType == 'semen') $product->quantity = random_int(10,300);
                else $product->quantity = 1;
                $product->adg = random_int(760,1450);
                $product->fcr = random_int(10,30)/10.0;
                $product->backfat_thickness = random_int(90,200)/10.0;
                $product->other_details = 'Our detailed information of our product';
                $breeder->products()->save($product);

                // Check if there is a second image
                if($image2->id) $product->images()->saveMany([$image, $image2]);
                else $product->images()->save($image);
                $product->videos()->save($video);

            }

        });
    }
}
