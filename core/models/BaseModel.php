<?php
class BaseModel implements IBaseDto
{
    public static $Tables = [
        'web_pages' => 'web_pages'
    ];

    /** @var int $id */
    public $id;

    /** @var string */
    public $table;

    public function FromDto(BaseDto $dto) {
        
    }
}