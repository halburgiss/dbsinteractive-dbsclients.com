<?php

$modalId = get_sub_field( 'modal_id' );
$image_gallery_callout = get_sub_field( 'modal' );
$modalButtonName = get_sub_field( 'button_name' );

?>

<a class="button modal__open" title="Open" <?php if ($modalId) { ?> href="<?php echo $modalId; ?>" <?php } ?>>
	<?php if ($modalButtonName) {
			echo $modalButtonName;
		} else {
			echo "Learn More";
		} ?>
</a>
<div class="modal hide" <?php if ($modalId) { ?> id="<?php echo $modalId; ?>" <?php } ?>>
	<div class="modal__box">
		<div class="modal__box--content">

		<?php echo $image_gallery_callout; ?>
		</div>
		<a class="modal__button" title="Close" href="#close" data-action="close"></a>
		
	</div>
</div>
