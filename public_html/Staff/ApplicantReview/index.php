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


<div class="container-fluid">
    <h2 class="mb-4">Applicant Review</h2>

    <table class="table table-dark table-hover align-middle">
        <thead>
        <tr>
            <th>Applicant</th>
            <th>RSI</th>
            <th>Status</th>
            <th>Red Flags</th>
            <th>Applied</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php if (empty($applicants)): ?>
            <tr>
                <td colspan="6" class="text-center text-muted">
                    No applications to review.
                </td>
            </tr>
        <?php endif; ?>

        <?php foreach ($applicants as $a): ?>
            <tr>

                <!-- Applicant -->
                <td>
                    <?php
                    $avatarUrl = discordAvatarUrl(
                        $a['DiscordID'] ?? null,
                        $a['DiscordAvatar'] ?? null,
                        64
                    );
                    ?>
                    <?php if ($avatarUrl): ?>
                        <img src="<?= e($avatarUrl) ?>"
                             class="rounded-circle me-2"
                             width="40"
                             height="40"
                             alt="Discord Avatar">
                    <?php endif; ?>

                    <?= e($a['DiscordUsername'] ?? 'Unknown User') ?>
                </td>

                <!-- RSI (FIXED) -->
                <td>
                    <?php if (!empty($a['RSIUsername'])): ?>
                        <a href="https://robertsspaceindustries.com/citizens/<?= e($a['RSIUsername']) ?>"
                           target="_blank"
                           class="text-info">
                            <?= e($a['RSIUsername']) ?>
                        </a>

                        <?php if (($a['RSIConfirmed'] ?? 'N') !== 'Y'): ?>
                            <span class="badge bg-warning ms-1">Unconfirmed</span>
                        <?php endif; ?>

                    <?php else: ?>
                        <span class="text-muted">Not provided</span>
                    <?php endif; ?>
                </td>

                <!-- Status -->
                <td>
                    <span class="badge bg-<?= statusBadge($a['Status'] ?? '') ?>">
                        <?= e($a['Status'] ?? 'Unknown') ?>
                    </span>
                </td>

                <!-- Red Flags -->
                <td>
                    <?= renderRedFlags($a) ?>
                </td>

                <!-- Applied Date -->
                <td>
                    <?= !empty($a['CreateDate'])
                        ? date('M d, Y', strtotime($a['CreateDate']))
                        : '—'; ?>
                </td>

                <!-- Actions -->
                <td>
                    <a href="ReviewApplicant?id=<?= (int)$a['ApplicantID'] ?>"
                       class="btn btn-sm btn-outline-light">
                        Review
                    </a>
                </td>

            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
</div>

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
