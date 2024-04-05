<?php

require 'vendor/autoload.php';

$client = new GuzzleHttp\Client();

$redirectUri = urlencode('https://beta.integrations.chasedatacorp.com/oauth2/callback');
$clientId = '3MVG9sG9Z3Q1Rlbcr_PEobEWR8ui0RXxykFsn0vaP0TgkMbhX9SUnbSMgewpAJwzApTQfMxlx1GcCaupgWd2d';
$scope = urlencode('full refresh_token');
$loginUrl = "https://login.salesforce.com/services/oauth2/authorize?response_type=code&client_id=$clientId&redirect_uri=$redirectUri&scope=$scope";

header('Location: ' . $loginUrl);
exit;