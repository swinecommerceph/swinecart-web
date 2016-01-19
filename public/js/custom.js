$(document).ready(function(){

	$(".dropdown-button").dropdown({
        hover:false,
        constrainwidth:false,
        gutter: 0,
        belowOrigin: false,
        alignment: 'right'
    });

	$(".collapsible").collapsible({
      accordion : false
    });

    var provinces = [
        // Negros Island Rregion
        'Negros Occidental',
        'Negros Oriental',
        // Cordillera Administrative Region
        'Mountain Province',
        'Ifugao',
        'Benguet',
        'Abra',
        'Apayao',
        'Kalinga',
        // Region I
        'La Union',
        'Ilocos Norte',
        'Ilocos Sur',
        'Pangasinan',
        // Region II
        'Nueva Vizcaya',
        'Cagayan',
        'Isabela',
        'Quirino',
        'Batanes',
        // Region III
        'Bataan',
        'Zambales',
        'Tarlac',
        'Pampanga',
        'Bulacan',
        'Nueva Ecija',
        'Aurora',
        // Region IV-A
        'Rizal',
        'Cavite',
        'Laguna',
        'Batangas',
        'Quezon',
        // Region IV-B
        'Occidental Mindoro',
        'Oriental Mindoro',
        'Romblon',
        'Palawan',
        'Marinduque',
        // Region V
        'Catanduanes',
        'Camarines Norte',
        'Sorsogon',
        'Albay',
        'Masbate',
        'Camarines Sur',
        // Region VI
        'Capiz',
        'Aklan',
        'Antique',
        'Iloilo',
        'Guimaras',
        // Region VII
        'Cebu',
        'Bohol',
        'Siquijor',
        // Region VIII
        'Southern Leyte',
        'Eastern Samar',
        'Northern Samar',
        'Western Samar',
        'Leyte',
        'Biliran',
        // Region IX
        'Zamboanga Sibugay',
        'Zamboanga del Norte',
        'Zamboanga del Sur',
        // Region X
        'Misamis Occidental',
        'Bukidnon',
        'Lanao del Norte',
        'Misamis Oriental',
        'Camiguin',
        // Region XI
        'Davao Oriental',
        'Compostela Valley',
        'Davao del Sur',
        'Davao Occidental',
        'Davao del Norte',
        // Region XII
        'South Cotabato',
        'Sultan Kudarat',
        'North Cotabato',
        'Sarangani',
        // Region XIII
        'Agusan del Norte',
        'Agusan del Sur',
        'Surigao del Sur',
        'Surigao del Norte',
        'Dinagat Islands',
        // ARMM
        'Tawi-tawi',
        'Basilan',
        'Sulu',
        'Maguindanao',
        'Lanao del Sur'
    ]

});
