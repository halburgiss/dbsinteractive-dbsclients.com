 'use strict';
/** These are the default DBS Global variables. This code is required in header.php */
var dbs = { device: {} };
dbs.device.is_mobile = navigator.userAgent.match(/(iPad|iPhone|iPod|Android|Mobile|Opera M|Blackberry|Silk|Kindle)/ig) || typeof window.orientation !== 'undefined' ? true : false;
dbs.device.is_iOS = navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? true : false;
dbs.device.is_phone = navigator.userAgent.match(/(iPhone|Mobile|Opera M|Blackberry)/ig) ? true : false;
dbs.device.is_phone = navigator.userAgent.match(/(iPad|iPod)/ig) ? false : dbs.device.is_phone;
dbs.device.is_IE = navigator.userAgent.match(/(MSIE |Trident\/)/g) ? true : false;
if ( dbs.device.is_mobile ) document.getElementsByTagName( 'html' )[0].className += ' mobile';
if ( dbs.device.is_phone ) document.getElementsByTagName( 'html' )[0].className += ' phone';
dbs.device.is_touch = (('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0) || dbs.device.is_mobile);
dbs.device.is_tablet = dbs.device.is_mobile && ! dbs.device.is_phone;
if(typeof window.console == 'undefined') { window.console = {log: function (msg) {} }; } // for legacy IE
