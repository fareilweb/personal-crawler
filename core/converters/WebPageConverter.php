<?php
class WebPageConverter
{
    const ImplodeExplodeSeparator = "|";

    public function ToModel(WebPageDto $dto) : WebPageModel {
        $model = new WebPageModel(TablesEnum::WebPageListTableName);

        if(!isset($dto->info)) { return $model; } // No Info, return empty model

        $model->id = $dto->id;
        $model->insert_timestamp = isset($dto->insert_datetime) ? $dto->insert_datetime : new DateTime();
        $model->update_timestamp = isset($dto->update_datetime) ? $dto->update_datetime : new DateTime();

        // Set Info and Check for HTTP errors
        $model->SetInfoFromCurlRequestInfoDto($dto->info);
        if($model->info_http_code <= 0 || $model->info_http_code >= 400) {
            return $model; // HTTP error, return incomplete model
        }

        // Collect other data

        $model->title = $dto->title;
        $model->language = $dto->language;

        $model->top_words = (function($top_words_array) {
            $top_words_string = "";
            $index = 0;
            $index_stop_add_char = count($top_words_array)-2;
            foreach($top_words_array as $word => $count) {
                $top_words_string .= $word;
                if($index < $index_stop_add_char) {
                    $top_words_string .= WebPageConverter::ImplodeExplodeSeparator;
                }
                $index++;
            }
            return $top_words_string;
        })($dto->top_words);
        //implode(WebPageConverter::ImplodeExplodeSeparator, $dto->top_words);

        $model->h1 = implode(WebPageConverter::ImplodeExplodeSeparator, $dto->headers["h1"]);
        $model->h2 = implode(WebPageConverter::ImplodeExplodeSeparator, $dto->headers["h2"]);
        $model->h3 = implode(WebPageConverter::ImplodeExplodeSeparator, $dto->headers["h3"]);
        $model->h4 = implode(WebPageConverter::ImplodeExplodeSeparator, $dto->headers["h4"]);
        $model->h5 = implode(WebPageConverter::ImplodeExplodeSeparator, $dto->headers["h5"]);
        $model->h6 = implode(WebPageConverter::ImplodeExplodeSeparator, $dto->headers["h6"]);

        $model->meta_keywords = (function($meta_data) {
            $key = array_search('keywords', array_column($meta_data, 'name'));
            $content = "";
            if($key !== FALSE) {
                $content = $meta_data[$key]['content'];
            }
            return $content;
        })($dto->meta_data);

        $model->meta_description = (function($meta_data) {
            $key = array_search('description', array_column($meta_data, 'name'));
            $content = "";
            if($key !== FALSE) {
                $content = $meta_data[$key]['content'];
            }
            return $content;
        })($dto->meta_data);

        return $model;
    }
}
