<?php
class ImageDto
{
	public $src;
	public $title;
	public $alt;

	public function __construct(string $src = NULL, string $title = NULL, string $alt = NULL)
	{
		$this->src = $src;
		$this->title = $title;
		$this->alt = $alt;
	}

	public function __destruct()
	{
		foreach( get_object_vars($this) as $key => $val)
		{
			unset( $this->{$key} );
		}
	}
}