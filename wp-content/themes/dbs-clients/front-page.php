<?php
/**
 * @file front-page.php, the site's "Home" page
 */

namespace Slate;
global $utils;
get_header();

// Flex content
$theme->view( 'views/acf-flex/loop.php', array( 'query' => $wp_query ) );

get_footer();
