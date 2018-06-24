<?php

namespace Catalyst\Email;

use \Ddeboer\Imap\Server;
use \Exception;
use \PHPMailer\PHPMailer\PHPMailer;

/**
 * Wrapper for PHPMailer which provides S/MIME support
 */
class Mailer extends PHPMailer {


	/**
	 * Assemble the message body.
	 * Returns an empty string on failure.
	 *
	 * LARGELY COPIED FROM PHPMailer WITH ADAPTATIONS FOR S/MIME SIGINING
	 *
	 * @throws Exception
	 * @return string The assembled message body
	 */
	public function createBody() : string {
		$body = '';
		//Create unique IDs and preset boundaries
		$this->uniqueid = $this->generateId();
		$this->boundary[1] = 'b1_' . $this->uniqueid;
		$this->boundary[2] = 'b2_' . $this->uniqueid;
		$this->boundary[3] = 'b3_' . $this->uniqueid;

		if ($this->sign_key_file) {
			$body .= $this->getMailMIME() . static::$LE;
		}

		$this->setWordWrap();

		$bodyEncoding = $this->Encoding;
		$bodyCharSet = $this->CharSet;
		//Can we do a 7-bit downgrade?
		if ('8bit' == $bodyEncoding and !$this->has8bitChars($this->Body)) {
			$bodyEncoding = '7bit';
			//All ISO 8859, Windows codepage and UTF-8 charsets are ascii compatible up to 7-bit
			$bodyCharSet = 'us-ascii';
		}
		//If lines are too long, and we're not already using an encoding that will shorten them,
		//change to quoted-printable transfer encoding for the body part only
		if ('base64' != $this->Encoding and static::hasLineLongerThanMax($this->Body)) {
			$bodyEncoding = 'quoted-printable';
		}

		$altBodyEncoding = $this->Encoding;
		$altBodyCharSet = $this->CharSet;
		//Can we do a 7-bit downgrade?
		if ('8bit' == $altBodyEncoding and !$this->has8bitChars($this->AltBody)) {
			$altBodyEncoding = '7bit';
			//All ISO 8859, Windows codepage and UTF-8 charsets are ascii compatible up to 7-bit
			$altBodyCharSet = 'us-ascii';
		}
		//If lines are too long, and we're not already using an encoding that will shorten them,
		//change to quoted-printable transfer encoding for the alt body part only
		if ('base64' != $altBodyEncoding and static::hasLineLongerThanMax($this->AltBody)) {
			$altBodyEncoding = 'quoted-printable';
		}
		//Use this as a preamble in all multipart message types
		$mimepre = 'This is a multi-part message in MIME format.' . static::$LE;
		switch ($this->message_type) {
			case 'inline':
				$body .= $mimepre;
				$body .= $this->getBoundary($this->boundary[1], $bodyCharSet, '', $bodyEncoding);
				$body .= $this->encodeString($this->Body, $bodyEncoding);
				$body .= static::$LE;
				$body .= $this->attachAll('inline', $this->boundary[1]);
				break;
			case 'attach':
				$body .= $mimepre;
				$body .= $this->getBoundary($this->boundary[1], $bodyCharSet, '', $bodyEncoding);
				$body .= $this->encodeString($this->Body, $bodyEncoding);
				$body .= static::$LE;
				$body .= $this->attachAll('attachment', $this->boundary[1]);
				break;
			case 'inline_attach':
				$body .= $mimepre;
				$body .= $this->textLine('--' . $this->boundary[1]);
				$body .= $this->headerLine('Content-Type', 'multipart/related;');
				$body .= $this->textLine("\tboundary=\"" . $this->boundary[2] . '"');
				$body .= static::$LE;
				$body .= $this->getBoundary($this->boundary[2], $bodyCharSet, '', $bodyEncoding);
				$body .= $this->encodeString($this->Body, $bodyEncoding);
				$body .= static::$LE;
				$body .= $this->attachAll('inline', $this->boundary[2]);
				$body .= static::$LE;
				$body .= $this->attachAll('attachment', $this->boundary[1]);
				break;
			case 'alt':
				$body .= $mimepre;
				$body .= $this->getBoundary($this->boundary[1], $altBodyCharSet, 'text/plain', $altBodyEncoding);
				$body .= $this->encodeString($this->AltBody, $altBodyEncoding);
				$body .= static::$LE;
				$body .= $this->getBoundary($this->boundary[1], $bodyCharSet, 'text/html', $bodyEncoding);
				$body .= $this->encodeString($this->Body, $bodyEncoding);
				$body .= static::$LE;
				if (!empty($this->Ical)) {
					$body .= $this->getBoundary($this->boundary[1], '', 'text/calendar; method=REQUEST', '');
					$body .= $this->encodeString($this->Ical, $this->Encoding);
					$body .= static::$LE;
				}
				$body .= $this->endBoundary($this->boundary[1]);
				break;
			case 'alt_inline':
				$body .= $mimepre;
				$body .= $this->getBoundary($this->boundary[1], $altBodyCharSet, 'text/plain', $altBodyEncoding);
				$body .= $this->encodeString($this->AltBody, $altBodyEncoding);
				$body .= static::$LE;
				$body .= $this->textLine('--' . $this->boundary[1]);
				$body .= $this->headerLine('Content-Type', 'multipart/related;');
				$body .= $this->textLine("\tboundary=\"" . $this->boundary[2] . '"');
				$body .= static::$LE;
				$body .= $this->getBoundary($this->boundary[2], $bodyCharSet, 'text/html', $bodyEncoding);
				$body .= $this->encodeString($this->Body, $bodyEncoding);
				$body .= static::$LE;
				$body .= $this->attachAll('inline', $this->boundary[2]);
				$body .= static::$LE;
				$body .= $this->endBoundary($this->boundary[1]);
				break;
			case 'alt_attach':
				$body .= $mimepre;
				$body .= $this->textLine('--' . $this->boundary[1]);
				$body .= $this->headerLine('Content-Type', 'multipart/alternative;');
				$body .= $this->textLine("\tboundary=\"" . $this->boundary[2] . '"');
				$body .= static::$LE;
				$body .= $this->getBoundary($this->boundary[2], $altBodyCharSet, 'text/plain', $altBodyEncoding);
				$body .= $this->encodeString($this->AltBody, $altBodyEncoding);
				$body .= static::$LE;
				$body .= $this->getBoundary($this->boundary[2], $bodyCharSet, 'text/html', $bodyEncoding);
				$body .= $this->encodeString($this->Body, $bodyEncoding);
				$body .= static::$LE;
				if (!empty($this->Ical)) {
					$body .= $this->getBoundary($this->boundary[2], '', 'text/calendar; method=REQUEST', '');
					$body .= $this->encodeString($this->Ical, $this->Encoding);
				}
				$body .= $this->endBoundary($this->boundary[2]);
				$body .= static::$LE;
				$body .= $this->attachAll('attachment', $this->boundary[1]);
				break;
			case 'alt_inline_attach':
				$body .= $mimepre;
				$body .= $this->textLine('--' . $this->boundary[1]);
				$body .= $this->headerLine('Content-Type', 'multipart/alternative;');
				$body .= $this->textLine("\tboundary=\"" . $this->boundary[2] . '"');
				$body .= static::$LE;
				$body .= $this->getBoundary($this->boundary[2], $altBodyCharSet, 'text/plain', $altBodyEncoding);
				$body .= $this->encodeString($this->AltBody, $altBodyEncoding);
				$body .= static::$LE;
				$body .= $this->textLine('--' . $this->boundary[2]);
				$body .= $this->headerLine('Content-Type', 'multipart/related;');
				$body .= $this->textLine("\tboundary=\"" . $this->boundary[3] . '"');
				$body .= static::$LE;
				$body .= $this->getBoundary($this->boundary[3], $bodyCharSet, 'text/html', $bodyEncoding);
				$body .= $this->encodeString($this->Body, $bodyEncoding);
				$body .= static::$LE;
				$body .= $this->attachAll('inline', $this->boundary[3]);
				$body .= static::$LE;
				$body .= $this->endBoundary($this->boundary[2]);
				$body .= static::$LE;
				$body .= $this->attachAll('attachment', $this->boundary[1]);
				break;
			default:
				// Catch case 'plain' and case '', applies to simple `text/plain` and `text/html` body content types
				//Reset the `Encoding` property in case we changed it for line length reasons
				$this->Encoding = $bodyEncoding;
				$body .= $this->encodeString($this->Body, $this->Encoding);
				break;
		}

		if ($this->isError()) {
			$body = '';
			if ($this->exceptions) {
				throw new Exception($this->lang('empty_message'), self::STOP_CRITICAL);
			}
		} elseif ($this->sign_key_file) {
			try {
				if (!defined('PKCS7_TEXT')) {
					throw new Exception($this->lang('extension_missing') . 'openssl');
				}
				// @TODO would be nice to use php://temp streams here
				$file = tempnam(sys_get_temp_dir(), 'mail');
				if (false === file_put_contents($file, $body)) {
					throw new Exception($this->lang('signing') . ' Could not write temp file');
				}
				$signed = tempnam(sys_get_temp_dir(), 'signed');
				//Workaround for PHP bug https://bugs.php.net/bug.php?id=69197
				if (empty($this->sign_extracerts_file)) {
					$sign = @openssl_pkcs7_sign(
						$file,
						$signed,
						file_get_contents($this->sign_cert_file),
						[file_get_contents($this->sign_key_file), $this->sign_key_pass],
						[]
					);
				} else {
					$sign = @openssl_pkcs7_sign(
						$file,
						$signed,
						file_get_contents($this->sign_cert_file),
						[file_get_contents($this->sign_key_file), $this->sign_key_pass],
						[],
						PKCS7_DETACHED,
						$this->sign_extracerts_file
					);
				}
				@unlink($file);
				if ($sign) {
					$body = file_get_contents($signed);
					@unlink($signed);
					//The message returned by openssl contains both headers and body, so need to split them up
					$parts = explode("\n\n", $body, 2);
					$this->MIMEHeader .= $parts[0] . static::$LE . static::$LE;
					$body = $parts[1];
				} else {
					@unlink($signed);
					throw new Exception($this->lang('signing') . openssl_error_string());
				}
			} catch (Exception $exc) {
				$body = '';
				if ($this->exceptions) {
					throw $exc;
				}
			}
		}

		return $body;
	}

	/**
	 * Save email to a folder
	 *
	 * @param string $folderPath Where to save the mail
	 * @param bool $markAsRead Whether or not to mark the mail as read
	 */
    public function copyToFolder(string $folderPath="Sent", bool $markAsRead=true) : void {
        $message = $this->MIMEHeader.$this->MIMEBody;

        $server = new Server($this->Host);
        $connection = $server->authenticate($this->Username, $this->Password);

        $mailbox = $connection->getMailbox($folderPath);
        
        $mailbox->addMessage($message, $markAsRead ? '\\Seen' : null);
    }
}
