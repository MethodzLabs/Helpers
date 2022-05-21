<?php

namespace Zaacom\helpers\curl;


abstract class Curl
{

	private static \CurlHandle $curlHandle;
	private static bool|string $result;
	private static string $url;
	private static array $infos;
	private static ?array $data = null;
	private static array $header = [
		CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.4 Safari/605.1.15',
		CURLOPT_RETURNTRANSFER => true,
	];

	public static function init(string $url)
	{
		self::$url = $url;
		self::$curlHandle = curl_init($url);
	}

	public static function setHeader(array $header)
	{
		self::$header = $header;
	}

	public static function addHeader(int $key, mixed $value)
	{
		self::$header[$key] = $value;
	}

	public static function setHtaccessUsernameAndPassword(string $username, string $password)
	{
		self::addHeader(CURLOPT_USERPWD, "$username:$password");
	}

	public static function setRequestAsPost(bool $bool = true) {
		self::addHeader(CURLOPT_POST, $bool);
	}

	public static function setPOSTParameters(array $data)
	{
		self::$data = $data;
	}

	public static function addPOSTParameters(string $key, mixed $value)
	{
		self::$data[$key] = $value;
	}

	public static function setGETParameters(array $data)
	{
		self::addHeader(CURLOPT_URL, Url::from(self::$url)->setParameters($data)->build());
	}

	public static function addGETParameters(string $key, mixed $value)
	{
		self::addHeader(CURLOPT_URL, Url::from(self::$url)->addParameters($key, $value)->build());
	}

	public static function exec(): bool|string
	{
		if (self::$data !== null) {
			self::setRequestAsPost();
			self::addHeader(CURLOPT_POSTFIELDS, self::$data);
		}
		curl_setopt_array(self::$curlHandle, self::$header);
		self::$result = curl_exec(self::$curlHandle);
		return self::$result;
	}

	public static function getInfos(): array
	{
		self::$infos = curl_getinfo(self::$curlHandle);

		return self::$infos;
	}

	public static function getInfo(int $option)
	{
		return curl_getinfo(self::$curlHandle, $option);
	}

	public static function getResult(): bool|string
	{
		return self::$result;
	}

	public static function getErrorString(): string
	{
		return "Curl error " . self::getErrorNumber() . " (" . self::getError()->name . "): \"" . curl_error(self::$curlHandle) . "\"";
	}

	public static function getError(): CurlErrorEnum
	{
		return CurlErrorEnum::getError(self::getErrorNumber());
	}

	public static function getErrorNumber(): int
	{
		return curl_errno(self::$curlHandle);
	}

	public static function close()
	{
		curl_close(self::$curlHandle);
	}
}
