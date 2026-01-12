<?php
$bootstrap = require __DIR__ . '/../app/bootstrap.php';
include __DIR__ . '/../app/functions.php';
include __DIR__ . '/../app/discord.php';

//Make sure we're where we need to be based on the tracked step.
if(empty($_SESSION['ApplicationStep']) || $_SESSION['ApplicationStep'] == 1)
{
    header("Location:/Apply");
    exit;
}
else
{
    switch($_SESSION['ApplicationStep'])
    {
        case 2: $rsiToken = 'TRL:' . getRSIAuthTokenByApplicantId($_SESSION['user']['ApplicantID']);
                break;
        case 3: header("Location:/SubmitApplication");
                exit;
        default: header("Location:/");
                exit;
    }
}

?>
<!doctype html>
<html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="assets/img/favicon.ico" type="image/ico">
        <?php
            include __DIR__ . '/../app/partials/head-includes.html';
        ?>
        <!--Form steps css-->
        <link rel="stylesheet" href="assets/vendor/node_modules/css/bs-stepper.min.css">
        <!--Prism css snippets-->
        <link rel="stylesheet" href="assets/vendor/node_modules/css/prism-tomorrow.css">
        <!-- Main CSS -->
        <link href="assets/css/theme.min.css" rel="stylesheet">

        <title>The Red Legion - Apply</title>
    </head>

    <body>
        <?php
	    	include __DIR__ . '/../app/partials/preloader.html';
	    ?>

        <!--Header Start-->
        <header class="z-fixed header-transparent header-absolute-top sticky-reverse">
            <nav class="navbar navbar-expand-lg navbar-light navbar-link-white">
                <div class="container position-relative">
                    <a class="navbar-brand" href="index.html">
                        <img src="assets/img/logo/logo.png" alt="" class="img-fluid navbar-brand-sticky">
                        <img src="assets/img/logo/logo.png" alt="" class="img-fluid navbar-brand-transparent">
                    </a>
                    <div class="d-flex align-items-center navbar-no-collapse-items order-lg-last">
                        <button class="navbar-toggler order-last" type="button" data-bs-toggle="collapse"
                            data-bs-target="#mainNavbarTheme" aria-controls="mainNavbarTheme" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon">
                                <i></i>
                            </span>
                        </button>
                        <div class="nav-item me-2 d-none d-xl-flex">
                            <a href="/Login" class="btn btn-danger btn-sm rounded-pill">Staff Login</a>
                        </div>
                    </div>
                    <div class="collapse navbar-collapse" id="mainNavbarTheme">
                    <?php
				        //Set the default active link.
				        $page = 'apply';
			        	include __DIR__ . '/../app/partials/headers/default-navbar-items.html';
		        	?>
                    </div>
                </div>
            </nav>
        </header>
        <!--Main content-->
        <main>
           <!--page-hero-->
           <section class="bg-gradient-primary text-white position-relative">
                <div class="container pt-8 pb-6 pb-lg-8 position-relative z-1">
                    <div class="row pt-lg-4 align-items-center justify-content-center text-center">
                    <div class="col-lg-10 col-xl-7 z-2">
                        <div class="position-relative">
                        <div>
                            <h1 class="mb-0 display-4">
                            Join The Red Legion
                            </h1>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </section>

                <!-- Display error if needed -->
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-warning alert-dismissible fade show rounded-0 mb-0" role="alert">
                        <?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php unset($_SESSION['error']); ?>
                <?php endif; ?>


                <!--:::Begin Divider Card-->
                <section class="position-relative overflow-hidden">
                <div class="container py-9 py-lg-11">
                    <div class="card mb-4 overflow-hidden">
                        <div class="card-header">
                            <h5 class="mb-0">Step 3: Submit Your Application</h5>
                        </div>
                        <div class="card-body">
                            <!--Content-Section-->
                            <section class="pb-7 bg-primary position-relative overflow-hidden">
                                <!--Container-->
                                <div class="py-3 py-lg-11 container text-white">
                                    <h6>

                                    <form method="post" action="/DoSubmitApplication">

                                        <div class="mb-3">
                                            <label for="why_join" class="form-label">
                                                1. Why do you want to join <strong>The Red Legion</strong>?
                                            </label><p><br>

                                            <textarea
                                                id="why_join"
                                                name="why_join"
                                                class="form-control w-50"
                                                rows="5"
                                                placeholder="Tell us what draws you to The Red Legion, your interests, or what you hope to contribute..."
                                                required
                                            ></textarea>
                                        </div>

                                        

                                            <!-- Submit Button -->
                                            <div class="d-flex justify-content-center">
                                                <button type="submit"
                                                        class="btn btn-discord btn-outline-light d-inline-flex align-items-center">
                                                    <i class="bi bi-send-fill"></i>&nbsp; Submit Application
                                                </button>
                                            </div>

                                        </form>
                                    </h6>
                                </div>

                                <!--Section divider-->
                                <svg class="w-100 position-absolute flip-y start-0 bottom-0" style="color: var(--bs-body-bg);"
                                    height="48" fill="currentColor" preserveAspectRatio="none" viewBox="0 0 1200 120"
                                    xmlns="http://www.w3.org/2000/svg" style="transform: rotate(180deg) scaleX(-1);">
                                    <path d="M0 0v46.29c47.79 22.2 103.59 32.17 158 28 70.36-5.37 136.33-33.31 206.8-37.5 73.84-4.36 147.54
    16.88 218.2 35.26 69.27 18 138.3 24.88 209.4
    13.08 36.15-6 69.85-17.84 104.45-29.34C989.49 25 1113-14.29 1200 52.47V0z" opacity=".25" />
                                    <path d="M0 0v15.81c13 21.11 27.64 41.05 47.69 56.24C99.41 111.27 165 111 224.58 91.58c31.15-10.15
    60.09-26.07 89.67-39.8 40.92-19 84.73-46 130.83-49.67 36.26-2.85 70.9 9.42 98.6 31.56 31.77 25.39
    62.32 62 103.63 73 40.44 10.79 81.35-6.69 119.13-24.28s75.16-39 116.92-43.05c59.73-5.85 
    113.28 22.88 168.9 38.84 30.2 8.66 59 6.17 87.09-7.5 22.43-10.89 48-26.93 60.65-49.24V0z" opacity=".5" />
                                    <path d="M0 0v5.63C149.93 59 314.09 71.32 475.83 42.57c43-7.64 84.23-20.12 127.61-26.46 59-8.63
    112.48 12.24 165.56 35.4C827.93 77.22 886 95.24 951.2 90c86.53-7 172.46-45.71 248.8-84.81V0z" />
                                </svg>
                            </section>
                        </div>
                    </div>
                </div>
                </section>
                <!--:::/End Divider Card-->
            
        </main>

        <?php
		    //Footer.	
	    	include __DIR__ . '/../app/partials/footers/footer-9.php';
        ?>
        <!-- begin Back to Top button -->
        <a href="#" class="toTop"><svg class="progress-circle" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
  <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="4" stroke-dasharray="0, 251.2"></circle>
</svg>
            <i class="bi bi-chevron-up"></i>
        </a>
        <!-- scripts -->
        <script src="assets/js/theme.bundle.min.js"></script>
    </body>

</html>