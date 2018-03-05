<?php

namespace Catalyst;

use \Catalyst\Page\Values;
use \Catalyst\Secrets;
use \PHPMailer\PHPMailer\PHPMailer;

/**
 * Class which makes sending of email easy
 * 
 * @todo make a queue so loading doesn't take forever
 */
class Email {
	public const NO_REPLY_EMAIL = ["no-reply@catalystapp.co", "Catalyst No-Reply"];
	public const NO_REPLY_PASSWORD = Secrets::NO_REPLY_PASSWORD;

	// used for logging errors
	public const ERROR_LOG_EMAIL = ["error_logs@catalystapp.co", "Catalyst Error Logging"];
	public const ERROR_LOG_PASSWORD = Secrets::ERROR_LOG_PASSWORD;

	public const EMAIL_SMTP = ["catalystapp.co", 587, "tls"];

	/**
	 * Get <head> to go with a HTML e-mail
	 * 
	 * @param string $color Color to use
	 * @return string
	 */
	public static function getEmailHeadHtml(string $color=Values::DEFAULT_COLOR) : string {
		$str = "";
		$str .= '<!DOCTYPE html>';
		$str .= '<html>';
		$str .= '<head>';
		$str .= '<style>';
		$str .= self::getCss($color);
		$str .= '</style>';
		$str .= '</head>';
		return $str;
	}

	/**
	 * Get e-mail CSS to go with a color, minified + simplified, not too much left in
	 * 
	 * @param string $color The hex color to use
	 * @return string CSS string
	 */
	public static function getCss(string $color=Values::DEFAULT_COLOR) : string {
		$str = '';

		// MAINTAINERS: these are seperated into module-like sections
		// Place block comments around, one above the // and one below the last $str .= in the block, if you wish to remove a "module"

		// basic html rules
		$str .= 'html{';
			$str .= 'font-family:sans-serif;';
			$str .= '-ms-text-size-adjust:100%;';
			$str .= '-webkit-text-size-adjust:100%;';
			$str .= '-webkit-box-sizing:border-box;';
			$str .= 'box-sizing:border-box;';
		$str .= '}';

		$str .= 'body{';
			$str .= 'margin:0;';
			$str .= 'color:#000;';
		$str .= '}';

		$str .= 'h1,h2,h3,h4,h5,p,*{';
			$str .= 'color:#000;';
		$str .= '}';

		$str .= 'article,aside,details,figcaption,figure,footer,header,hgroup,main,menu,nav,section,summary{';
			$str .= 'display:block;';
		$str .= '}';
		
		$str .= 'h1{';
			$str .= 'font-size:2em;margin:0.67em 0;';
		$str .= '}';

		$str .= '*, *:before, *:after{';
			$str .= '-webkit-box-sizing:inherit;';
			$str .= 'box-sizing:inherit;';
		$str .= '}';

		/*
		// LISTS
		$str .= 'ul:not(.browser-default){';
			$str .= 'padding-left:0;';
			$str .= 'list-style-type:none;';
		$str .= '}';

		$str .= 'ul:not(.browser-default) > li{';
			$str .= 'list-style-type:none;';
		$str .= '}';
		*/

		// CONTAINER
		$str .= '.container{';
			$str .= 'margin:0 auto;';
			$str .= 'max-width:1280px;';
			$str .= 'width:90%;';
		$str .= '}';
		
		$str .= '@media only screen and (min-width:601px){';
			$str .= '.container{';
				$str .= 'width:85%;';
			$str .= '}';
		$str .= '}';

		$str .= '@media only screen and (min-width:993px){';
			$str .= '.container{';
				$str .= 'width:70%;';
			$str .= '}';
		$str .= '}';

		// section
		$str .= '.section{';
			$str .= 'padding-top:1rem;';
			$str .= 'padding-bottom:1rem;';
		$str .= '}';
		
		/*
		// no-pad sections?
		$str .= '.section.no-pad{';
			$str .= 'padding:0;';
		$str .= '}';
		
		$str .= '.section.no-pad-bot{';
			$str .= 'padding-bottom:0;';
		$str .= '}';

		$str .= '.section.no-pad-top{';
			$str .= 'padding-top:0;';
		$str .= '}';
		*/

		// row and column basics
		$str .= '.container .row{';
			$str .= 'margin-left:-0.75rem;';
			$str .= 'margin-right:-0.75rem;';
		$str .= '}';

		$str .= '.row{';
			$str .= 'margin-left:auto;';
			$str .= 'margin-right:auto;';
			$str .= 'margin-bottom:20px;';
		$str .= '}';

		$str .= '.row:after{';
			$str .= 'content:"";';
			$str .= 'display:table;';
			$str .= 'clear:both;';
		$str .= '}';

		$str .= '.row .col{';
			$str .= 'float:left;';
			$str .= '-webkit-box-sizing:border-box;';
			$str .= 'box-sizing:border-box;';
			$str .= 'padding:0 0.75rem;';
			$str .= 'min-height:1px;';
		$str .= '}';

		$str .= '.row .col[class*="push-"], .row .col[class*="pull-"]{';
			$str .= 'position:relative;';
		$str .= '}';

		// small column concrete definitions
		$str .= '.row .col.s1{';
			$str .= 'width:8.3333333333%;';
			$str .= 'margin-left:auto;';
			$str .= 'left:auto;';
			$str .= 'right:auto;';
		$str .= '}';
		$str .= '.row .col.s2{';
			$str .= 'width:16.6666666667%;';
			$str .= 'margin-left:auto;';
			$str .= 'left:auto;';
			$str .= 'right:auto;';
		$str .= '}';
		$str .= '.row .col.s3';
			$str .= '{width:25%;';
			$str .= 'margin-left:auto;';
			$str .= 'left:auto;';
			$str .= 'right:auto;';
		$str .= '}';
		$str .= '.row .col.s4{';
			$str .= 'width:33.3333333333%;';
			$str .= 'margin-left:auto;';
			$str .= 'left:auto;';
			$str .= 'right:auto;';
		$str .= '}';
		$str .= '.row .col.s5{';
			$str .= 'width:41.6666666667%;';
			$str .= 'margin-left:auto;';
			$str .= 'left:auto;';
			$str .= 'right:auto;';
		$str .= '}';
		$str .= '.row .col.s6';
			$str .= '{width:50%;';
			$str .= 'margin-left:auto;';
			$str .= 'left:auto;';
			$str .= 'right:auto;';
		$str .= '}';
		$str .= '.row .col.s7{';
			$str .= 'width:58.3333333333%;';
			$str .= 'margin-left:auto;';
			$str .= 'left:auto;';
			$str .= 'right:auto;';
		$str .= '}';
		$str .= '.row .col.s8{';
			$str .= 'width:66.6666666667%;';
			$str .= 'margin-left:auto;';
			$str .= 'left:auto;';
			$str .= 'right:auto;';
		$str .= '}';
		$str .= '.row .col.s9';
			$str .= '{width:75%;';
			$str .= 'margin-left:auto;';
			$str .= 'left:auto;';
			$str .= 'right:auto;';
		$str .= '}';
		$str .= '.row .col.s10{';
			$str .= 'width:83.3333333333%;';
			$str .= 'margin-left:auto;';
			$str .= 'left:auto;';
			$str .= 'right:auto;';
		$str .= '}';
		$str .= '.row .col.s11{';
			$str .= 'width:91.6666666667%;';
			$str .= 'margin-left:auto;';
			$str .= 'left:auto;';
			$str .= 'right:auto;';
		$str .= '}';
		$str .= '.row .col.s12';
			$str .= '{width:100%;';
			$str .= 'margin-left:auto;';
			$str .= 'left:auto;';
			$str .= 'right:auto;';
		$str .= '}';

		/*
		// small column offset defintions
		$str .= '.row .col.offset-s1{';
			$str .= 'margin-left:8.3333333333%;';
		$str .= '}';
		
		$str .= '.row .col.offset-s2{';
			$str .= 'margin-left:16.6666666667%;';
		$str .= '}';
		
		$str .= '.row .col.offset-s3{';
			$str .= 'margin-left:25%;';
		$str .= '}';
		
		$str .= '.row .col.offset-s4{';
			$str .= 'margin-left:33.3333333333%;';
		$str .= '}';
		
		$str .= '.row .col.offset-s5{';
			$str .= 'margin-left:41.6666666667%;';
		$str .= '}';
		
		$str .= '.row .col.offset-s6{';
			$str .= 'margin-left:50%;';
		$str .= '}';
		
		$str .= '.row .col.offset-s7{';
			$str .= 'margin-left:58.3333333333%;';
		$str .= '}';
		
		$str .= '.row .col.offset-s8{';
			$str .= 'margin-left:66.6666666667%;';
		$str .= '}';
		
		$str .= '.row .col.offset-s9{';
			$str .= 'margin-left:75%;';
		$str .= '}';
		
		$str .= '.row .col.offset-s10{';
			$str .= 'margin-left:83.3333333333%;';
		$str .= '}';
		
		$str .= '.row .col.offset-s11{';
			$str .= 'margin-left:91.6666666667%;';
		$str .= '}';
		
		$str .= '.row .col.offset-s12{';
			$str .= 'margin-left:100%;';
		$str .= '}';
		*/

		/*
		// concrete column push/pull definitions
		$str .= '.row .col.pull-s1{';
			$str .= 'right:8.3333333333%;';
		$str .= '}';
		$str .= '.row .col.push-s1{';
			$str .= 'left:8.3333333333%;';
		$str .= '}';
		$str .= '.row .col.pull-s2{';
			$str .= 'right:16.6666666667%;';
		$str .= '}';
		$str .= '.row .col.push-s2{';
			$str .= 'left:16.6666666667%;';
		$str .= '}';
		$str .= '.row .col.pull-s3{';
			$str .= 'right:25%;';
		$str .= '}';
		$str .= '.row .col.push-s3{';
			$str .= 'left:25%;';
		$str .= '}';
		$str .= '.row .col.pull-s4{';
			$str .= 'right:33.3333333333%;';
		$str .= '}';
		$str .= '.row .col.push-s4{';
			$str .= 'left:33.3333333333%;';
		$str .= '}';
		$str .= '.row .col.pull-s5{';
			$str .= 'right:41.6666666667%;';
		$str .= '}';
		$str .= '.row .col.push-s5{';
			$str .= 'left:41.6666666667%;';
		$str .= '}';
		$str .= '.row .col.pull-s6{';
			$str .= 'right:50%;';
		$str .= '}.row .col.push-s6{';
			$str .= 'left:50%;';
		$str .= '}';
		$str .= '.row .col.pull-s7{';
			$str .= 'right:58.3333333333%;';
		$str .= '}';
		$str .= '.row .col.push-s7{';
			$str .= 'left:58.3333333333%;';
		$str .= '}';
		$str .= '.row .col.pull-s8{';
			$str .= 'right:66.6666666667%;';
		$str .= '}';
		$str .= '.row .col.push-s8{';
			$str .= 'left:66.6666666667%;';
		$str .= '}';
		$str .= '.row .col.pull-s9{';
			$str .= 'right:75%;';
		$str .= '}';
		$str .= '.row .col.push-s9{';
			$str .= 'left:75%;';
		$str .= '}';
		$str .= '.row .col.pull-s10{';
			$str .= 'right:83.3333333333%;';
		$str .= '}';
		$str .= '.row .col.push-s10{';
			$str .= 'left:83.3333333333%;';
		$str .= '}';
		$str .= '.row .col.pull-s11{';
			$str .= 'right:91.6666666667%;';
		$str .= '}';
		$str .= '.row .col.push-s11{';
			$str .= 'left:91.6666666667%;';
		$str .= '}';
		$str .= '.row .col.pull-s12{';
			$str .= 'right:100%;';
		$str .= '}';
		$str .= '.row .col.push-s12{';
			$str .= 'left:100%;';
		$str .= '}';
		*/


		// base medium column defintions
		$str .= '@media only screen and (min-width:601px){';
			$str .= '.row .col.m1{';
				$str .= 'width:8.3333333333%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.m2{';
				$str .= 'width:16.6666666667%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.m3';
				$str .= '{width:25%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.m4{';
				$str .= 'width:33.3333333333%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.m5{';
				$str .= 'width:41.6666666667%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.m6';
				$str .= '{width:50%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.m7{';
				$str .= 'width:58.3333333333%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.m8{';
				$str .= 'width:66.6666666667%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.m9';
				$str .= '{width:75%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.m10{';
				$str .= 'width:83.3333333333%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.m11{';
				$str .= 'width:91.6666666667%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.m12';
				$str .= '{width:100%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';

			/*
			// medium column offset defintions
			$str .= '.row .col.offset-m1{';
				$str .= 'margin-left:8.3333333333%;';
			$str .= '}';

			$str .= '.row .col.offset-m2{';
				$str .= 'margin-left:16.6666666667%;';
			$str .= '}';

			$str .= '.row .col.offset-m3{';
				$str .= 'margin-left:25%;';
			$str .= '}';

			$str .= '.row .col.offset-m4{';
				$str .= 'margin-left:33.3333333333%;';
			$str .= '}';

			$str .= '.row .col.offset-m5{';
				$str .= 'margin-left:41.6666666667%;';
			$str .= '}';

			$str .= '.row .col.offset-m6{';
				$str .= 'margin-left:50%;';
			$str .= '}';

			$str .= '.row .col.offset-m7{';
				$str .= 'margin-left:58.3333333333%;';
			$str .= '}';

			$str .= '.row .col.offset-m8{';
				$str .= 'margin-left:66.6666666667%;';
			$str .= '}';

			$str .= '.row .col.offset-m9{';
				$str .= 'margin-left:75%;';
			$str .= '}';

			$str .= '.row .col.offset-m10{';
				$str .= 'margin-left:83.3333333333%;';
			$str .= '}';

			$str .= '.row .col.offset-m11{';
				$str .= 'margin-left:91.6666666667%;';
			$str .= '}';

			$str .= '.row .col.offset-m12{';
				$str .= 'margin-left:100%;';
			$str .= '}';
			*/

			/*
			// medium push/pull column definitions
			$str .= '.row .col.pull-m1{';
				$str .= 'right:8.3333333333%;';
			$str .= '}';
			$str .= '.row .col.push-m1{';
				$str .= 'left:8.3333333333%;';
			$str .= '}';
			$str .= '.row .col.pull-m2{';
				$str .= 'right:16.6666666667%;';
			$str .= '}';
			$str .= '.row .col.push-m2{';
				$str .= 'left:16.6666666667%;';
			$str .= '}';
			$str .= '.row .col.pull-m3{';
				$str .= 'right:25%;';
			$str .= '}';
			$str .= '.row .col.push-m3{';
				$str .= 'left:25%;';
			$str .= '}';
			$str .= '.row .col.pull-m4{';
				$str .= 'right:33.3333333333%;';
			$str .= '}';
			$str .= '.row .col.push-m4{';
				$str .= 'left:33.3333333333%;';
			$str .= '}';
			$str .= '.row .col.pull-m5{';
				$str .= 'right:41.6666666667%;';
			$str .= '}';
			$str .= '.row .col.push-m5{';
				$str .= 'left:41.6666666667%;';
			$str .= '}';
			$str .= '.row .col.pull-m6{';
				$str .= 'right:50%;';
			$str .= '}';
			$str .= '.row .col.push-m6{';
				$str .= 'left:50%;';
			$str .= '}';
			$str .= '.row .col.pull-m7{';
				$str .= 'right:58.3333333333%;';
			$str .= '}';
			$str .= '.row .col.push-m7{';
				$str .= 'left:58.3333333333%;';
			$str .= '}';
			$str .= '.row .col.pull-m8{';
				$str .= 'right:66.6666666667%;';
			$str .= '}';
			$str .= '.row .col.push-m8{';
				$str .= 'left:66.6666666667%;';
			$str .= '}';
			$str .= '.row .col.pull-m9{';
				$str .= 'right:75%;';
			$str .= '}';
			$str .= '.row .col.push-m9{';
				$str .= 'left:75%;';
			$str .= '}';
			$str .= '.row .col.pull-m10{';
				$str .= 'right:83.3333333333%;';
			$str .= '}';
			$str .= '.row .col.push-m10{';
				$str .= 'left:83.3333333333%;';
			$str .= '}';
			$str .= '.row .col.pull-m11{';
				$str .= 'right:91.6666666667%;';
			$str .= '}';
			$str .= '.row .col.push-m11{';
				$str .= 'left:91.6666666667%;';
			$str .= '}';
			$str .= '.row .col.pull-m12{';
				$str .= 'right:100%;';
			$str .= '}';
			$str .= '.row .col.push-m12{';
				$str .= 'left:100%;';
			$str .= '}';
			*/
		$str .= '}';

		$str .= '@media only screen and (min-width:993px){';
			// base large column defintions
			$str .= '.row .col.l1{';
				$str .= 'width:8.3333333333%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.l2{';
				$str .= 'width:16.6666666667%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.l3';
				$str .= '{width:25%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.l4{';
				$str .= 'width:33.3333333333%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.l5{';
				$str .= 'width:41.6666666667%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.l6';
				$str .= '{width:50%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.l7{';
				$str .= 'width:58.3333333333%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.l8{';
				$str .= 'width:66.6666666667%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.l9';
				$str .= '{width:75%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.l10{';
				$str .= 'width:83.3333333333%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.l11{';
				$str .= 'width:91.6666666667%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.l12';
				$str .= '{width:100%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';

			/*
			// large column offset definitions
			$str .= '.row .col.offset-l1{';
				$str .= 'margin-left:8.3333333333%;';
			$str .= '}';

			$str .= '.row .col.offset-l2{';
				$str .= 'margin-left:16.6666666667%;';
			$str .= '}';

			$str .= '.row .col.offset-l3{';
				$str .= 'margin-left:25%;';
			$str .= '}';

			$str .= '.row .col.offset-l4{';
				$str .= 'margin-left:33.3333333333%;';
			$str .= '}';

			$str .= '.row .col.offset-l5{';
				$str .= 'margin-left:41.6666666667%;';
			$str .= '}';

			$str .= '.row .col.offset-l6{';
				$str .= 'margin-left:50%;';
			$str .= '}';

			$str .= '.row .col.offset-l7{';
				$str .= 'margin-left:58.3333333333%;';
			$str .= '}';

			$str .= '.row .col.offset-l8{';
				$str .= 'margin-left:66.6666666667%;';
			$str .= '}';

			$str .= '.row .col.offset-l9{';
				$str .= 'margin-left:75%;';
			$str .= '}';

			$str .= '.row .col.offset-l10{';
				$str .= 'margin-left:83.3333333333%;';
			$str .= '}';

			$str .= '.row .col.offset-l11{';
				$str .= 'margin-left:91.6666666667%;';
			$str .= '}';

			$str .= '.row .col.offset-l12{';
				$str .= 'margin-left:100%;';
			$str .= '}';
			*/

			/*
			// large column push/pull definitions
			$str .= '.row .col.pull-l1{';
				$str .= 'right:8.3333333333%;';
			$str .= '}';
			$str .= '.row .col.push-l1{';
				$str .= 'left:8.3333333333%;}';
			$str .= '.row .col.pull-l2{';
				$str .= 'right:16.6666666667%;';
			$str .= '}';
			$str .= '.row .col.push-l2{';
				$str .= 'left:16.6666666667%;}';
			$str .= '.row .col.pull-l3{';
				$str .= 'right:25%;';
			$str .= '}';
			$str .= '.row .col.push-l3{';
				$str .= 'left:25%;';
			$str .= '}';
			$str .= '.row .col.pull-l4{';
				$str .= 'right:33.3333333333%;';
			$str .= '}';
			$str .= '.row .col.push-l4{';
				$str .= 'left:33.3333333333%;}';
			$str .= '.row .col.pull-l5{';
				$str .= 'right:41.6666666667%;';
			$str .= '}';
			$str .= '.row .col.push-l5{';
				$str .= 'left:41.6666666667%;}';
			$str .= '.row .col.pull-l6{';
				$str .= 'right:50%;';
			$str .= '}';
			$str .= '.row .col.push-l6{';
				$str .= 'left:50%;';
			$str .= '}';
			$str .= '.row .col.pull-l7{';
				$str .= 'right:58.3333333333%;';
			$str .= '}';
			$str .= '.row .col.push-l7{';
				$str .= 'left:58.3333333333%;}';
			$str .= '.row .col.pull-l8{';
				$str .= 'right:66.6666666667%;';
			$str .= '}';
			$str .= '.row .col.push-l8{';
				$str .= 'left:66.6666666667%;}';
			$str .= '.row .col.pull-l9{';
				$str .= 'right:75%;';
			$str .= '}';
			$str .= '.row .col.push-l9{';
				$str .= 'left:75%;';
			$str .= '}';
			$str .= '.row .col.pull-l10{';
				$str .= 'right:83.3333333333%;';
			$str .= '}';
			$str .= '.row .col.push-l10{';
				$str .= 'left:83.3333333333%;}';
			$str .= '.row .col.pull-l11{';
				$str .= 'right:91.6666666667%;';
			$str .= '}';
			$str .= '.row .col.push-l11{';
				$str .= 'left:91.6666666667%;}';
			$str .= '.row .col.pull-l12{';
				$str .= 'right:100%;';
			$str .= '}';
			$str .= '.row .col.push-l12{';
				$str .= 'left:100%;';
			$str .= '}';
			*/
		$str .= '}';

		/*
		$str .= '@media only screen and (min-width:1201px){';
			// extra large column definitions
			$str .= '.row .col.xl1{';
				$str .= 'width:8.3333333333%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.xl2{';
				$str .= 'width:16.6666666667%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.xl3';
				$str .= '{width:25%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.xl4{';
				$str .= 'width:33.3333333333%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.xl5{';
				$str .= 'width:41.6666666667%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.xl6';
				$str .= '{width:50%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.xl7{';
				$str .= 'width:58.3333333333%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.xl8{';
				$str .= 'width:66.6666666667%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.xl9';
				$str .= '{width:75%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.xl10{';
				$str .= 'width:83.3333333333%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.xl11{';
				$str .= 'width:91.6666666667%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';
			$str .= '.row .col.xl12';
				$str .= '{width:100%;';
				$str .= 'margin-left:auto;';
				$str .= 'left:auto;';
				$str .= 'right:auto;';
			$str .= '}';

			// extra large column offset definitions
			$str .= '.row .col.offset-xl1{';
				$str .= 'margin-left:8.3333333333%;';
			$str .= '}';

			$str .= '.row .col.offset-xl2{';
				$str .= 'margin-left:16.6666666667%;';
			$str .= '}';

			$str .= '.row .col.offset-xl3{';
				$str .= 'margin-left:25%;';
			$str .= '}';

			$str .= '.row .col.offset-xl4{';
				$str .= 'margin-left:33.3333333333%;';
			$str .= '}';

			$str .= '.row .col.offset-xl5{';
				$str .= 'margin-left:41.6666666667%;';
			$str .= '}';

			$str .= '.row .col.offset-xl6{';
				$str .= 'margin-left:50%;';
			$str .= '}';

			$str .= '.row .col.offset-xl7{';
				$str .= 'margin-left:58.3333333333%;';
			$str .= '}';

			$str .= '.row .col.offset-xl8{';
				$str .= 'margin-left:66.6666666667%;';
			$str .= '}';

			$str .= '.row .col.offset-xl9{';
				$str .= 'margin-left:75%;';
			$str .= '}';

			$str .= '.row .col.offset-xl10{';
				$str .= 'margin-left:83.3333333333%;';
			$str .= '}';

			$str .= '.row .col.offset-xl11{';
				$str .= 'margin-left:91.6666666667%;';
			$str .= '}';

			$str .= '.row .col.offset-xl12{';
				$str .= 'margin-left:100%;';
			$str .= '}';

			// extra large column push/pull definitions
			$str .= '.row .col.pull-xl1{';
				$str .= 'right:8.3333333333%;';
			$str .= '}';
			$str .= '.row .col.push-xl1{';
				$str .= 'left:8.3333333333%;';
			$str .= '}';
			$str .= '.row .col.pull-xl2{';
				$str .= 'right:16.6666666667%;';
			$str .= '}';
			$str .= '.row .col.push-xl2{';
				$str .= 'left:16.6666666667%;';
			$str .= '}';
			$str .= '.row .col.pull-xl3{';
				$str .= 'right:25%;';
			$str .= '}';
			$str .= '.row .col.push-xl3{';
				$str .= 'left:25%;';
			$str .= '}';
			$str .= '.row .col.pull-xl4{';
				$str .= 'right:33.3333333333%;';
			$str .= '}';
			$str .= '.row .col.push-xl4{';
				$str .= 'left:33.3333333333%;';
			$str .= '}';
			$str .= '.row .col.pull-xl5{';
				$str .= 'right:41.6666666667%;';
			$str .= '}';
			$str .= '.row .col.push-xl5{';
				$str .= 'left:41.6666666667%;';
			$str .= '}';
			$str .= '.row .col.pull-xl6{';
				$str .= 'right:50%;';
			$str .= '}';
			$str .= '.row .col.push-xl6{';
				$str .= 'left:50%;';
			$str .= '}';
			$str .= '.row .col.pull-xl7{';
				$str .= 'right:58.3333333333%;';
			$str .= '}';
			$str .= '.row .col.push-xl7{';
				$str .= 'left:58.3333333333%;';
			$str .= '}';
			$str .= '.row .col.pull-xl8{';
				$str .= 'right:66.6666666667%;';
			$str .= '}';
			$str .= '.row .col.push-xl8{';
				$str .= 'left:66.6666666667%;';
			$str .= '}';
			$str .= '.row .col.pull-xl9{';
				$str .= 'right:75%;';
			$str .= '}';
			$str .= '.row .col.push-xl9{';
				$str .= 'left:75%;';
			$str .= '}';
			$str .= '.row .col.pull-xl10{';
				$str .= 'right:83.3333333333%;';
			$str .= '}';
			$str .= '.row .col.push-xl10{';
				$str .= 'left:83.3333333333%;';
			$str .= '}';
			$str .= '.row .col.pull-xl11{';
				$str .= 'right:91.6666666667%;';
			$str .= '}';
			$str .= '.row .col.push-xl11{';
				$str .= 'left:91.6666666667%;';
			$str .= '}';
			$str .= '.row .col.pull-xl12{';
				$str .= 'right:100%;';
			$str .= '}';
			$str .= '.row .col.push-xl12{';
				$str .= 'left:100%;';
			$str .= '}';
		$str .= '}';
		*/

		// basic font stuff
		$str .= 'html{';
			$str .= 'line-height:1.5;';
			$str .= 'font-family:"Roboto", "Helvetica", "Arial", sans-serif;';
			$str .= 'font-weight:normal;';
			$str .= 'color:rgba(0, 0, 0, 0.87);';
		$str .= '}';

		// base font sizes
		$str .= '@media only screen and (min-width:0){';
			$str .= 'html{';
				$str .= 'font-size:14px;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:992px){';
			$str .= 'html{';
				$str .= 'font-size:14.5px;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:1200px){';
			$str .= 'html{';
				$str .= 'font-size:15px;';
			$str .= '}';
		$str .= '}';

		// heading font sizes
		$str .= 'h1, h2, h3, h4, h5, h6{';
			$str .= 'font-weight:400;';
			$str .= 'line-height:1.1;';
		$str .= '}';
		
		$str .= 'h1 a, h2 a, h3 a, h4 a, h5 a, h6 a{';
			$str .= 'font-weight:inherit;';
		$str .= '}';

		$str .= 'h1{';
			$str .= 'font-size:4.2rem;';
			$str .= 'line-height:110%;';
			$str .= 'margin:2.1rem 0 1.68rem 0;';
		$str .= '}';
		
		$str .= 'h2{';
			$str .= 'font-size:3.56rem;';
			$str .= 'line-height:110%;';
			$str .= 'margin:1.78rem 0 1.424rem 0;';
		$str .= '}';
		
		$str .= 'h3{';
			$str .= 'font-size:2.92rem;';
			$str .= 'line-height:110%;';
			$str .= 'margin:1.46rem 0 1.168rem 0;';
		$str .= '}';

		$str .= 'h4{';
			$str .= 'font-size:2.28rem;';
			$str .= 'line-height:110%;';
			$str .= 'margin:1.14rem 0 0.912rem 0;';
		$str .= '}';

		$str .= 'h5{';
			$str .= 'font-size:1.64rem;';
			$str .= 'line-height:110%;';
			$str .= 'margin:0.82rem 0 0.656rem 0;';
		$str .= '}';

		$str .= 'h6{';
			$str .= 'font-size:1rem;';
			$str .= 'line-height:110%;';
			$str .= 'margin:0.5rem 0 0.4rem 0;';
		$str .= '}';

		// basic font elements (em, strong, etc)
		$str .= 'em{';
			$str .= 'font-style:italic;';
		$str .= '}';
		$str .= 'strong{';
			$str .= 'font-weight:500;';
		$str .= '}';
		$str .= 'small{';
			$str .= 'font-size:75%;';
		$str .= '}';

		// font thinkness
		$str .= '.light, .page-footer .footer-copyright{';
			$str .= 'font-weight:300;';
		$str .= '}';
		$str .= '.thin{';
			$str .= 'font-weight:200;';
		$str .= '}';

		// flow-text
		$str .= '.flow-text{';
			$str .= 'font-weight:300;';
		$str .= '}';

		$str .= '@media only screen and (min-width:360px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.2rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:390px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.224rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:420px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.248rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:450px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.272rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:480px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.296rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:510px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.32rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:540px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.344rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:570px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.368rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:600px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.392rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:630px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.416rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:660px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.44rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:690px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.464rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:720px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.488rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:750px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.512rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:780px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.536rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:810px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.56rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:840px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.584rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:870px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.608rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:900px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.632rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:930px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.656rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (min-width:960px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.68rem;';
			$str .= '}';
		$str .= '}';
		$str .= '@media only screen and (max-width:360px){';
			$str .= '.flow-text{';
				$str .= 'font-size:1.2rem;';
			$str .= '}';
		$str .= '}';

		// button styles
		$str .= '.btn, .btn-large,.btn-flat{';
			$str .= 'border:none;';
			$str .= 'border-radius:2px;';
			$str .= 'display:inline-block;';
			$str .= 'height:36px;';
			$str .= 'line-height:36px;';
			$str .= 'padding:0 2rem;';
			$str .= 'text-transform:uppercase;';
			$str .= 'vertical-align:middle;';
			$str .= '-webkit-tap-highlight-color:transparent;';
		$str .= '}';
		$str .= '.btn.disabled, .disabled.btn-large,.btn-floating.disabled,.btn-large.disabled,.btn-flat.disabled,.btn:disabled,.btn-large:disabled,.btn-floating:disabled,.btn-large:disabled,.btn-flat:disabled,.btn[disabled],[disabled].btn-large,.btn-floating[disabled],.btn-large[disabled],.btn-flat[disabled]{';
			$str .= 'pointer-events:none;';
			$str .= 'background-color:#DFDFDF !important;';
			$str .= '-webkit-box-shadow:none;';
			$str .= 'box-shadow:none;';
			$str .= 'color:#9F9F9F !important;';
			$str .= 'cursor:default;';
		$str .= '}';
		$str .= '.btn.disabled:hover, .disabled.btn-large:hover,.btn-floating.disabled:hover,.btn-large.disabled:hover,.btn-flat.disabled:hover,.btn:disabled:hover,.btn-large:disabled:hover,.btn-floating:disabled:hover,.btn-large:disabled:hover,.btn-flat:disabled:hover,.btn[disabled]:hover,[disabled].btn-large:hover,.btn-floating[disabled]:hover,.btn-large[disabled]:hover,.btn-flat[disabled]:hover{';
			$str .= 'background-color:#DFDFDF !important;';
			$str .= 'color:#9F9F9F !important;';
		$str .= '}';
		$str .= '.btn, .btn-large,.btn-floating,.btn-large,.btn-flat{';
			$str .= 'font-size:1rem;';
			$str .= 'outline:0;';
		$str .= '}';
		$str .= '.btn i, .btn-large i,.btn-floating i,.btn-large i,.btn-flat i{';
			$str .= 'font-size:1.3rem;';
			$str .= 'line-height:inherit;';
		$str .= '}';
		$str .= '.btn, .btn-large{';
			$str .= 'text-decoration:none;';
			$str .= 'color:#fff;';
			$str .= 'text-align:center;';
			$str .= 'letter-spacing:.5px;';
			$str .= '-webkit-transition:.2s ease-out;';
			$str .= 'transition:.2s ease-out;';
			$str .= 'cursor:pointer;';
		$str .= '}';
		$str .= '.btn:hover, .btn-large:hover{';
			$str .= 'background-color:#2bbbad;';
		$str .= '}';

		$str .= '.btn-flat{';
			$str .= '-webkit-box-shadow:none;';
			$str .= 'box-shadow:none;';
			$str .= 'background-color:transparent;';
			$str .= 'color:#343434;';
			$str .= 'cursor:pointer;';
			$str .= '-webkit-transition:background-color .2s;';
			$str .= 'transition:background-color .2s;';
		$str .= '}';
		$str .= '.btn-flat:focus, .btn-flat:hover{';
			$str .= '-webkit-box-shadow:none;';
			$str .= 'box-shadow:none;';
		$str .= '}';
		$str .= '.btn-flat:focus{';
			$str .= 'background-color:rgba(0, 0, 0, 0.1);';
		$str .= '}';
		$str .= '.btn-flat.disabled{';
			$str .= 'background-color:transparent !important;';
			$str .= 'color:#b3b2b2 !important;';
			$str .= 'cursor:default;';
		$str .= '}';
		$str .= '.btn-large{';
			$str .= 'height:54px;';
			$str .= 'line-height:54px;';
		$str .= '}';
		$str .= '.btn-large i{';
			$str .= 'font-size:1.6rem;';
		$str .= '}';
		$str .= '.btn-block{';
			$str .= 'display:block;';
		$str .= '}';
		/*
		// FABs
		$str .= '.fixed-action-btn{';
			$str .= 'position:fixed;';
			$str .= 'right:23px;';
			$str .= 'bottom:23px;';
			$str .= 'padding-top:15px;';
			$str .= 'margin-bottom:0;';
			$str .= 'z-index:997;';
		$str .= '}';
		$str .= '.fixed-action-btn.active ul{';
			$str .= 'visibility:visible;';
		$str .= '}';
		$str .= '.fixed-action-btn.horizontal{';
			$str .= 'padding:0 0 0 15px;';
		$str .= '}';
		$str .= '.fixed-action-btn.horizontal ul{';
			$str .= 'text-align:right;';
			$str .= 'right:64px;';
			$str .= 'top:50%;';
			$str .= '-webkit-transform:translateY(-50%);';
			$str .= 'transform:translateY(-50%);';
			$str .= 'height:100%;';
			$str .= 'left:auto;';
			$str .= 'width:500px;';
		$str .= '}';
		$str .= '.fixed-action-btn.horizontal ul li{';
			$str .= 'display:inline-block;';
			$str .= 'margin:15px 15px 0 0';
		$str .= '}';

		$str .= ".fixed-action-btn.toolbar{';
			$str .= 'padding:0;';
			$str .= 'height:56px;';
		$str .= '}';
		$str .= '.fixed-action-btn.toolbar.active > a i{';
			$str .= 'opacity:0;';
		$str .= '}';
		$str .= '.fixed-action-btn.toolbar ul{';
			$str .= 'display:-webkit-box;';
			$str .= 'display:-webkit-flex;';
			$str .= 'display:-ms-flexbox;';
			$str .= 'display:flex;';
			$str .= 'top:0;';
			$str .= 'bottom:0;';
			$str .= 'z-index:1;';
		$str .= '}';
		$str .= '.fixed-action-btn.toolbar ul li{';
			$str .= '-webkit-box-flex:1;';
			$str .= '-webkit-flex:1;';
			$str .= '-ms-flex:1;';
			$str .= 'flex:1;';
			$str .= 'display:inline-block;';
			$str .= 'margin:0;';
			$str .= 'height:100%;';
			$str .= '-webkit-transition:none;';
			$str .= 'transition:none;';
		$str .= '}';
		$str .= '.fixed-action-btn.toolbar ul li a{';
			$str .= 'display:block;';
			$str .= 'overflow:hidden;';
			$str .= 'position:relative;';
			$str .= 'width:100%;';
			$str .= 'height:100%;';
			$str .= 'background-color:transparent;';
			$str .= '-webkit-box-shadow:none;';
			$str .= 'box-shadow:none;';
			$str .= 'color:#fff;';
			$str .= 'line-height:56px;';
			$str .= 'z-index:1;';
		$str .= '}';
		$str .= '.fixed-action-btn.toolbar ul li a i{';
			$str .= 'line-height:inherit;';
		$str .= '}';
		$str .= '.fixed-action-btn ul{';
			$str .= 'left:0;';
			$str .= 'right:0;';
			$str .= 'text-align:center;';
			$str .= 'position:absolute;';
			$str .= 'bottom:64px;';
			$str .= 'margin:0;';
			$str .= 'visibility:hidden;';
		$str .= '}';
		$str .= '.fixed-action-btn ul li{';
			$str .= 'margin-bottom:15px;';
		$str .= '}';
		$str .= '.fixed-action-btn ul a.btn-floating{';
			$str .= 'opacity:0;';
		$str .= '}';
		$str .= '.fixed-action-btn .fab-backdrop{';
			$str .= 'position:absolute;';
			$str .= 'top:0;';
			$str .= 'left:0;';
			$str .= 'z-index:-1;';
			$str .= 'width:40px;';
			$str .= 'height:40px;';
			$str .= 'border-radius:50%;';
			$str .= '-webkit-transform:scale(0);';
			$str .= 'transform:scale(0);';
		$str .= '}';
		$str .= '.btn-floating{';
			$str .= 'display:inline-block;';
			$str .= 'color:#fff;';
			$str .= 'position:relative;';
			$str .= 'overflow:hidden;';
			$str .= 'z-index:1;';
			$str .= 'width:40px;';
			$str .= 'height:40px;';
			$str .= 'line-height:40px;';
			$str .= 'padding:0;';
			$str .= 'border-radius:50%;';
			$str .= '-webkit-transition:.3s;';
			$str .= 'transition:.3s;';
			$str .= 'cursor:pointer;';
			$str .= 'vertical-align:middle;';
		$str .= '}';
		$str .= '.btn-floating:before{';
			$str .= 'border-radius:0;';
		$str .= '}';
		$str .= '.btn-floating.btn-large{';
			$str .= 'width:56px;';
			$str .= 'height:56px;';
		$str .= '}';
		$str .= '.btn-floating.btn-large.halfway-fab{';
			$str .= 'bottom:-28px;';
		$str .= '}';
		$str .= '.btn-floating.btn-large i{';
			$str .= 'line-height:56px;';
		$str .= '}';
		$str .= '.btn-floating.halfway-fab{';
			$str .= 'position:absolute;';
			$str .= 'right:24px;';
			$str .= 'bottom:-20px;';
		$str .= '}';
		$str .= '.btn-floating.halfway-fab.left{';
			$str .= 'right:auto;';
			$str .= 'left:24px;';
		$str .= '}';
		$str .= '.btn-floating i{';
			$str .= 'width:inherit;';
			$str .= 'display:inline-block;';
			$str .= 'text-align:center;';
			$str .= 'color:#fff;';
			$str .= 'font-size:1.6rem;';
			$str .= 'line-height:40px;';
		$str .= '}';
		$str .= 'button.btn-floating{';
			$str .= 'border:none;';
		$str .= '}';
		*/
		
		// show-on and hide-on and stuff
		$str .= '@media only screen and (max-width: 600px) {';
			$str .= '.hide-on-small-only, .hide-on-small-and-down {';
				$str .= 'display: none !important;';
			$str .= '}';
		$str .= '}';

		$str .= '@media only screen and (max-width: 992px) {';
			$str .= '.hide-on-med-and-down {';
				$str .= 'display: none !important;';
			$str .= '}';
		$str .= '}';

		$str .= '@media only screen and (min-width: 601px) {';
			$str .= '.hide-on-med-and-up {';
				$str .= 'display: none !important;';
			$str .= '}';
		$str .= '}';

		$str .= '@media only screen and (min-width: 600px) and (max-width: 992px) {';
			$str .= '.hide-on-med-only {';
				$str .= 'display: none !important;';
			$str .= '}';
		$str .= '}';

		$str .= '@media only screen and (min-width: 993px) {';
			$str .= '.hide-on-large-only {';
				$str .= 'display: none !important;';
			$str .= '}';
		$str .= '}';

		$str .= '@media only screen and (min-width: 993px) {';
			$str .= '.show-on-large {';
				$str .= 'display: block !important;';
			$str .= '}';
		$str .= '}';

		$str .= '@media only screen and (min-width: 600px) and (max-width: 992px) {';
			$str .= '.show-on-medium {';
				$str .= 'display: block !important;';
			$str .= '}';
		$str .= '}';

		$str .= '@media only screen and (max-width: 600px) {';
			$str .= '.show-on-small {';
				$str .= 'display: block !important;';
			$str .= '}';
		$str .= '}';

		$str .= '@media only screen and (min-width: 601px) {';
			$str .= '.show-on-medium-and-up {';
				$str .= 'display: block !important;';
			$str .= '}';
		$str .= '}';

		$str .= '@media only screen and (max-width: 992px) {';
			$str .= '.show-on-medium-and-down {';
				$str .= 'display: block !important;';
			$str .= '}';
		$str .= '}';

		// generic links
		$str .= 'a:hover {';
			$str .= 'text-decoration: underline;';
		$str .= '}';

		$str .= 'a {';
			$str .= 'font-weight: bolder;';
		$str .= '}';

		$str .= 'header a, a.btn {';
			$str .= 'font-weight: initial;';
		$str .= '}';

		// C O L O R S (colors)
		$str .= 'a {';
			$str .= 'color: #'.$color.';';
		$str .= '}';

		$str .= '.side-nav > li > a:hover, .side-nav > li > ul li:hover {';
			$str .= 'background-color: #'.Color::lightenHex($color, 0.5).'33;';
		$str .= '}';

		$str .= '.side-nav > li.active > a, .side-nav > li.active > ul {';
			$str .= 'background-color: #'.Color::lightenHex($color, 0.5).'55;';
		$str .= '}';
		
		$str .= '.switch label input[type=checkbox]:checked + .lever {';
			$str .= 'background-color: #'.Color::lightenHex($color, 1.5).';';
		$str .= '}';
		$str .= '.switch label .lever::before {';
			$str .= 'background-color: rgba('.implode(",", Color::getRGB($color)).', 0.15) !important;';
		$str .= '}';
		$str .= '.user-color, .btn, .btn-large {';
			$str .= 'background-color: #'.$color.';';
		$str .= '}';
		$str .= '.dropdown-content li:not(.disabled) > a, .dropdown-content li:not(.disabled) > span {';
			$str .= 'color: #'.$color.' !important;';
		$str .= '}';
		$str .= '[type="checkbox"]:checked + label:before {';
			$str .= 'border-right: 2px solid #'.$color.';';
			$str .= 'border-bottom: 2px solid #'.$color.';';
		$str .= '}';
		$str .= '[type="checkbox"]:indeterminate + label:before {';
			$str .= 'border-right: 2px solid #'.$color.';';
		$str .= '}';
		$str .= '[type="checkbox"].filled-in:checked + label:after {';
			$str .= 'border: 2px solid #'.$color.';';
		$str .= '}';
		$str .= '[type="checkbox"].filled-in.tabbed:checked:focus + label:after {';
			$str .= 'border-color: #'.$color.';';
		$str .= '}';
		$str .= 'input:not([type]):focus:not([readonly]),input[type=text]:not(.browser-default):focus:not([readonly]),';
		$str .= 'input[type=password]:not(.browser-default):focus:not([readonly]),';
		$str .= 'input[type=email]:not(.browser-default):focus:not([readonly]),';
		$str .= 'input[type=url]:not(.browser-default):focus:not([readonly]),';
		$str .= 'input[type=time]:not(.browser-default):focus:not([readonly]),';
		$str .= 'input[type=date]:not(.browser-default):focus:not([readonly]),';
		$str .= 'input[type=datetime]:not(.browser-default):focus:not([readonly]),';
		$str .= 'input[type=datetime-local]:not(.browser-default):focus:not([readonly]),';
		$str .= 'input[type=tel]:not(.browser-default):focus:not([readonly]),';
		$str .= 'input[type=number]:not(.browser-default):focus:not([readonly]),';
		$str .= 'input[type=search]:not(.browser-default):focus:not([readonly]),';
		$str .= 'textarea.materialize-textarea:focus:not([readonly]) {';
			$str .= 'border-bottom: 1px solid #'.$color.';';
			$str .= '-webkit-box-shadow: 0 1px 0 0 #'.$color.';';
			$str .= 'box-shadow: 0 1px 0 0 #'.$color.';';
		$str .= '}';
		$str .= 'input:not([type]):focus:not([readonly]) + label,';
		$str .= 'input[type=text]:not(.browser-default):focus:not([readonly]) + label,';
		$str .= 'input[type=password]:not(.browser-default):focus:not([readonly]) + label,';
		$str .= 'input[type=email]:not(.browser-default):focus:not([readonly]) + label,';
		$str .= 'input[type=url]:not(.browser-default):focus:not([readonly]) + label,';
		$str .= 'input[type=time]:not(.browser-default):focus:not([readonly]) + label,';
		$str .= 'input[type=date]:not(.browser-default):focus:not([readonly]) + label,';
		$str .= 'input[type=datetime]:not(.browser-default):focus:not([readonly]) + label,';
		$str .= 'input[type=datetime-local]:not(.browser-default):focus:not([readonly]) + label,';
		$str .= 'input[type=tel]:not(.browser-default):focus:not([readonly]) + label,';
		$str .= 'input[type=number]:not(.browser-default):focus:not([readonly]) + label,';
		$str .= 'input[type=search]:not(.browser-default):focus:not([readonly]) + label,';
		$str .= 'textarea.materialize-textarea:focus:not([readonly]) + label {';
			$str .= 'color: #'.$color.';';
		$str .= '}';
		$str .= '.progress {';
			$str .= 'background-color: #'.Color::lightenHex($color, 2).';';
		$str .= '}';
		$str .= '.btn:hover, .btn:focus, .btn-large:hover, .btn-large:focus {';
			$str .= 'background-color: #'.Color::lightenHexByPercent($color, 5).';';
		$str .= '}';
		$str .= 'blockquote {';
			$str .= 'border-left: 5px solid #'.$color.';';
		$str .= '}';


		return $str;
	}

	/**
	 * Send an e-mail
	 * 
	 * @param string[][] $recipients A 2D array of recipients, each being [name, email]
	 * @param string $subject Email subject
	 * @param string $message HTML message
	 * @param string $textMessage Message in text format for older clients/viewers
	 * @param array $email E-mail to send from, [username, name]
	 * @param string $pass Password for the email
	 * @param mixed[] $smtp STMP settings, [host,port,protocol]
	 */
	public static function sendEmail(array $recipients, string $subject, string $message, string $textMessage, array $email, string $pass, array $smtp=self::EMAIL_SMTP) : void {
		$mail = new PHPMailer(false);
		$mail->SMTPDebug = 0;
		$mail->isSMTP();
		$mail->Host = $smtp[0];
		$mail->SMTPAuth = true;
		$mail->Username = $email[0];
		$mail->Password = $pass;
		$mail->SMTPSecure = $smtp[2];
		$mail->Port = $smtp[1];

		$mail->setFrom(...$email);
		foreach ($recipients as $to) {
			$mail->addAddress(...$to);
		}

		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $message;
		$mail->AltBody = $textMessage;

		$mail->send();
	}
}
