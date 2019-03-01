<?php
class RequestResponseDto
{
	public $info;
	public $content;

	public function __construct(int $id = NULL, $info = NULL, $content = NULL)
	{
		$this->info = NULL;
		$this->content = NULL;
	}

	public function __destruct()
	{
		unset( $this->info );
		unset( $this->content );
	}
}
