<?php
class WebPageConverter implements IConverter
{
    private $implode_explode_separator_string = "|";

    /**
     * Take a WebPageModel and convert to a WebPageDto
     *
     * @param WebPageModel
     * @return WebPageDto
     */
    public static function ToDto(WebPageModel $model): WebPageDto {
        $dto = new WebPageDto();

        return $dto;
    }

    /**
     * Take a WebPageDto and convert to a WebPageModel
     *
     * @param WebPageDto
     * @return WebPageModel
     */
    public static function ToModel(WebPageDto $dto): WebPageModel {
        $model = new WebPageModel(DBTablesEnum::WebPages);

        $model->id = $dto->id;
        $model->info = $dto->info;

        $model->h1 = implode($this->implode_explode_separator_string, $dto->headers["h1"]);
        $model->h2 = implode($this->implode_explode_separator_string, $dto->headers["h2"]);
        $model->h3 = implode($this->implode_explode_separator_string, $dto->headers["h3"]);
        $model->h4 = implode($this->implode_explode_separator_string, $dto->headers["h4"]);
        $model->h5 = implode($this->implode_explode_separator_string, $dto->headers["h5"]);
        $model->h6 = implode($this->implode_explode_separator_string, $dto->headers["h6"]);

        $model->insert_date = $dto->insert_date;
        $model->update_date = $dto->update_date;
        $model->language = $dto->language;


        //['name' => $name, 'content' => $content]

        $model->meta_keywords = (function($meta_data) {
            
            return [];
        })($dto->meta_data);

        return $model;
    }
}
