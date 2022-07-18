<?php

namespace Methodz\Helpers\Curl;


enum CurlInfoKeyEnum: string
{
	case URL = "url";
	case CONTENT_TYPE = "content_type";
	case HTTP_CODE = "http_code";
	case HEADER_SIZE = "header_size";
	case REQUEST_SIZE = "request_size";
	case FILE_TIME = "filetime";
	case SSL_VERIFY_RESULT = "ssl_verify_result";
	case REDIRECT_COUNT = "redirect_count";
	case TOTAL_TIME = "total_time";
	case NAME_LOOKUP_TIME = "namelookup_time";
	case CONNECT_TIME = "connect_time";
	case PRE_TRANSFER_TIME = "pretransfer_time";
	case SIZE_UPLOAD = "size_upload";
	case SIZE_DOWNLOAD = "size_download";
	case SPEED_DOWNLOAD = "speed_download";
	case SPEED_UPLOAD = "speed_upload";
	case DOWNLOAD_CONTENT_LENGTH = "download_content_length";
	case UPLOAD_CONTENT_LENGTH = "upload_content_length";
	case START_TRANSFER_TIME = "starttransfer_time";
	case REDIRECT_TIME = "redirect_time";

}
