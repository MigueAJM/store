<?php

namespace App\Exception;

final class ErrorCode
{
	const NOT_FOUND_SALT = 1001;
	const NOT_FOUND_EMAIL = 1002;
	const NOT_FOUND_PASSWORD = 1003;
	const NOT_FOUND_UUID_SESSION = 1004;
	const EXPIRED_SESSION = 1005;
}