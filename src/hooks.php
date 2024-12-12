<?php

return function ($app) {
    $app->hook('csrf.token', function () {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    });
};