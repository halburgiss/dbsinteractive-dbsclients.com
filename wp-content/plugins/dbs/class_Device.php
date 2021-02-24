<?php
/**
* @file class_Device.php
*
* A simple, low resource means of differentiating from basic desktops and
* mobile devices, using a process of elimination.
*
* @author Hal Burgiss  2010-03-02
*
* See http://smartmobtoolkit.wordpress.com/tag/mobile-detection/
*/
class Device {

	var $is_mobile = false;
	var $is_iphone = false;
	var $is_smart  = false;
	var $is_bot    = false;
	var $is_ie;
	var $ua; 

	function __construct() {
		$this->ua    = $_SERVER['HTTP_USER_AGENT'];
		$this->is_ie = ( strpos($this->ua, 'MSIE') !== false ) ? true : false;

		// if this first group matches, we are a desktop and not a mobile device.
		if (	( strstr( $this->ua, 'Windows') && ! preg_match('/Windows\s+(CE|Phone)/i', $this->ua) ) ||
		     ( preg_match('/OS\s+(X|9)/', $this->ua ) && ! stristr( $this->ua, 'iphone' ) ) ||
			preg_match('/(Linux|Solaris|BSD)/i', $this->ua) && ! stristr( $this->ua, 'android' )  ) {
			// do nothing, we are a Desktop.
		} elseif ( strstr( $this->ua, 'iPhone' ) ) {
			$this->is_smart = true;
			$this->is_mobile = true;
			$this->is_iphone = true;
		} elseif ( preg_match( '/(Android|WebKit|Opera|Firefox|Blackberry|Windows)/i', $this->ua ) ) {
			$this->is_smart = true;
			$this->is_mobile = true;
		} elseif ( preg_match( '/(spider|crawl|slurp|bot)/i', $this->ua ) ) {
			$this->is_bot = true;
		} else {
			// almost surely mobile, and not a "smartphone" with full web browser
			$this->is_mobile = true;
		}
	}
}
