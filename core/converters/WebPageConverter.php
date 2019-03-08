<?php
class WebPageConverter implements IConverter
{
    public static function ToDto(WebPageModel $model): WebPageDto {
        $dto = new WebPageDto();

        

        return $dto;
    }
    public static function ToModel(WebPageDto $dto): WebPageModel {

        return new WebPageModel();
    }
}
