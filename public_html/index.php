<?php
include __DIR__ . '/../app/partials/head-includes.html';
?>

<!doctype html>
<html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/img//favicon-16x16.png">
	<link rel="manifest" href="assets/img/site.webmanifest">

        <!--Swiper slider-->
        <link rel="stylesheet" href="assets/vendor/node_modules/css/swiper-bundle.min.css">

        <!-- Main CSS -->
        <link href="assets/css/theme.min.css" rel="stylesheet">

        <title>The Red Legion</title>
    </head>

    <body>
	<?php
		include __DIR__ . '/../app/partials/preloader.html';
	?>

        <!--Header Start-->
        <header class="z-fixed header-transparent header-absolute-top pt-lg-3 header-sticky">
            <nav class="navbar navbar-expand-lg navbar-light navbar-link-white">
                <div class="container position-relative">
                    <a class="navbar-brand" href="/">
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
                        
                        <div class="nav-item position-relative ms-3 me-1">
                            <a href="https://discord.gg/RedLegion" target='_blank'
                                class="btn btn-white p-0 rounded-circle si width-3x height-3x si-colored-linkedin d-flex align-items-center justify-content-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Join our Discord!">
                                <i class="bi bi-discord fs-6"></i>
                                <i class="bi bi-discord fs-6"></i>
                            </a>
                        </div>
                        <div class="nav-item position-relative ms-1 me-1">
                            <a href="https://robertsspaceindustries.com/en/orgs/REDLEGN" target='_blank'
				class="p-0 rounded-circle si width-3x height-3x si-colored-linkedin d-flex align-items-center justify-content-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Join us on RSI!">

                                <i class="bi bi-rocket-takeoff fs-6 text-white"></i>
                                <i class="bi bi-rocket-takeoff fs-6 text-white"></i>
                            </a>
                        </div>
                        <div class="nav-item position-relative ms-1 me-1">
                            <a href="https://www.twitch.tv/RedMonsterSC" target='_blank'
				class="p-0 rounded-circle si width-3x height-3x si-colored-linkedin d-flex align-items-center justify-content-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Follow RedMonsterSC on Twitch!">

                                <i class="bi bi-twitch fs-6 text-white"></i>
                                <i class="bi bi-twitch fs-6 text-white"></i>
                            </a>
                        </div>
                        <div class="nav-item position-relative ms-1 me-1">
                            <a href="https://www.youtube.com/@RedMonsterSC" target='_blank'
				class="p-0 rounded-circle si width-3x height-3x si-colored-linkedin d-flex align-items-center justify-content-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Subscribe to RedMonsterSC on YouTube!">

                                <i class="bi bi-youtube fs-6 text-white"></i>
                                <i class="bi bi-youtube fs-6 text-white"></i>
                            </a>
                        </div>

                        <div class="nav-item me-2 d-none d-xl-flex">
                            <a href="/Apply" class="btn btn-success btn-sm rounded-pill">Join Today!</a>
                        </div>

                        <div class="nav-item me-2 d-none d-xl-flex">
                            <a href="/Login" class="btn btn-danger btn-sm rounded-pill">Staff Login</a>
                        </div>
                    </div>
		    <div class="collapse navbar-collapse" id="mainNavbarTheme">
			<?php
				//Set the default active link.
				$page = 'home';
				include __DIR__ . '/../app/partials/headers/default-navbar-items.html';
			?>
                    </div>
                </div>
            </nav>
        </header>
        <!--Main content-->
        <main class="main-content" id="main-content">
            <!--page-hero-->
            <section class="bg-dark text-white position-relative overflow-hidden">


        <!--begin:Video-bg-->
        <div class="w-100 position-absolute end-0 top-0 overflow-hidden opacity-25" style="min-height:60vh; height:100%;">

        <!-- Video background -->
        <div class="jarallax h-100 w-100"
            data-jarallax
            data-jarallax-video="https://vimeo.com/1121338232">
        </div>

        <!-- Fallback logo OVER the video initially -->
        <div id="video-fallback"
            class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
            style="background:#000; z-index:2;">
            <img src="assets/img/logo/logo.png" alt="Background cover" style="width:50%; height:auto;">
        </div>

        </div>

        <script>
        // simplest: fade the overlay after ~1.5s (adjust to taste)
        document.addEventListener('DOMContentLoaded', function () {
            const overlay = document.getElementById('video-fallback');
            if (!overlay) return;
            setTimeout(function () {
            overlay.style.transition = 'opacity .4s ease';
            overlay.style.opacity = '0';
            overlay.style.pointerEvents = 'none';
            overlay.addEventListener('transitionend', () => overlay.remove(), { once: true });
            }, 1500);
        });
        </script>
        <!--end:Video-bg-->



                <!--begin:container-->
                <div class="container pt-12 pb-12 position-relative z-1">
                    <div class="row pt-lg-9 pb-lg-5 align-items-center">
                        <div class="col-lg-8 mx-auto text-center mb-5 mb-lg-0">
                            <!--begin:swiper text-slider-->
                            <div
                                class="swiper-container swiper-text w-100 height-3x fs-5 fw-bold text-uppercase mb-3 text-white overflow-hidden">
                                <!--begin:Slider wrapper-->
                                <div class="swiper-wrapper text-warning">
                                    <!--Slide item-->
                                    <div class="swiper-slide justify-content-center">
                                        Resource Extraction and Processing
                                    </div>
                                    <!--Slide item-->
                                    <div class="swiper-slide justify-content-center">
                                        Asset Reclamation
                                    </div>
                                    <!--Slide item-->
                                    <div class="swiper-slide justify-content-center">
                                        Shipping &amp; Logistics
                                    </div>
                                    <!--Slide item-->
                                    <div class="swiper-slide justify-content-center">
                                        Supply Chain Management
                                    </div>
                                    <!--Slide item-->
                                    <div class="swiper-slide justify-content-center">
                                        Field Support &amp; Protection
                                    </div>
                                </div>
                                <!--end:Slider wrapper-->

                            </div>
                            <!--end:swiper text-slider-->

                            <!--Hero title-->
                            <h1 class="display-3 mb-4 mb-lg-5">
				An industry focused <span class="text-gradient-light">logistics organization.</span> 
			    </h1>

                            <!--Hero description-->
                            <p class="mb-5 mb-lg-7 lead w-lg-60 mx-auto">
                                The Red Legion, led by RedMonsterSC, is an industry-driven organization built on cooperation. We specialize in large-scale mining, salvage, and refining operations.
                            </p>

                            <!--Hero action button-->
                            <div class="d-flex align-items-center justify-content-center">
                                <a href="/Apply" class="btn btn-primary btn-lg me-2 me-lg-3">Join The Red Legion Today!</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end:container-->

                <!--begin:Divider shape-->
                <svg class="position-absolute start-0 bottom-0 mb-n1" style="color:var(--bs-body-bg)" preserveAspectRatio="none" width="100%"
                    height="80" viewBox="0 0 1460 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M122 22.8261L0 0V120H1460V0L1338 22.8261C1217 44.1304 973 88.2609 730 88.2609C487 88.2609 243 44.1304 122 22.8261Z"
                        fill="currentColor"></path>
                </svg>
                <!--end:Divider shape-->
            </section>
            <!--/section-->

            <!--end:about section-->
            <section class="position-relative" id="about">
                <div class="container py-9 py-lg-11 position-relative">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-lg-6 col-xl-5 order-lg-last mb-7 mb-lg-0">
                            <!--Heading-->
                            <h3 class="position-relative mb-5 display-6" data-aos="fade-up">
                                About Us
                            </h3>
                            <!--text-->
                            <p class="mb-0 lead" data-aos="fade-up" data-aos-delay="100">
                                The mission of <span class="text-gradient-light">The Red Legion</span> is to forge a 
                                thriving community of industrious gamers within the vast universe of Star Citizen.

                                We are dedicated to embracing and excelling 
                                in the challenging realm of industrial gameplay, where resource extraction, refining, 
                                salvaging, and trade take center stage.
                            </p>
                        </div>
                        <div class="col-lg-6 col-xl-5 order-lg-1">
                            <div class="row justify-content-center">
                                <div class="col-md-6 text-center mb-5" data-aos="fade-up">
                                    <!--Numbers card-->
                                    <div class="position-relative pb-2 rounded-4 overflow-hidden bg-body">
                                        <!--Numbers card bg-->
                                        <div
                                            class="position-absolute start-50 translate-middle-x bottom-0 bg-primary rounded-4 w-75 h-75">
                                        </div>
                                        <div class="position-relative bg-body-tertiary p-4 rounded-4">
                                            <h2 class="display-6" data-aos
                                                data-countup='{"startVal":0,"suffix":"+","duration":"5"}' data-to="700"
                                                data-aos-id="countup:in">0</h2>
                                            <h6 class="mb-0">Members</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-center mb-5" data-aos="fade-up" data-aos-delay="100">
                                    <!--Numbers card-->
                                    <div class="position-relative pb-2 rounded-4 overflow-hidden bg-body">
                                        <!--Numbers card bg-->
                                        <div
                                            class="position-absolute start-50 translate-middle-x bottom-0 bg-danger rounded-4 w-75 h-75">
                                        </div>
                                        <div class="position-relative bg-body-tertiary p-4 rounded-4">
                                            <h2 class="display-6" data-aos
                                                data-countup='{"startVal":0,"suffix":"+","duration":"5"}' data-to="30"
                                                data-aos-id="countup:in">0</h2>
                                            <h6 class="mb-0">Monthly Events</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5 mb-md-0 text-center" data-aos="fade-up" data-aos-delay="150">
                                    <!--Numbers card-->
                                    <div class="position-relative pb-2 rounded-4 overflow-hidden bg-body">
                                        <!--Numbers card bg-->
                                        <div
                                            class="position-absolute start-50 translate-middle-x bottom-0 bg-warning rounded-4 w-75 h-75">
                                        </div>
                                        <div class="position-relative bg-body-tertiary p-4 rounded-4">
                                            <h2 class="display-6" data-aos
                                                data-countup='{"startVal":0,"suffix":"+","duration":"5"}' data-to="15"
                                                data-aos-id="countup:in">0</h2>
                                            <h6 class="mb-0">Timezones Represented</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-center" data-aos="fade-up">
                                    <!--Numbers card-->
                                    <div class="position-relative pb-2 rounded-4 overflow-hidden bg-body"
                                        data-aos-delay="200">
                                        <!--Numbers card bg-->
                                        <div
                                            class="position-absolute start-50 translate-middle-x bottom-0 bg-success rounded-4 w-75 h-75">
                                        </div>
                                        <div class="position-relative bg-body-tertiary p-4 rounded-4">
                                            <h2 class="display-6" data-aos
                                                data-countup='{"startVal":0,"suffix":"+","duration":"5"}' data-to="500"
                                                data-aos-id="countup:in">0</h2>
                                            <h6 class="mb-0">Successful Missions</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
</div>
                </div>
            </section>
            <!--end:about section-->


            <!--begin:join section-->
            <section class="position-relative">
                <div class="container position-relative py-9 py-lg-11">
                    <div class="row justify-content-between align-items-center" id='apply'>
                        <div class="col-lg-6 mb-5 mb-lg-0 order-lg-last" data-aos="fade-up" data-aos-delay="50">
                            <div class="position-relative p-2">
                                <!--background-parallax-shape-->
                                <div class="rellax bg-danger width-9x height-9x rounded-circle position-absolute end-0 top-0"
                                    data-rellax-percentage="0.95" data-rellax-speed="2"></div>
                                <!--background-parallax-shape-->
                                <div class="rellax bg-danger width-5x height-5x rounded-circle position-absolute bottom-0 mb-5 start-0"
                                    data-rellax-percentage="0.1" data-rellax-speed="-1"></div>

                                <!--Shape Image with mask-->
                                <div class="bg-mask">
                                    <img src="assets/img/maninmoon.png" class="mask-squircle mask-image" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 order-lg-1 position-relative" data-aos="fade-up" data-aos-delay="100">
                            <!--Heading-->
                            <h2 class="mb-4 display-6">Join <span class="text-gradient">The Red Legion</span>!</h2>

                                <!--Text-->
                            <p class="mb-5">
                                The Red Legion is a thriving Star Citizen organization with hundreds of members 
                                worldwide, active across nearly every time zone. Whether you’re into industry—salvage, 
                                trade, mining, logistics, or medical—or prefer combat and security with Taskforce Red, 
                                there’s a place for you here. We bring strong “Space Dads” vibes: mature, drama-free, 
                                and welcoming to anyone who wants to relax, fly with good people, and build something 
                                lasting. Whatever your experience level, you’ll always have backup when you fly with 
                                The Red Legion.
                            </p>
                            <!--Feature-card-->
                            <div class="d-flex mb-4">
                                <div class="me-3 mt-1">
                                    <i class="bi bi-globe fs-4"></i>
                                </div>
                                <div>
                                    <p class="mb-0">
                                        Global membership across most time zones—easy to find someone to squad up with.
                                    </p>
                                </div>
                            </div>
                            <!--Feature-card-->
                            <div class="d-flex mb-4">
                                <div class="me-3 mt-1">
                                    <i class="bi bi-arrow-left-right fs-4"></i>
                                </div>
                                <div>
                                    <p class="mb-0">
                                        Multi-path progression: Whether you want to specialize in a single career 
                                        loop or dabble across many, we support your journey.
                                    </p>
                                </div>
                            </div>
                            <!--Feature-card-->
                            <div class="d-flex">
                                <div class="me-3 mt-1">
                                    <i class="bi bi-calendar-check fs-4"></i>
                                </div>
                                <div>
                                    <p class="mb-0">
                                        Organized events: Regularly scheduled ops, plus spontaneous “drop-in” 
                                        sessions so you can join at your own pace.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                        <br>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="/Apply" class="btn btn-primary btn-lg me-2 me-lg-3">Join The Red Legion Today!</a>
                        </div>
                </div>
            </section>
            <!--end:join section-->


            <!--begin:partner section-->
            <section class="overflow-hidden bg-body position-relative" id='Partner'>
                <div class="container position-relative py-9 py-lg-11">
                    <h2 class="display-6 text-center mb-5">Be a <span class="text-gradient">Red Legion</span> Partner</h2>
                    <!--feature icons row-->
                    <div class="row justify-content-center">
                        <p class="mb-5" style="max-width: 800px;">
                                Looking for more than just a crew? The Red Legion offers a full suite of professional services 
                                across the ‘verse. With hundreds of active members, proven expertise, and reliable operations,
                                we’re the ally you need to keep your business moving forward.  When you partner with The Red 
                                Legion, you’re gaining a dependable force committed to professionalism, accountability, and results.
                            </p>
                        <div class="col-md-6 text-center mb-4" data-aos="fade-up" data-aos-delay="50">
                            <!--Icon card-->
                            <div class="card card-body py-5 px-4 border-0 shadow-lg hover-lift hover-shadow-xl">
                                <!--Feature icon-->
                            <div class="mb-4 mx-auto width-10x height-10x flex-center position-relative">
                                <!--Icon bg-->
                                <svg class="position-absolute text-primary start-0 top-0 w-100 h-100"
                                    preserveAspectRatio="none" width="120" height="120" viewBox="0 0 120 120"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M55.4078 1.90215C58.3481 0.684225 61.6519 0.684225 64.5922 1.90215L97.8342 15.6714C100.775 16.8894 103.111 19.2255 104.329 22.1658L118.098 55.4078C119.316 58.3481 119.316 61.6519 118.098 64.5922L104.329 97.8342C103.111 100.775 100.775 103.111 97.8342 104.329L64.5922 118.098C61.6519 119.316 58.3481 119.316 55.4078 118.098L22.1658 104.329C19.2255 103.111 16.8894 100.775 15.6714 97.8342L1.90215 64.5922C0.684225 61.6519 0.684225 58.3481 1.90215 55.4078L15.6714 22.1658C16.8894 19.2255 19.2255 16.8894 22.1658 15.6714L55.4078 1.90215Z"
                                        fill="currentColor" />
                                </svg>
                                <!--Icon-->
                                <i class="bi bi-gem fs-1 text-white position-relative"></i>
                            </div>
                            <div class="d-flex align-items-center mb-3 justify-content-center">
                                <h5 class="mb-0">Resource Supply</h5>
                            </div>
                            <p class="mb-0 w-lg-75 mx-auto">
                                From raw ore to refined minerals, we can source and deliver the materials you need, when you need them.
                            </p>
                            </div>
                        </div>
                        <div class="col-md-6 text-center mb-4" data-aos="fade-up" data-aos-delay="100">
                            <div class="card card-body py-5 px-4 border-0 shadow-lg hover-lift hover-shadow-xl">
                                <!--Feature icon-->
                            <div class="mb-4 mx-auto width-10x height-10x flex-center position-relative">
                                <!--Icon bg-->
                                <svg class="position-absolute text-primary start-0 top-0 w-100 h-100"
                                    preserveAspectRatio="none" width="120" height="120" viewBox="0 0 120 120"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M55.4078 1.90215C58.3481 0.684225 61.6519 0.684225 64.5922 1.90215L97.8342 15.6714C100.775 16.8894 103.111 19.2255 104.329 22.1658L118.098 55.4078C119.316 58.3481 119.316 61.6519 118.098 64.5922L104.329 97.8342C103.111 100.775 100.775 103.111 97.8342 104.329L64.5922 118.098C61.6519 119.316 58.3481 119.316 55.4078 118.098L22.1658 104.329C19.2255 103.111 16.8894 100.775 15.6714 97.8342L1.90215 64.5922C0.684225 61.6519 0.684225 58.3481 1.90215 55.4078L15.6714 22.1658C16.8894 19.2255 19.2255 16.8894 22.1658 15.6714L55.4078 1.90215Z"
                                        fill="currentColor" />
                                </svg>
                                <!--Icon-->
                                <i class="bi bi-truck text-white fs-2 position-relative"></i>
                            </div>

                            <!--Feature Text-->
                            <div class="d-flex align-items-center mb-3 justify-content-center">
                                <h5 class="mb-0">Hauling &amp; Logistics</h5>
                            </div>
                            <p class="mb-0 w-lg-75 mx-auto">
                                Whether it’s a single run or long-term contracts, we move cargo with precision and efficiency.
                            </p>
                            </div>
                        </div>
                        <div class="col-md-6 text-center mb-4" data-aos="fade-up" data-aos-delay="150">

                            <div class="card card-body py-5 px-4 border-0 shadow-lg hover-lift hover-shadow-xl">
                                <!--Feature icon-->
                            <div class="mb-4 mx-auto width-10x height-10x flex-center position-relative">
                                <!--Icon bg-->
                                <svg class="position-absolute text-primary start-0 top-0 w-100 h-100"
                                    preserveAspectRatio="none" width="120" height="120" viewBox="0 0 120 120"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M55.4078 1.90215C58.3481 0.684225 61.6519 0.684225 64.5922 1.90215L97.8342 15.6714C100.775 16.8894 103.111 19.2255 104.329 22.1658L118.098 55.4078C119.316 58.3481 119.316 61.6519 118.098 64.5922L104.329 97.8342C103.111 100.775 100.775 103.111 97.8342 104.329L64.5922 118.098C61.6519 119.316 58.3481 119.316 55.4078 118.098L22.1658 104.329C19.2255 103.111 16.8894 100.775 15.6714 97.8342L1.90215 64.5922C0.684225 61.6519 0.684225 58.3481 1.90215 55.4078L15.6714 22.1658C16.8894 19.2255 19.2255 16.8894 22.1658 15.6714L55.4078 1.90215Z"
                                        fill="currentColor" />
                                </svg>
                                <!--Icon-->
                                <i class="bi bi-people text-white fs-1 position-relative"></i>
                            </div>

                            <!--Feature Text-->
                            <div class="d-flex align-items-center mb-3 justify-content-center">
                                <h5 class="mb-0">Consulting &amp; Training</h5>
                            </div>
                            <p class="mb-0 w-lg-75 mx-auto">
                                Tap into our experience with strategy, logistics, and operations to level up your 
                                organization or crew.
                            </p>
                            </div>
                        </div>
                        <div class="col-md-6 text-center mb-4" data-aos="fade-up" data-aos-delay="200">

                            <div class="card card-body py-5 px-4 border-0 shadow-lg hover-lift hover-shadow-xl">
                                <!--Feature icon-->
                            <div class="mb-4 mx-auto width-10x height-10x flex-center position-relative">
                                <!--Icon bg-->
                                <svg class="position-absolute text-primary start-0 top-0 w-100 h-100"
                                    preserveAspectRatio="none" width="120" height="120" viewBox="0 0 120 120"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M55.4078 1.90215C58.3481 0.684225 61.6519 0.684225 64.5922 1.90215L97.8342 15.6714C100.775 16.8894 103.111 19.2255 104.329 22.1658L118.098 55.4078C119.316 58.3481 119.316 61.6519 118.098 64.5922L104.329 97.8342C103.111 100.775 100.775 103.111 97.8342 104.329L64.5922 118.098C61.6519 119.316 58.3481 119.316 55.4078 118.098L22.1658 104.329C19.2255 103.111 16.8894 100.775 15.6714 97.8342L1.90215 64.5922C0.684225 61.6519 0.684225 58.3481 1.90215 55.4078L15.6714 22.1658C16.8894 19.2255 19.2255 16.8894 22.1658 15.6714L55.4078 1.90215Z"
                                        fill="currentColor" />
                                </svg>
                                <!--Icon-->
                                <i class="bi bi-shield-lock text-white fs-1 position-relative"></i>
                            </div>

                            <!--Feature Text-->
                            <div class="d-flex align-items-center mb-3 justify-content-center">
                                <h5 class="mb-0">Security &amp; Escorts</h5>
                            </div>
                            <p class="mb-0 w-lg-75 mx-auto">
                                Keep your assets safe with Taskforce Red providing armed escorts and defensive support.
                            </p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="d-flex align-items-center justify-content-center">
                            <a href="https://discord.gg/RedLegion" target='_blank' class="btn btn-primary btn-lg me-2 me-lg-3">Contact Us on Discord!</a>
                        </div>
                </div>
            </section>
            <!--end:partner section-->

        </main>

        <?php
		//Footer.	
		include __DIR__ . '/../app/partials/footers/footer-9.php';
        ?>

        <!-- begin:back to Top button -->
        <a href="#" class="toTop"><svg class="progress-circle" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
  <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="4" stroke-dasharray="0, 251.2"></circle>
</svg>
            <i class="bi bi-chevron-up"></i>
        </a>
        <!-- end:back to Top button -->


        <!-- begin:main script -->
        <script src="assets/js/theme.bundle.min.js"></script>
        <!-- end:main script -->

    </body>

</html>

