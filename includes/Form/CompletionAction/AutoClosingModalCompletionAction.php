<?php

namespace Catalyst\Form\CompletionAction;

/**
 * Represents an action which can be performed based
 */
class AutoClosingModalCompletionAction extends AbstractCompletionAction {
	/**
	 * Prepended to any generated modal's IDs
	 */
	const PREFIX_ID = "form-completion-modal-";

	/**
	 * String to display in modal
	 * 
	 * @var string
	 */
	protected $contents = 'Thank you for submitting this form.';
	/**
	 * Number of seconds to show modal
	 * 
	 * @var int
	 */
	protected $delay = 10;

	/**
	 * Create a new AutoClosingModalCompletionAction
	 * 
	 * @param string $contents What should be displayed to the user on completion
	 * @param int $delay Number of seconds to wait until the modal is closed
	 */
	public function __construct(string $contents='Thank you for submitting this form.', int $delay=10) {
		$this->setContents($contents);
		$this->setDelay($delay);
	}

	/**
	 * Get the modal's contents
	 * 
	 * @return string Modal's contents
	 */
	public function getContents() : string {
		return $this->contents;
	}

	/**
	 * Set the modal's contents
	 * 
	 * @param string $contents New contents to show in modal
	 */
	public function setContents(string $contents) : void {
		$this->contents = $contents;
	}

	/**
	 * Get the delay until the modal closes
	 * 
	 * @return int The delay until the modal closes
	 */
	public function getDelay() : int {
		return $this->delay;
	}

	/**
	 * Set the delay until the modal closes to a new value
	 * 
	 * @param int $delay Number of seconds until the modal closes
	 */
	public function setDelay(int $delay) : void {
		$this->delay = $delay;
	}

	/**
	 * Get JavaScript to run on success
	 * 
	 * @return string JS code for success
	 */
	public function getJs() : string {
		$str = '';
		$str .= 'var modalId = '.json_encode(self::PREFIX_ID).'+Date.now().toString();';
		$str .= '$("body").append(';
			$str .= '$("<div></div>").attr("id", modalId).addClass("modal").append(';
				$str .= '$("<div></div>").addClass("modal-content").append(';
					$str .= '$("<h4></h4>").text('.json_encode($this->getContents()).').add(';
						$str .= '$("<p></p>").text("This box will close in "+'.json_encode($this->getDelay()).'+" seconds.")';
					$str .= ')';
				$str .= ')';
			$str .= ')';
		$str .= ');';
		$str .= 'window.log('.json_encode(__CLASS__).', '.json_encode("created modal ").'+modalId);';
		$str .= 'var modal = M.Modal.init(document.querySelector("#"+modalId));';
		$str .= 'modal.open();';
		$str .= 'setTimeout(';
			$str .= 'function() {';
				$str .= 'window.log('.json_encode(__CLASS__).', '.json_encode("closing modal ").'+modalId);';
				$str .= 'modal.close();';
			$str .= '},';
			$str .= $this->getDelay()*1000;
		$str .= ');';
		return $str;
	}
}

