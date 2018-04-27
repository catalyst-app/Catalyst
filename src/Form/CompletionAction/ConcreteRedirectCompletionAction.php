<?php

namespace Catalyst\Form\CompletionAction;

/**
 * Redirects to a given URL
 */
class ConcreteRedirectCompletionAction extends AbstractCompletionAction {
	/**
	 * URL to redirect to
	 * 
	 * @var string
	 */
	protected $redirectUrl = '';

	/**
	 * Create a new ConcreteRedirectCompletionAction
	 * 
	 * @param string $redirectUrl The URL to redirect to
	 */
	public function __construct(string $redirectUrl='') {
		$this->setRedirectUrl($redirectUrl);
	}

	/**
	 * Get the redirect URL
	 * 
	 * @return string redirect URL
	 */
	public function getRedirectUrl() : string {
		return $this->redirectUrl;
	}

	/**
	 * Set the redirect URL
	 * 
	 * @param string $redirectUrl New redirect URL
	 */
	public function setRedirectUrl(string $redirectUrl) : void {
		$this->redirectUrl = $redirectUrl;
	}

	/**
	 * Get JavaScript to run on success
	 * 
	 * @return string JS code for success
	 */
	public function getJs() : string {
		$str = '';
		$str .= 'window.log('.json_encode(basename(__CLASS__)).', '.json_encode("redirecting to ".$this->getRedirectUrl()).');';
		$str .= 'window.location = $("html").attr("data-rootdir")+'.json_encode($this->getRedirectUrl()).';';
		return $str;
	}
}

