<?php
$bootstrap = require __DIR__ . '/../app/bootstrap.php';
include __DIR__ . '/../app/functions.php';
include __DIR__ . '/../app/discord.php';

$rsiUsername = trim($_POST['rsi_username']) ?? null;

if (!$rsiUsername) 
{
    header("Location:/RSIConfim");
    exit;
}

//Build the URL to scrape.
$rsiUsername = trim($_POST['rsi_username']);
$url = 'https://robertsspaceindustries.com/en/citizens/' . rawurlencode($rsiUsername);

//Get the ApplicationID from the session.
$applicantId = $_SESSION['ApplicantID'] ?? null;

if (!$rsiUsername || !$applicantId) 
{
        // handle error
        //HANDLE THIS.
}

$token = 'TRL:' . getRSIAuthTokenByApplicantId($applicantId);

if (!$token) 
{
    // handle missing token
    //HANDLE THIS.
}

if (validateRSIProfile($rsiUsername, $token)) 
{
    // SUCCESS
    markRSIConfirmed($applicantId, $rsiUsername);
    header("Location:/Application");
    exit;
} 
else 
{
    // FAIL
    //HANDLE THIS.
    // show message: "Token not found in RSI profile"
    echo "No Joy";
}
?>