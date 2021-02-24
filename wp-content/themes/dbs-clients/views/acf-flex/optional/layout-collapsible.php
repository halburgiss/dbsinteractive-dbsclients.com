<?php
/**
 * Repeater Field Template: Collapsible Layout - Optional
 * This template is used to make a collapsible layout that is useful for hiding and showing content
 * This also includes the option for a colored image background.
 *
 * @author Tyler Akin 2017 - DBS>Interactive
 * 		template based on a layout by @author Mark Shelton
 */

$layout = $this->layout;

?>
<div class="layout layout-collapsible <?php $layout->the_layout_classes; ?>">
	<div class="contain">
		<?php $layout->the_header; ?>
		<?php if ( have_rows( 'row' ) ) : while ( have_rows( 'row' ) ) : the_row(); ?>
			<div class="collapsible-row">
				<button class="collapsible-row__header"><?php $layout->the_row_header; ?></button>
				<div class="collapsible-row__content--wrapper">
					<div class="collapsible-row__content">
						<?php $layout->the_row_content; ?>
					</div>
				</div>
			</div>
		<?php endwhile; endif; ?>
	</div>
</div>

<script>
// Hides collapsible content only if JS loads
var collapsibleContents = document.querySelectorAll('.collapsible-row__content--wrapper');
collapsibleContents.forEach(function(content) {
	content.classList.add('loaded')
});

// Click event for 'expanded' class toggle
var collapsibleToggles = document.querySelectorAll('.collapsible-row__header');
collapsibleToggles.forEach(function(toggle) {
	toggle.addEventListener('click', function() {
		this.parentElement.classList.toggle('expanded');
	});
});
</script>
