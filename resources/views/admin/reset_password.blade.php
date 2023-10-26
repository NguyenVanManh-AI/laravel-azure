@extends('layouts.master')
@section('content')
    <link rel="stylesheet" href="{{ asset('user/css/reset_password.css') }}">
    <div class="container">
        <div class="row">
            <div class="mt-5 col-lg-10 col-xl-9 mx-auto">
                <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
                    <div style="background-image: url('{{ asset('image/register.jfif') }}')" class="card-img-left d-none d-md-flex">
                    </div>
                    <div class="card-body pt-4 pb-4 pl-5 pr-5">
                        <h5 class="card-title text-center mb-4 fw-light fs-5">Reset Password</h5>
                        <form method="POST" action="{{ route('admin_forgot_update') }}" enctype="multipart/form-data">
                            @csrf
                            <input hidden value="{{ old('token') }}" name="token" type="text" class="form-control"
                                id="token" placeholder="Token">
                            <div class="form-floating mb-3 has-float-label">
                                <input minlength="6" value="{{ old('new_password') }}" name="new_password" type="password"
                                    class="form-control" id="floatingPassword" placeholder="Password" required>
                                <label for="floatingPassword">New Password</label>
                            </div>
                            <div class="form-floating mb-3 has-float-label">
                                <input minlength="6" value="{{ old('new_password_confirmation') }}" name="new_password_confirmation"
                                    type="password" class="form-control" id="floatingPasswordConfirm"
                                    placeholder="Confirm Password" required>
                                <label for="floatingPasswordConfirm">Confirm New Password</label>
                            </div>
                            <div class="d-grid mb-2">
                                <button class="col-12 btn btn-lg btn-primary btn-login fw-bold text-uppercase"
                                    type="submit">Submit</button>
                            </div>
                            <a class="d-block text-center mt-2 small" href="{{ \App\Enums\UserEnum::DOMAIN_CLIENT . 'auth/sign-in'}}">Have an account? Sign
                                In</a>
                        </form>
                        <script>
                            url_string = window.location.href;
                            var url = new URL(url_string);
                            var token = url.searchParams.get("token");
                            var inputToken = window.document.getElementById('token');
                            inputToken.value = token;
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
