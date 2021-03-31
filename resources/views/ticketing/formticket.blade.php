<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ ('TicketForm') }}</title>
        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <style>
            .error {
                color: #a94442;
                border-color: #ee0c31;
            }
        </style>
        <!---Google Captcha--->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" type="text/css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
    </head>
    <body>
        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Ticket Form</div>

                            <div class="card-body">
                                <form action="{{ route('openticket.store') }}" method="POST" enctype="multipart/form-data" id="form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="first_name" class="col-form-label">FirstName:</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name">
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-form-label">Lastname:</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name">
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="col-form-label">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="col-form-label">Subject:</label>
                                        <input type="text" class="form-control" id="name" name="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="col-form-label">Message:</label>
                                        <textarea class="form-control" id="description" name="description"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <div
                                            class="g-recaptcha"
                                            data-sitekey="{{ config('services.recaptcha.sitekey') }}"
                                            data-error-callback="Fill the recaptcha"
                                            data-expired-callback="Your Recaptcha has expired, please verify it again !">
                                        </div>
                                    </div>

                                <div class="card-footer">
                                    {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button> --}}
                                    <a class="btn btn-info" href="{{ url()->previous() }}">Back</a>
                                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

    @if(Session::has('message'))
    <script>
        $(function () {
            toastr.options = {
                "positionClass": "toast-top-left",
            }
            toastr.success('{{ Session::get('message') }}')
        });
    </script>
    @endif

    @if (Session::has('errors'))
    <script>
        $(function () {
            toastr.options = {
                "positionClass": "toast-top-left",
                "progressBar": true,
                "debug": false,
            }
            toastr.error('{{ Session::get('errors') }}')
        });
    </script>
    @endif

    <script>

        $(document).ready(function () {

            $('#form').validate({ // initialize the plugin
                rules: {
                    first_name: "required",
                    last_name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    name: {
                        required: true,
                        maxlenght: 50,
                    },
                    description: {
                        required: true,
                    },
                },
                messages: {
                    first_name: "please enter your first name",
                    last_name: "please enter your last name",
                    email: "please enter valid email",
                    name: {
                        required: "please enter title of ticket",
                        maxlenght: "your text must be maximum of 50 characters",
                    },
                    description: "please fill details of your query",
                }
            });
        });
    </script>
</body>
</html>
