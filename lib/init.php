<?php

$deps = array(
    'controller',
    'models',
    'views'
);

foreach( $deps as $dep ) include_once( $dep . '.php' );
