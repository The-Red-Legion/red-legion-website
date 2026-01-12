<?php
$bootstrap = require __DIR__ . '/../app/bootstrap.php';
include __DIR__ . '/../app/functions.php';
include __DIR__ . '/../app/discord.php';

//Get the RSI Username submitted.
$rsiUsername = trim($_POST['rsi_username']) ?? null;

//Get the ApplicationID from the session.
$applicantId = $_SESSION['ApplicantID'] ?? null;

//Get the token from the session.
$token = 'TRL:' . getRSIAuthTokenByApplicantId($applicantId);

if (!$rsiUsername || !$applicantId || !$token) 
{
    header("Location:/RSIConfim");
    exit;
}

//Build the URL to scrape.
$rsiUsername = trim($_POST['rsi_username']);
$url = 'https://robertsspaceindustries.com/en/citizens/' . rawurlencode($rsiUsername);

$token = 'TRL:' . getRSIAuthTokenByApplicantId($applicantId);


if (validateRSIProfile($rsiUsername, $token)) 
{
    // SUCCESS
    markRSIConfirmed($applicantId, $rsiUsername);
    $_SESSION['ApplicationStep'] = 3;
    header("Location:/SubmitApplication");
    exit;
} 
else 
{
    $_SESSION['error'] = "Validation code not found in RSI Profile. Please check the instructions and try again.";
    header("Location:/RSIConfirm");
    exit;
}
?>