<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Digital CRM</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            /* background-color: #fff;
            color: #636b6f; */
            /* font-family: 'Raleway', sans-serif; */
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        .error {
            color: #a94442;
            border-color: #ee0c31;
        }
    </style>
</head>
<body>
    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
        <div class="top-right links">
            @auth
            <a href="{{ url('/home') }}">Dashboard</a>
            @else
            <!--<a href="{{ route('login') }}">Login</a>-->
            <!--<a href="{{ route('register') }}">Register</a>-->
            @endauth
            {{-- <a
            type="button"
            href="#"
            data-toggle="modal"
            data-target="#exampleModal"
            role="button"
            data-whatever="@mdo">Open Ticket</a> --}}
            <a href="{{route('createticket')}}">Open Ticket</a>
        </div>
        @endif

        <div class="content">
            <div class="title m-b-md">
                <img src="{{ url('/uploads/logo/digitalcrm-logo.png') }}" height="60" width="60">Digital CRM
            </div>

            <div class="links">
                <!--                    <a href="{{ route('login') }}">User Panel</a>
                    <a href="{{ url('admin') }}">Admin Panel</a>
                    <a href="#">Rest Api</a>-->
                </div>
            </div>
        </div>


        {{-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>

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
                }
                toastr.success('{{ Session::get('errors') }}')
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
                        required: true
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
