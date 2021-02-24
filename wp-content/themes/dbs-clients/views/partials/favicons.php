<?php
// Latest output per http://realfavicongenerator.net ***
$latestFaviconOutput = "<link rel='apple-touch-icon' sizes='180x180' href='" . get_stylesheet_directory_uri() . "/library/favicons/apple-touch-icon.png'>" . "\n";
$latestFaviconOutput .= "<link rel='icon' type='image/png' href='" . get_stylesheet_directory_uri() . "/library/favicons/favicon-32x32.png' sizes='32x32'>" . "\n";
$latestFaviconOutput .= "<link rel='icon' type='image/png' href='" . get_stylesheet_directory_uri() . "/library/favicons/favicon-16x16.png' sizes='16x16'>" . "\n";
$latestFaviconOutput .= "<link rel='manifest' href='" . get_stylesheet_directory_uri() . "/library/favicons/manifest.json'>" . "\n";
// Commented Out for WCAG2AA Color Compliance. (You can enable this; it just causes a warning, NOT an error.)
$latestFaviconOutput .= "<meta name='theme-color' content='#ffffff'>" . "\n";
echo $latestFaviconOutput;
