<?php

namespace Methodz\Helpers\Curl;


use CurlHandle;
use Methodz\Helpers\Curl\Exception\CurlExecuteException;

class Curl
{
	private CurlHandle $curlHandle;
	private null|string $result;
	private string $url;
	private array $infos;
	private ?array $data;
	private array $header;
	private array $options;

	private function __construct(string $url)
	{
		$this->curlHandle = curl_init($url);
		$this->result = null;
		$this->url = $url;
		$this->infos = [];
		$this->data = null;
		$this->options = [];
		$this->addOption(CURLOPT_RETURNTRANSFER, true);
		$this->header = [];
		$this->addHeader(CurlCommonHeaderKeyEnum::USER_AGENT, "'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.4 Safari/605.1.15'");
	}

	public function setHeader(array $header): self
	{
		$this->header = $header;

		return $this;
	}

	public function addHeader(CurlCommonHeaderKeyEnum|string $key, mixed $value): self
	{
		if (!is_string($key)) {
			$key = $key->value;
		}
		$this->header[$key] = $value;

		return $this;
	}

	public function setHtaccessUsernameAndPassword(string $username, string $password): self
	{
		$this->addOption(CURLOPT_USERPWD, "$username:$password");

		return $this;
	}

	public function setRequestAsPost(bool $bool = true): self
	{
		$this->addOption(CURLOPT_POST, $bool);

		return $this;
	}

	public function setPOSTParameters(array $data): self
	{
		$this->data = $data;

		return $this;
	}

	public function addPOSTParameters(string $key, mixed $value): self
	{
		$this->data[$key] = $value;

		return $this;
	}

	public function setGETParameters(array $data): self
	{
		$this->url = Url::from($this->url)->setParameters($data)->build();
		$this->addOption(CURLOPT_URL, $this->url);

		return $this;
	}

	public function addGETParameters(string $key, mixed $value): self
	{
		$this->url = Url::from($this->url)->addParameters($key, $value)->build();
		$this->addOption(CURLOPT_URL, $this->url);

		return $this;
	}

	public function setOptions(array $options): self
	{
		$this->options = $options;

		return $this;
	}

	public function addOption(int $key, mixed $value): self
	{
		$this->options[$key] = $value;

		return $this;
	}

	public function getUrl(): string
	{
		return $this->url;
	}

	/**
	 * @param bool $closeAfterExec
	 *
	 * @return Curl
	 * @throws CurlExecuteException
	 */
	public function exec(bool $closeAfterExec = true): self
	{
		if ($this->data !== null) {
			$this->setRequestAsPost();
			curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $this->data);
		}
		$header = [];
		foreach ($this->header as $k => $h) {
			$header[] = "$k: $h";
		}
		$this->addOption(CURLOPT_HTTPHEADER, $header);
		curl_setopt_array($this->curlHandle, $this->options);
		$this->result = curl_exec($this->curlHandle);
		$this->infos = curl_getinfo($this->curlHandle);
		if ($this->result === false) {
			throw new CurlExecuteException($this->getErrorString());
		}
		if ($closeAfterExec) {
			$this->close();
		}

		return $this;
	}

	public function getInfos(): array
	{
		return $this->infos;
	}

	public function getInfo(CurlInfoKeyEnum $key): string|int|float|null
	{
		if (!array_key_exists($key->value, $this->infos)) {
			return null;
		}
		$data = $this->infos[$key->value];
		if (strval(intval($data)) === $data) {
			return intval($data);
		}
		if (strval(floatval($data)) === $data) {
			return floatval($data);
		}
		return $data;
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

	public function close(): self
	{
		curl_close($this->curlHandle);

		return $this;
	}


	public static function init(string $url): self
	{
		return new self($url);
	}
}
