<?php
interface IConverter {
    static function ToDto(BaseModel $model) : BaseDto;
    static function ToModel(BaseDto $dto) : BaseModel;
}