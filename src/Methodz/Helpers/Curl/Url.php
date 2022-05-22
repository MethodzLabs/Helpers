<?php

namespace Methodz\Helpers\Curl;

class Url
{
	private UrlSchemeEnum $scheme;
	private ?string $user;
	private ?string $pass;
	private string $host;
	private ?int $port;
	private ?string $path;
	private ?array $data = null;
	private ?string $fragment;


	private function __construct(string $url)
	{
		$this->scheme = UrlSchemeEnum::from(parse_url($url, PHP_URL_SCHEME) ?? UrlSchemeEnum::HTTPS->value);
		$this->user = parse_url($url, PHP_URL_USER);
		$this->pass = parse_url($url, PHP_URL_PASS);
		$this->host = parse_url($url, PHP_URL_HOST) ?? parse_url($url, PHP_URL_PATH);
		$this->port = parse_url($url, PHP_URL_PORT);
		$this->path = ($path = parse_url($url, PHP_URL_PATH)) !== $this->host ? $path : null;
		$query = parse_url($url, PHP_URL_QUERY);
		if ($query !== null && $query !== "") {
			preg_match_all("/(([^=]+)=([^&]+))(&?)/", $query, $matches);
			$keys = $matches[2];
			$values = $matches[3];
			$this->data = [];
			for ($i = 0; $i < count($keys); $i++) {
				$this->data[$keys[$i]] = $values[$i];
			}
		}
		$this->fragment = ($fragment = parse_url($url, PHP_URL_FRAGMENT)) === "" ? null : $fragment;
	}

	/**
	 * @param UrlSchemeEnum $scheme
	 *
	 * @return Url
	 */
	public function setScheme(UrlSchemeEnum $scheme): self
	{
		$this->scheme = $scheme;

		return $this;
	}

	/**
	 * @param string|null $user
	 *
	 * @return Url
	 */
	public function setUser(string|null $user): self
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * @param string|null $pass
	 *
	 * @return Url
	 */
	public function setPass(string|null $pass): self
	{
		$this->pass = $pass;

		return $this;
	}

	/**
	 * @param string|null $host
	 *
	 * @return Url
	 */
	public function setHost(string|null $host): self
	{
		$this->host = $host;

		return $this;
	}

	/**
	 * @param int|null $port
	 *
	 * @return Url
	 */
	public function setPort(int|null $port): self
	{
		$this->port = $port;

		return $this;
	}

	/**
	 * @param string|null $path
	 *
	 * @return Url
	 */
	public function setPath(string|null $path): self
	{
		$this->path = $path;

		return $this;
	}

	/**
	 * @param array|null $data
	 *
	 * @return $this
	 */
	public function setParameters(?array $data): self
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return $this
	 */
	public function addParameters(string $key, mixed $value): self
	{
		if ($this->data === null) {
			$this->data = [];
		}
		$this->data[$key] = $value;
		return $this;
	}

	/**
	 * @param string|null $fragment
	 *
	 * @return Url
	 */
	public function setFragment(string|null $fragment): self
	{
		$this->fragment = $fragment;

		return $this;
	}

	public function build(): string
	{
		$url = $this->scheme->value . "://";
		if ($this->user !== null && $this->pass !== null) {
			$url .= $this->user . ":" . $this->pass . "@";
		}
		$url .= $this->host;
		if ($this->port !== null) {
			$url .= ":" . $this->port;
		}
		if ($this->path !== null) {
			$url .= $this->path;
		}
		if ($this->data !== null) {
			$dataString = "?";
			foreach ($this->data as $key => $value) {
				if ($dataString !== "?") {
					$dataString .= "&";
				}
				$dataString .= $key . "=" . $value;
			}
			$url .= $dataString;
		}
		if ($this->fragment !== null) {
			$url .= "#" . $this->fragment;
		}
		return $url;
	}

	public static function from(string $url): self
	{
		return new self($url);
	}

	public function __toString(): string
	{
		return $this->build();
	}
}
