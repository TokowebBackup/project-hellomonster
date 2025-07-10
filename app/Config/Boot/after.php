<?php

// Set locale from session
$session = service('session');
$locale = $session->get('lang') ?? 'en';
service('request')->setLocale($locale);
