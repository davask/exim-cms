<?php

$ips = array(
    'ErlangenKirchenstarsse92ndfloor' => '188.193.99.247',
    'ErlangenKirchenstarsse91stfloor' => '91.15.227.117',
    'VanvesPruvot74stfloor' => '80.187.96.93',
    'lcddHome' => '128.79.15.169',
);

$authorizedIps = array('127.0.0.1', 'fe80::1', '::1');
foreach ($ips as $name => $ip) {
    $authorizedIps[] = $ip;
}
