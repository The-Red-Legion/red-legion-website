<?php
$bootstrap = require __DIR__ . '/../app/bootstrap.php';
include __DIR__ . '/../app/functions.php';
include __DIR__ . '/../app/discord.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Basic existence checks
    if (
        empty($_SESSION['ApplicantID']) ||
        empty($_POST['why_join'])
    ) {
        $_SESSION['error'] = 'Missing application information. Please try again.';
       //header('Location: /SubmitApplication');
        exit;
    }

    // Sanitize and validate textarea input
    $reason = trim($_POST['why_join']);

    // Update applicant record
    try {
        updateApplicantReasonAndStatus(
            $_SESSION['ApplicantID'],
            $reason
        );
    } catch (Throwable $e) {
        $_SESSION['error'] = 'Unable to submit application at this time.';
        //header('Location: Apply.php');
    }

    // Optional success flag
    $_SESSION['success'] = 'Your application has been submitted successfully.';
    header('Location:/Applied');
}