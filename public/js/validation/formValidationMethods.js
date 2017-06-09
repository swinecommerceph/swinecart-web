// Place error on specific HTML input
var placeError = function(inputElement, errorMsg){
    $(inputElement)
        .parents("form")
        .find("label[for='" + inputElement.id + "']")
        .attr('data-error', errorMsg);

    setTimeout(function(){
        $(inputElement).addClass('invalid');
    },0);
};

// Place success from specific HTML input
var placeSuccess = function(inputElement){
    // Check first if it is invalid
    if($(inputElement).hasClass('invalid')){
        $(inputElement)
            .parents("form")
            .find("label[for='" + inputElement.id + "']")
            .attr('data-error', false);

        setTimeout(function(){
            $(inputElement).removeClass('invalid');
            $(inputElement).addClass('valid');
        },0);
    }
    else {
        $(inputElement).addClass('valid');
    }
}

var validationMethods = {
    // functions must return either true or the errorMsg only
    required: function(inputElement){
        var errorMsg = 'This field is required';
        return inputElement.value ? true : errorMsg;
    },
    email: function(inputElement){
        var errorMsg = 'Please enter a valid email address';
        return /\S+@\S+\.\S+/.test(inputElement.value) ? true : errorMsg;
    },
    minLength: function(inputElement, min){
        var errorMsg = 'Please enter ' + min + ' or more characters';
        return (inputElement.value.length >= min) ? true : errorMsg;
    },
    equalTo: function(inputElement, compareInputElementId){
        var errorMsg = 'Please enter the same value';
        var compareInputElement = document.getElementById(compareInputElementId);
        return (inputElement.value === compareInputElement.value) ? true : errorMsg;
    },
    zipCodePh: function(inputElement){
        var errorMsg = 'Please enter zipcode of 4 number characters';
        return (/\d{4}/.test(inputElement.value) && inputElement.value.length === 4) ? true : errorMsg;
    },
    phoneNumber: function(inputElement){
        var errorMsg = 'Please enter 11-digit phone number starting with 09';
        return (/^09\d{9}/.test(inputElement.value) && inputElement.value.length === 11)  ? true : errorMsg;
    }

};
