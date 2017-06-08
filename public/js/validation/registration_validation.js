$(document).ready(function(){
    
    var validateInput = function(inputElement){
        // Initialize needed validations
        var validations = {
            name: ['required'],
            email: ['required', 'email'],
            password: ['required', 'minLength:8'],
            password_confirmation: ['required', 'equalTo:password']
        };

        // Check if validation rules exist
        if(validations[inputElement.id]){
            var result = true;

            for (var i = 0; i < validations[inputElement.id].length; i++) {
                var element = validations[inputElement.id][i];
                var method = element.includes(':') ? element.split(':') : element;

                result = (typeof(method) === 'object')
                    ? (validationMethods[method[0]](inputElement, method[1]))
                    : (validationMethods[method](inputElement));

                // Result would return to a string errorMsg if validation fails
                if(result !== true){
                    placeError(inputElement, result);
                    return false;
                }
            }

            // If all validations succeed then
            if(result === true){
                placeSuccess(inputElement);
                return true;
            }
        }
    };

    // Focusout events
    $("input").focusout(function(e){
        e.preventDefault();

        validateInput(this);
    });

    // OnKeypressUp events
    $("input").keyup(function(e){
        e.preventDefault();

        if($(this).hasClass('invalid')) validateInput(this);
    })

    $("button[type='submit']").click(function(e){
        e.preventDefault();

        var validName = validateInput(document.getElementById('name'));
        var validEmail = validateInput(document.getElementById('email'));
        var validPassword = validateInput(document.getElementById('password'));
        var validPasswordConfirmation = validateInput(document.getElementById('password_confirmation'));

        if(validName && validEmail && validPassword && validPasswordConfirmation){
            $(this).addClass('disabled');
            $(this).parents('form').submit();
        }
    })

});
