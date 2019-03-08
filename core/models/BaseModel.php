<?php
class BaseModel implements IBaseModel
{
    /** @var int $id */
    public $id;

    /** @var string - the name of the corresponding table on database */
    public $table_name;

    /**
     * Construct of the class
     * @param string
     */
    public function __construct(string $table_name) {
        $this->table_name = $table_name;
    }
}
