<?php
$bootstrap = require __DIR__ . '/../../../app/bootstrap.php';
include __DIR__ . '/../../../app/functions.php';

/**
 * Small, local escape helper to prevent PHP 8.2 warnings
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$applicants = getApplicantsForReview();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../../assets/img/favicon.ico" type="image/ico">
    <link rel="stylesheet" href="../../assets/fonts/bootstrap-icons/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/fonts/iconsmind/iconsmind.css">
    <link rel="stylesheet" href="../../assets/vendor/node_modules/css/aos.css">
    <link rel="stylesheet" href="../../assets/vendor/node_modules/css/bs-stepper.min.css">
    <link rel="stylesheet" href="../../assets/vendor/node_modules/css/prism-tomorrow.css">
    <link href="../../assets/css/theme.min.css" rel="stylesheet">

    <title>The Red Legion – Applicant Review</title>

    <!-- Sticky footer support -->
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }

        /* Offset main content to clear absolute header */
        .content-offset {
            padding-top: 120px; /* desktop header height */
        }

        @media (max-width: 991.98px) {
            .content-offset {
                padding-top: 90px; /* mobile header height */
            }
        }
    </style>
</head>

<body>
<?php include __DIR__ . '/../../../app/partials/preloader.html'; ?>

<header class="z-fixed header-transparent header-absolute-top sticky-reverse">
    <nav class="navbar navbar-expand-lg navbar-light navbar-link-white">
        <div class="container position-relative">
            <a class="navbar-brand" href="/">
                <img src="../../assets/img/logo/logo.png" class="img-fluid navbar-brand-sticky" alt="">
                <img src="../../assets/img/logo/logo.png" class="img-fluid navbar-brand-transparent" alt="">
            </a>

            <div class="d-flex align-items-center navbar-no-collapse-items order-lg-last">
                <button class="navbar-toggler order-last" type="button" data-bs-toggle="collapse"
                        data-bs-target="#mainNavbarTheme">
                    <span class="navbar-toggler-icon"><i></i></span>
                </button>
                <div class="nav-item me-2 d-none d-xl-flex">
                    <a href="/Login" class="btn btn-danger btn-sm rounded-pill">Staff Login</a>
                </div>
            </div>

            <div class="collapse navbar-collapse" id="mainNavbarTheme">
                <?php
                $page = 'apply';
                include __DIR__ . '/../../../app/partials/headers/default-navbar-items.html';
                ?>
            </div>
        </div>
    </nav>
</header>

<main class="content-offset">




</main>

<?php include __DIR__ . '/../../../app/partials/footers/footer-9.php'; ?>

<a href="#" class="toTop">
    <svg class="progress-circle" viewBox="0 0 100 100">
        <circle cx="50" cy="50" r="40" fill="none"
                stroke="currentColor" stroke-width="4"
                stroke-dasharray="0, 251.2"></circle>
    </svg>
    <i class="bi bi-chevron-up"></i>
</a>

<script src="../../assets/js/theme.bundle.min.js"></script>
</body>
</html>
