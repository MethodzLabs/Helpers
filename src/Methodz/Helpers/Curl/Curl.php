<?php

namespace Methodz\Helpers\Curl;


use Methodz\Helpers\Curl\Exception\CurlExecuteException;

class Curl
{
	private \CurlHandle $curlHandle;
	private null|string $result;
	private string $url;
	private ?array $infos;
	private ?array $data;
	private array $header;

	private function __construct(string $url)
	{
		$this->curlHandle = curl_init($url);
		$this->result = null;
		$this->url = null;
		$this->infos = [];
		$this->data = null;
		$this->header = [
			CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.4 Safari/605.1.15',
			CURLOPT_RETURNTRANSFER => true,
		];
	}

	public static function init(string $url): static
	{
		return new self($url);
	}

	public function setHeader(array $header): static
	{
		$this->header = $header;

		return $this;
	}

	public function addHeader(int $key, mixed $value): static
	{
		$this->header[$key] = $value;

		return $this;
	}

	public function setHtaccessUsernameAndPassword(string $username, string $password): static
	{
		$this->addHeader(CURLOPT_USERPWD, "$username:$password");

		return $this;
	}

	public function setRequestAsPost(bool $bool = true): static
	{
		$this->addHeader(CURLOPT_POST, $bool);

		return $this;
	}

	public function setPOSTParameters(array $data): static
	{
		$this->data = $data;

		return $this;
	}

	public function addPOSTParameters(string $key, mixed $value): static
	{
		$this->data[$key] = $value;

		return $this;
	}

	public function setGETParameters(array $data): static
	{
		$this->url = Url::from($this->url)->setParameters($data)->build();
		$this->addHeader(CURLOPT_URL, $this->url);

		return $this;
	}

	public function addGETParameters(string $key, mixed $value): static
	{
		$this->url = Url::from($this->url)->addParameters($key, $value)->build();
		$this->addHeader(CURLOPT_URL, $this->url);

		return $this;
	}

	/**
	 * @return $this
	 * @throws CurlExecuteException
	 */
	public function exec(): static
	{
		if ($this->data !== null) {
			$this->setRequestAsPost();
			$this->addHeader(CURLOPT_POSTFIELDS, $this->data);
		}
		curl_setopt_array($this->curlHandle, $this->header);
		$this->result = curl_exec($this->curlHandle);
		if ($this->result === false) {
			throw new CurlExecuteException($this->getErrorString());
		}

		return $this;
	}

	public function getInfos(): array
	{
		$this->infos = curl_getinfo($this->curlHandle);

		return $this->infos;
	}

	public function getInfo(int $option)
	{
		return curl_getinfo($this->curlHandle, $option);
	}

	public function getResult(): string
	{
		if ($this->result === null) {
			$this->exec();
		}
		return $this->result;
	}

	public function getErrorString(): string
	{
		return "Curl error " . $this->getErrorNumber() . " (" . $this->getError()->name . "): \"" . curl_error($this->curlHandle) . "\"";
	}

	public function getError(): CurlErrorEnum
	{
		return CurlErrorEnum::getError($this->getErrorNumber());
	}

	public function getErrorNumber(): int
	{
		return curl_errno($this->curlHandle);
	}

	public function close(): static
	{
		curl_close($this->curlHandle);

		return $this;
	}
}