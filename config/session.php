<?php
// configurer la sécurité de la session
session_set_cookie_params([
    "lifetime" => 86400,
    "path" => "/",
    "domain" => '',
    "secure" => false,
    "httponly" => true,
    "samesite" => "Lax"
]);

session_start();