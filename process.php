<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $turnstileResponse = $_POST['cf-turnstile-response'];
    $secretKey = 'MY_SECRET_KEY';
    $verifyUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    $postData = http_build_query([
        'secret' => $secretKey,
        'response' => $turnstileResponse,
    ]);

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => $postData,
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($verifyUrl, false, $context);
    $responseData = json_decode($result);

    if ($responseData->success) {
        echo "Turnstile validation succeeded. Form submission processed.";
    } else {
        echo "Turnstile validation failed. Form submission rejected.";
    }
}
?>