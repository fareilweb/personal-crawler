<?php
class RequestResponseDto
{
	public $info;
	public $content;

	public function __construct($info = NULL, $content = NULL)
	{
		$this->info = $info;
		$this->content = $content;
	}

	public function __destruct()
	{
		foreach( get_object_vars($this) as $key => $val)
		{
			unset( $this->{$key} );
		}
	}
}
