@extends('layout.auth')
@section('content')
    <div id="login">
        <h3 class="text-center text-white pt-5">Login form</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form id="login-form" class="form" action="{{ route('login') }}" method="post">
                            @csrf
                            <h3 class="text-center text-info">Login</h3>
                            <div class="form-group">
                                <label for="email" class="text-info">Email</label><br>
                                <input type="text" name="email" id="email" class="form-control">
                                <div class="text-danger">
                                    @if ($errors->has('email'))
                                        {{ $errors->first('email') }}
                                    @endif
                                    @if (session()->has('email_wrong'))
                                        {{ session('email_wrong') }}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="text-info">Password</label><br>
                                <input type="password" name="password" id="password" class="form-control">
                                <div class="text-danger">
                                    @if ($errors->has('password'))
                                        {{ $errors->first('password') }}
                                    @endif
                                    @if (session()->has('password_wrong'))
                                        {{ session('password_wrong') }}
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="remember" class="text-info"><span>Remember me</span> <span><input id="remember"
                                            name="remember" type="checkbox"></span></label><br>
                                <button type="submit" class="btn btn-info btn-md">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
