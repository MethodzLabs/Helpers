<?php

namespace Zaacom\helpers\mail;

class Mail
{

	private string $fromMail;
	private ?string $fromName = null;
	private array $toMails;
	private string $subject;
	private string $body;
	private ?bool $isHTML = false;

	public function __construct(array $to, string $fromMail, string $subject, string $body)
	{
		$this->toMails = $to;
		$this->fromMail = $fromMail;
		$this->subject = $subject;
		$this->body = $body;
	}

	public function setFromMail(string $fromMail): static
	{
		$this->fromMail = $fromMail;
		return $this;
	}

	public function setFromName(string $fromName): static
	{
		$this->fromName = $fromName;
		return $this;
	}

	public function isHTML(): static
	{
		$this->isHTML = true;
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
}
