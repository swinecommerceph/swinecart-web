{{--
	View for Registration Page which includes form for Customer registration
--}}

@extends('layouts.twoColumn')

@section('page-id')
    id="page-register"
@endsection

@section('left_column')
	<div class="row">
		<div class="card-panel">
			<div class="row s12">
				<h4 class="center-align"> Register </h4>
				{{-- Display Validation Errors --}}
				@include('common._errors')

				{{-- Registration Form --}}
				<form id="registration-form" action="{{ url('register') }}" method="POST" class="col s12">
					{{ csrf_field() }}

					{{-- Name --}}
					<div class="row">
						<div class="input-field col s12">
							<input class="validate" type="text" id="name" name="name" value="{{ old('name') }}" autofocus>
							<label for="name">Name</label>
						</div>
					</div>

					{{-- E-Mail Address --}}
					<div class="row">
						<div class="input-field col s12">
							<input class="validate" type="email" id="email" name="email" value="{{ old('email') }}">
							<label for="email">E-mail</label>
						</div>
					</div>

					{{-- Password --}}
					<div class="row">
						<div class="input-field col s12">
							<input class="validate" type="password" id="password" name="password">
							<label for="password">Password</label>
						</div>
					</div>

					{{-- Confirm Password --}}
					<div class="row">
						<div class="input-field col s12">
							<input class="validate" type="password" id="password_confirmation" name="password_confirmation">
							<label for="password_confirmation">Re-Type Password</label>
						</div>
					</div>

					{{-- Register Button --}}
					<div class="row">
						<div class="">
							<button type="submit" class="btn waves-effect waves-light col s5 push-s7"> Register
								<i class="material-icons right">send</i>
							</button>
						</div>
					</div>

				</form>

				<div class="row">
					<h5 class="center-align"> OR </h5>
					{{-- Facebook Button --}}
					<div class="col s12">
						<a href="/login/facebook" class="btn-large waves-effect waves-light indigo darken-2 col s12 social-button"> Register with Facebook </a>
					</div>
				</div>

				<div class="row">
					{{-- Google Button --}}
					<div class="col s12">
						<a href="/login/google" class="btn-large waves-effect waves-light red col s12 social-button"> Register with Google </a>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('right_column')
	<h4>Downloadable Forms</h4>
	<p> Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.
        In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.
    </p>
@endsection

@section('customScript')
    <script type="text/javascript">
        $(document).ready(function(){
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
                }
            };

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
    </script>
@endsection
