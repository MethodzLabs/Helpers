<?php

namespace Zaacom\helpers\curl;


abstract class Curl
{

	private static \CurlHandle $curlHandle;
	private static bool|string $result;
	private static array $infos;

	public static function init(string $url)
	{
		self::$curlHandle = curl_init($url);
		self::setHeader([
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13',
		]);
	}

	public static function setHeader(array $header)
	{
		curl_setopt_array(self::$curlHandle, $header);
	}

	public static function setHtaccessUsernameAndPassword(string $username, string $password)
	{
		curl_setopt(self::$curlHandle, CURLOPT_USERPWD, "$username:$password");
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

	public static function exec(): bool|string
	{
		self::$result = curl_exec(self::$curlHandle);
		return self::$result;
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
