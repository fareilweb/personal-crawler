<?php

class CurlInfoModel
{
	public function __construct(array $curl_info_result)
	{

	}

	public function __destruct()
	{
		foreach( get_object_vars($this) as $key => $val)
		{
			unset( $this->{$key} );
		}
	}
}
