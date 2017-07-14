var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    // Concatenate common vendor js files
    mix.combine([
        'resources/assets/js/vendor/jquery.min.js',
        'resources/assets/js/vendor/materialize.min.js',
        'resources/assets/js/vendor/VueJS/vue.min.js',
        'resources/assets/js/vendor/VueJS/vue-resource.min.js',
        'resources/assets/js/vendor/moment.min.js',
        'resources/assets/js/vendor/autobahn.min.js',
        'resources/assets/js/vendor/lodash.min.js'
    ], 'public/js/vendor.js');

    // Concatenate js files for specific pages
    mix.scripts([
            'config.js',
            'custom.js'
        ], 'public/js/siteCustom.js')
        .scripts([
            'vendor/elasticsearch.jquery.min.js',
            'customer/swinecart.js',
            'customer/customer_custom.js'
        ], 'public/js/customer/custom.js')
        .scripts([
            'customer/createProfile_script.js',
            'validation/formValidationMethods.js',
            'validation/customer/createProfile_validation.js'
        ], 'public/js/customer/createProfile.js')
        .scripts([
            'customer/profile.js',
            'customer/editProfile_script.js',
            'validation/formValidationMethods.js',
            'validation/customer/editProfile_validation.js'
        ], 'public/js/customer/editProfile.js')
        .scripts([
            'vendor/VideoJS/video.min.js',
            'vendor/imagezoom.min.js',
            'customer/viewProductDetail_script.js'
        ], 'public/js/customer/viewProductDetail.js')
        .scripts([
            'customer/filter.js',
            'customer/viewProducts_script.js'
        ], 'public/js/customer/viewProducts.js');


    // Version the following files to promote browser cache busting
    // Forces browser to download latest asset files
    mix.version([
        'js/siteCustom.js',
        'js/vendor.js',
        'js/customer/custom.js',
        'js/customer/createProfile.js',
        'js/customer/editProfile.js',
        'js/customer/notifications.js',
        'js/customer/swinecartPage.js',
        'js/customer/viewProductDetail.js',
        'js/customer/viewProducts.js'
    ]);

    //  -app:
    //      > Customer
    //          + pages:
    //              _ createProfile
    //              _ editProfile
    //              _ swineCart
    //              _ viewBreeders (maps)
    //              _ viewProductDetail
    //              _ viewProducts
    //      > Breeder:
    //          +pages:
    //              _ createProfile
    //              _ editProfile
    //              _ reviews
    //              _ dashboard
    //              _ dashboardProductStatus
    //              _ showProducts
    //              _ viewCustomers (maps)
    //              _ viewProductDetail
    //  -vendor: jquery, materialize, vue, vue-resource
    //      > Customer: moment, autobahn, elasticsearch.jquery (home, products), lodash (swineCart page), videojs (viewProductDetail), imagezoom (viewProductDetail)
    //      > Breeder: moment, autobahn, videojs (showProducts, viewProductDetail), imagezoom, dropzone (editProfile, showProducts, viewProductDetail), lodash (dashboardProductStatus)
});
