<?php

namespace Methodz\Helpers\Curl;


enum CurlCommonHeaderKeyEnum: string
{
	case USER_AGENT = "User-Agent";
	case ACCEPT = "Accept";
	case ACCEPT_ENCODING = "Accept-Encoding";
	case ACCEPT_LANGUAGE = "Accept-Language";
	case CACHE_CONTROL = "Cache-Control";
	case CONNECTION = "Connection";
	case HOST = "Host";
	case ORIGIN = "Origin";
	case REFERER = "Referer";
	case UPGRADE_INSECURE_REQUESTS = "Upgrade-Insecure-Requests";
	case HTTP_ACCEPT_LANGUAGE = "http_accept_language";
	case CONTENT_LENGTH = "Content-Length";
	case CONTENT_TYPE = "Content-Type";

}
