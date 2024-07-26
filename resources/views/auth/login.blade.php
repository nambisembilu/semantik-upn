<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ getenv('APP_NAME')}} </title>
    <link rel="icon" type="image/png" href="{{ asset(getenv('APP_LOGO_ICON')) }}">

    <!-- Global stylesheets -->
    <link href="{{ asset('assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="assets/css/ltr/all.min.css" id="stylesheet') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- /theme JS files -->

</head>

<body>

<!-- Page content -->
<div class="page-content">

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Inner content -->
        <div class="content-inner">

            <!-- Content area -->
            <div class="content d-flex justify-content-center align-items-center">

                <!-- Login form -->
                <form class="login-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="d-flex flex-column align-items-center justify-content-center mb-4 mt-2">
                                    <img src="{{ asset(getenv('APP_LOGO_ICON')) }}" class="w-72px" alt="">
                                    <img src="{{ asset(getenv('APP_LOGO_SECOND_ICON')) }}" class="h-48px" alt="">
                                </div>
                                <span class="d-block text-muted">Silahkan masukkan identitas akun</span>
                            </div>
                            @if ($errors->any())
                                <div class="alert bg-danger text-white alert-icon-start alert-dismissible fade show border-0">
                                        <span class="alert-icon bg-black bg-opacity-20">
                                            <i class="ph-x-circle"></i>
                                        </span>
                                    <span class="fw-semibold">Gagal!</span>
                                    @foreach ($errors->all() as $error)
                                        <span>{{ $error }}</span>
                                    @endforeach
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="form-control-feedback form-control-feedback-start">
                                    <input type="text" class="form-control" id="username" name="username" value="{{old('username')}}" placeholder="username">
                                    <div class="form-control-feedback-icon">
                                        <i class="ph-user-circle text-muted"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="form-control-feedback form-control-feedback-start">
                                    <input type="password" id="password" type="password" name="password" required class="form-control" placeholder="•••••••••••">
                                    <div class="form-control-feedback-icon">
                                        <i class="ph-lock text-muted"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-warning w-100">Sign in</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /login form -->

            </div>
            <!-- /content area -->


            <!-- Footer -->
            <div class="navbar navbar-sm navbar-footer border-top">
                <div class="container-fluid">
                    <span>&copy; {{ date('Y') }} from <a href="https://inspima.id/">Developer </a></span>

                </div>
            </div>
            <!-- /footer -->

        </div>
        <!-- /inner content -->

    </div>
    <!-- /main content -->

</div>
<!-- /page content -->


</body>

</html>
