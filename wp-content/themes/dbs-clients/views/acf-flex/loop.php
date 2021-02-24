<?php
/**
 * Loop for creating the layout object (and displaying the appropriate
 * template).
 * DBS>Interactive
 */

use \Base\Layout;

$prefix = $this->prefix ?? '';

if ( have_rows( $prefix . 'flex_content' ) ) :
	while ( have_rows( $prefix . 'flex_content' ) ) :  the_row();
		new Layout( get_row_layout() );  // The constructor displays the layout
	endwhile;
endif;
