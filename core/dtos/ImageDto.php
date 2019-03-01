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
		unset( $this->src );
		unset( $this->title );
		unset( $this->alt );
	}
}