$(document).ready(function(){
    $('.collapsible').collapsible();
});

var slider = document.getElementById('price-range');
  noUiSlider.create(slider, {
   start: [minPrice, maxPrice],
   tooltips: [wNumb({ decimals: 2}), wNumb({ decimals: 1 })],
   connect: true,
   step: 1,
   range: {
     'min': minPrice,
     'max': maxPrice
   },
});

// var priceInput = document.getElementById('price-input');
//
// slider.noUiSlider.on('update', function( values, handle ) {
//
// 	var value = values[handle];
//
// });
//
// priceInput.addEventListener('change', function(){
// 	slider.noUiSlider.set([minPrice, this.value]);
// });
