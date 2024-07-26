<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ getenv('APP_NAME')}}</title>

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
                    <form class="login-form" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="card mb-0">
							<div class="card-body">
								<div class="text-center mb-3">
									<div class="d-inline-flex bg-primary bg-opacity-10 text-primary lh-1 rounded-pill p-3 mb-3 mt-1">
										<i class="ph-arrows-counter-clockwise ph-2x"></i>
									</div>
									<h5 class="mb-0">Password recovery</h5>
									<span class="d-block text-muted">We'll send you instructions in email</span>
								</div>

								<div class="mb-3">
									<label class="form-label">Your email</label>
									<div class="form-control-feedback form-control-feedback-start">
										<input type="email" class="form-control" id="email" type="email" name="email" :value="old('email')">
										<div class="form-control-feedback-icon">
											<i class="ph-at text-muted"></i>
										</div>
									</div>
                                    <x-input-error :messages="$errors->get('email')" class="text-danger" />
								</div>

								<div class="mb-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ph-arrow-counter-clockwise me-2"></i>
                                        Reset password
                                    </button>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('login') }}"><i class="ph-arrow-left"></i> Back login</a>
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
                        <span>&copy; {{ date('Y') }} <b>Made with <span style="color: #e25555;">&hearts;</span></b> from <a href="https://inspima.id/">Inspima </a></span>

                        <ul class="nav">
                            <li class="nav-item">
                                <a href="https://inspima.id/" class="navbar-nav-link navbar-nav-link-icon rounded" target="_blank">
                                    <div class="d-flex align-items-center mx-md-1">
                                        <i class="ph-lifebuoy"></i>
                                        <span class="d-none d-md-inline-block ms-2">Support</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
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
