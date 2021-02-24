<?php
/**
 * Slate search form
 */
namespace Slate;
global $utils;
?>

<div id="search-form" class="search-form">
	<form class="search-form__form" method="get" action="/" itemprop="potentialAction" itemscope itemtype="http://schema.org/SearchAction">
		<meta itemprop="target" content="<?php echo home_url('/'); ?>?s={s}"/>
		<label for="search-input" class="search-form__label assistive">Search Input</label>
		<div class="search-form__container">
			<input id="search-input" class="site_search__field search-form__field" type="text" size="20" value="<?php echo get_search_query(); ?>" name="s" placeholder="Search" data-state="closed" itemprop="query-input" required />
			<button class="search-form__button--search" type="submit"><span class="assistive">Submit search</span></button>
		</div>
	</form>

	<button class="search-form__button--open"><span class="assistive">Open the search input field</span></button>
	<button class="search-form__button--close"><span class="assistive">Close the search input field</span></button>
</div>