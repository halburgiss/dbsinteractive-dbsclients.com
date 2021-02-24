<?php
/**
* Contains the json-ld schema for this #Organization and other site wide schemas.
*
* NOTE: Phone number is required to be full international format, ie with +1 for
* US.
*/
?>
<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "Organization",
	"name": "<?php echo $dbs->client_name?>",
	"url": "<?php echo $dbs->canonical_url?>",
	"logo": "<?php echo get_template_directory_uri() .	'/library/images/site-logo.png'; ?>"<?php if ( $dbs->client_phone ) : ?>,
	"contactPoint": {
		"@type": "ContactPoint",
		"telephone": "+1-<?php echo $dbs->client_phone; ?>",
		"contactType": "Sales Inquiries",
		"areaServed": "US",
		"availableLanguage": "English"
	}
	<?php /* TODO: add this manually
	"address": {
		"@type": "PostalAddress",
		"streetAddress": "1201 Edison Drive",
		"addressLocality": "Louisville",
		"addressRegion": "KY",
		"postalCode": "40202"
	}*/?>
<?php else: echo "\n"; endif; ?>
}
</script>
