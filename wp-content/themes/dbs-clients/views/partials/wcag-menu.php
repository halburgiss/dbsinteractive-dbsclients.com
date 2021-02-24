<?php
/**
 * The WCAG Menu
 * @author Tyler Akin, 11/20/19
 */
?>
<div class="wcag-panel" id="wcag-panel" title="WCAG Accessibility Controls Panel">
	<button id="wcag-button__close" class="wcag-button wcag-button__close" itemscope itemtype="http://schema.org/ControlAction">
		<span class="assistive" itemprop="description">Close the website accessibility panel: Closes the website accessibility panel</span>
		<span class="assistive" itemprop="instrument">Button</span>
		<span class="assistive" itemprop="result">Closes the accessibility options panel</span>
	</button>
	<div class="wcag-panel-main-controls" aria-hidden="true" itemscope itemtype="http://schema.org/Action">
		<span class="assistive" itemprop="agent">User</span>
		<span class="assistive" itemprop="description">Use these controls to access accessibility options built into this website.</span>
		<h2 class="wcag-panel-title">Accessibility Menu</h2>
		<div class="wcag-panel-control-set font-size-options">
			<strong class="wcag-panel-control-set-title">Adjust Font Size</strong>
			<ul class="wcag-panel-control-set-list font-size-list">
	            <!-- Removed because px are used and not helpful for accessible font scaling. Leaving in here because that functionality should be reinstated once the functionality is not dependent on px. -->
				<li class="wcag-panel-control-set-item" itemscope itemtype="http://schema.org/ControlAction">
					<button class="wcag-button wcag-button__increase-font-size" id="font-increase" aria-label="Font Options: Increase Font Size">
						<span class="assistive" itemprop="description">Changes the website font size: Increases the font size</span>
						<span class="assistive" itemprop="instrument">Button</span>
						<span class="assistive" itemprop="result">Larger Font Size</span>
					</button>
				</li>
				<li class="wcag-panel-control-set-item" itemscope itemtype="http://schema.org/ControlAction">
					<button class="wcag-button wcag-button__decrease-font-size" id="font-decrease" aria-label="Font Options: Decrease Font Size">
						<span class="assistive" itemprop="description">Changes the website font size: Decreases the font size</span>
						<span class="assistive" itemprop="instrument">Button</span>
						<span class="assistive" itemprop="result">Smaller Font Size</span>
					</button>
				</li>
			</ul>
		</div>
		<div class="wcag-panel-control-set contrast-options">
			<strong class="wcag-panel-control-set-title">High Contrast Options</strong>
			<ul class="wcag-panel-control-set-list contrast-list">
				<li class="wcag-panel-control-set-item" itemscope itemtype="http://schema.org/ControlAction">
					<button class="wcag-button wcag-button__aaa-dark-contrast" id="wcag-aaa-dark" aria-label="Contrast Option: Black Background with White Text">
						<span class="assistive" itemprop="description">Changes the website contrast: Black Background with White Text</span>
						<span class="assistive" itemprop="instrument">Button</span>
						<span class="assistive" itemprop="result">Dark Website Theme</span>
					</button>
				</li>
				<li class="wcag-panel-control-set-item" itemscope itemtype="http://schema.org/ControlAction">
					<button class="wcag-button wcag-button__aaa-light-contrast" id="wcag-aaa-light" aria-label="Contrast Option: White Background with Black Text">
						<span class="assistive" itemprop="description">Changes the website contrast: White Background with Black Text</span>
						<span class="assistive" itemprop="instrument">Button</span>
						<span class="assistive" itemprop="result">Light Website Theme</span>
					</button>
				</li>
			</ul>
		</div>

		<div class="wcag-panel-control-set additional-options">
			<ul class="wcag-panel-control-set-list additional-options-list">
					<li class="wcag-panel-control-set-item" itemscope itemtype="http://schema.org/ControlAction">
					<button type="button" name="Disable Stylesheet" id="disable-stylesheet" class="wcag-button wcag-button__disable-stylesheet" aria-label="Disable Stylesheet">Disable <br>Stylesheet
						<span class="assistive" itemprop="description">Disable the styling of the page</span>
						<span class="assistive" itemprop="instrument">Button</span>
					</button>
				</li>

				<li class="wcag-panel-control-set-item" itemscope itemtype="http://schema.org/ControlAction">
					<button id="prevent-animations" class="wcag-button wcag-button__prevent-animations"
							aria-label="Accessibility Options: Prevent Animations">Prevent <br>Animations
						<span class="assistive" itemprop="description">Prevent all unnecessary page animations</span>
						<span class="assistive" itemprop="instrument">Button</span>
					</button>
				</li>
				
			</ul>
		</div>
			
		<div class="wcag-panel-control-set additional-options">
			<ul class="wcag-panel-control-set-list additional-options-list">
				<li class="wcag-panel-control-set-item" itemscope itemtype="http://schema.org/ControlAction">
					<button class="wcag-button wcag-button__reset-accessibility" id="reset-accessibility" aria-label="Accessibility Options: Reset Settings">Reset All Settings
						<span class="assistive" itemprop="description">Reset website accessibility: Set all adjusted website accessibility settings back to default</span>
						<span class="assistive" itemprop="instrument">Button</span>
						<span class="assistive" itemprop="result">Resets accessibility settings back to default</span>
					</button>
				</li>
			</ul>
		</div>
		<div class="wcag-panel-control-set additional-resources">
			<strong class="wcag-panel-control-set-title">Additional Resources</strong>
			<ul class="wcag-panel-control-set-list">
				<li class="wcag-panel-control-set-item"><a href="/accessibility-statement" data-link-text="Accessibility Statement" data-link-cat="Other Link" data-link-location="Header Link">Accessibility Statement</a></li>
				<li class="wcag-panel-control-set-item"><a href="/web-accessibility-glossary/" data-link-text="View Term Glossary" data-link-cat="Other Link" data-link-location="Header Link">View Term Glossary</a></li>
			</ul>
		</div>
	</div>

</div>
