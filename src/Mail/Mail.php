<?php

namespace Mail;

class Mail
{
	private string $fromMail;
	private ?string $fromName = null;
	private array $toMails;
	private string $subject;
	private string $body;
	private bool $isHTML;

	private function __construct(array $to, string $fromMail, string $subject, string $body, bool $isHTML = false)
	{
		$this->toMails = $to;
		$this->fromMail = $fromMail;
		$this->subject = $subject;
		$this->body = $body;
		$this->isHTML = $isHTML;
	}

	public function setFromMail(string $fromMail): self
	{
		$this->fromMail = $fromMail;
		return $this;
	}

	public function setFromName(string $fromName): self
	{
		$this->fromName = $fromName;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function send(): bool
	{
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'X-Mailer: PHP/' . phpversion();
		if ($this->isHTML == true) {
			$headers[] = 'Content-type: text/html; charset=UTF-8';
		}
		$headers[] = 'To: ' . implode(' , ', $this->toMails);
		$headers[] = 'From:' . ($this->fromName != null ? $this->fromName . ' ' : '') . '<' . $this->fromMail . '>';

		return mail(implode(' , ', $this->toMails), $this->subject, $this->body, implode("\r\n", $headers));
	}


	public static function create(array $to, string $fromMail, string $subject, string $body, bool $isHTML = false): self
	{
		return new self($to, $fromMail, $subject, $body, $isHTML);
	}
}
