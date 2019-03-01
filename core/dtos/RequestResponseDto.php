<?php
class RequestResponseDto
{
	public $id;	
	public $info;
	public $content;
	
	public function __construct(int $id = NULL, $info = NULL, $content = NULL)
	{
		$this->id = NULL;		
		$this->info = NULL;
		$this->content = NULL;		
	}

	public function ToModel() : RequestResponseModel
	{
		$model = new RequestResponseModel();

		return $model;
	}
}
