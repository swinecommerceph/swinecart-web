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

elixir(function (mix) {
  // Concatenate common vendor js files
  mix.combine([
    'resources/assets/js/vendor/jquery.min.js',
    'resources/assets/js/vendor/materialize.min.js',
    'resources/assets/js/vendor/VueJS/vue.min.js',
    'resources/assets/js/vendor/VueJS/vue-resource.min.js',
    'resources/assets/js/vendor/moment.min.js',
    'resources/assets/js/vendor/autobahn.min.js',
    'resources/assets/js/vendor/lodash.min.js',
    'resources/assets/js/vendor/patternomaly.js'
  ], 'public/js/vendor.js');

  // Concatenate js files for specific pages
  mix.scripts([
    'config.js',
    'custom.js'
  ], 'public/js/siteCustom.js')
    .scripts([
      'validation/formValidationMethods.js',
      'validation/login_validation.js',
      'show-hide-pw.js'
    ], 'public/js/login.js')
    .scripts([
      'vendor/elasticsearch.jquery.min.js',
      'customer/swinecart.js',
      'customer/customer_custom.js'
    ], 'public/js/customer/custom.js')
    .scripts([
      'customer/swinecartPage.js'
    ], 'public/js/customer/swinecartPage.js')
    .scripts([
      'customer/createProfile_script.js',
      'validation/formValidationMethods.js',
      'validation/customer/createProfile_validation.js'
    ], 'public/js/customer/createProfile.js')
    .scripts([
      'customer/profile.js',
      'customer/editProfile_script.js',
      'validation/formValidationMethods.js',
      'validation/customer/editProfile_validation.js',
      'show-hide-pw.js'
    ], 'public/js/customer/editProfile.js')
    .scripts([
      'vendor/VideoJS/video.min.js',
      'vendor/imagezoom.min.js',
      'customer/viewProductDetail_script.js'
    ], 'public/js/customer/viewProductDetail.js')
    .scripts([
      'customer/filter.js',
      'customer/viewProducts_script.js'
    ], 'public/js/customer/viewProducts.js')
    .scripts([
      'vendor/dropzone.min.js',
      'vendor/VideoJS/video.min.js',
      'breeder/breeder_custom.js'
    ], 'public/js/breeder/custom.js')
    .scripts([
      'breeder/createProfile_script.js',
      'validation/formValidationMethods.js',
      'validation/breeder/createProfile_validation.js'
    ], 'public/js/breeder/createProfile.js')
    .scripts([
      'breeder/profile.js',
      'breeder/editProfile_script.js',
      'validation/formValidationMethods.js',
      'validation/breeder/editProfile_validation.js',
      'show-hide-pw.js'
    ], 'public/js/breeder/editProfile.js')
    .scripts([
      'vendor/chart.min.js',
      'breeder/dashboardPage.js'
    ], 'public/js/breeder/dashboard.js')
    .scripts([
      'breeder/dashboardProductStatus.js'
    ], 'public/js/breeder/dashboardProductStatus.js')
    .scripts([
      'breeder/product.js',
      'breeder/filter.js',
      'breeder/manageProducts_script.js',
      'validation/formValidationMethods.js',
      'validation/breeder/manageProducts_validation.js'
    ], 'public/js/breeder/showProducts.js')
    .scripts([
      'breeder/product.js',
      'breeder/editProduct.js',
      'breeder/filter.js',
      'breeder/manageProducts_script-edit.js',
      'validation/formValidationMethods.js',
      'validation/breeder/manageProducts_validation.js'
    ], 'public/js/breeder/editProducts.js')
    .scripts([
      'vendor/imagezoom.min.js',
      'breeder/viewProductDetail_script.js'
    ], 'public/js/breeder/viewProductDetail.js')
    .scripts([
      'validation/formValidationMethods.js',
      'validation/registration_validation.js'
    ], 'public/js/register.js')
    .scripts([
      'vendor/dropzone.min.js',
      'chat.js'
    ], 'public/js/chat.js');

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
    'js/customer/viewProducts.js',
    'js/breeder/custom.js',
    'js/breeder/createProfile.js',
    'js/breeder/editProfile.js',
    'js/breeder/notifications.js',
    'js/breeder/reviews.js',
    'js/breeder/dashboard.js',
    'js/breeder/showProducts.js',
    'js/breeder/viewProductDetail.js'
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
  //          + pages:
  //              _ createProfile
  //              _ editProfile
  //              _ reviews
  //              _ notifications
  //              _ dashboard
  //              _ dashboardProductStatus
  //              _ showProducts
  //              _ viewCustomers (maps)
  //              _ viewProductDetail
  //  -vendor: jquery, materialize, vue, vue-resource, moment, autobahn, lodash
  //      > Customer: elasticsearch.jquery (home, products), lodash (swineCart page), videojs (viewProductDetail), imagezoom (viewProductDetail)
  //      > Breeder: videojs (showProducts, viewProductDetail), imagezoom, dropzone (editProfile, showProducts), lodash (dashboardProductStatus)
});
