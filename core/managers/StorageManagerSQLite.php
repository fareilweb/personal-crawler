<?php

class StorageManagerSQLite extends BaseManager implements IStorageManager
{
    /** @var SQLite3 */
    private $db;

    private $UrlListTableName;
    private $WebPagesTableName;

    public function __construct()
    {
        $db_file = PATH_SQLITE . DIRECTORY_SEPARATOR . SQLITE_DB_NAME;
        $this->db = new SQLite3( $db_file );

        // Set Tables Names
        $this->UrlListTableName = DBTablesEnum::UrlListTableName;
        $this->WebPagesTableName = DBTablesEnum::WebPageListTableName;

        parent::__construct();
    }

    public function GetContentTableNameByUrl(string $url): string {
        $query = "SELECT content_table_name FROM {$this->UrlListTableName} WHERE url = '{$url}'";
        $result = $this->db->querySingle($query, true);
        if(empty($result)) { return FALSE; } // NULL or FALSE or [] - return FALSE
        return $result['content_table_name'];
    }


#region - UrlList

    public function GetUrlModelById(int $id) : UrlModel {
        $query = "SELECT * FROM {$this->UrlListTableName} WHERE id = {$id}";
        $result = $this->db->querySingle($query, true);
        if(empty($result)) { return FALSE; } // NULL or FALSE or [] - return FALSE
        $urlModel = new UrlModel($this->UrlListTableName);
        $urlModel->SetDataFromArray($result);
        return $urlModel;
    }

    public function GetUrlModelByUrl(string $url) {
        $query = "SELECT * FROM {$this->UrlListTableName} WHERE url = '{$url}'";
        $result = $this->db->querySingle($query, true);
        if(empty($result)) { return FALSE; } // NULL or FALSE or [] - return FALSE
        $urlModel = new UrlModel($this->UrlListTableName);
        $urlModel->SetDataFromArray($result);
        return $urlModel;
    }

    public function InsertUrl(UrlModel $model) : int {
        $insert_timestamp = (new DateTime())->getTimestamp();
        $update_timestamp = $insert_timestamp;
        $query = <<<EOT
            INSERT INTO {$this->UrlListTableName} (
                url,
                content_type,
                http_code,
                redirect_count,
                redirect_url,
                primary_ip,
                primary_port,
                has_content,
                content_table_name,
                insert_timestamp,
                update_timestamp
            ) VALUES (
                '$model->url',
                '$model->content_type',
                '$model->http_code',
                '$model->redirect_count',
                '$model->redirect_url',
                '$model->primary_ip',
                '$model->primary_port',
                '$model->has_content',
                '$model->content_table_name',
                '$insert_timestamp',
                '$update_timestamp'
            );
EOT;

        $insert_urllist_id = FALSE;
        if($this->db->exec($query)) {
            $insert_urllist_id = $this->db->lastInsertRowID();
        }
        return $insert_urllist_id;
    }

    public function UpdateUrl(UrlModel $model) : int {
        $update_timestamp = (new DateTime())->getTimestamp();
        $query = <<<EOT
           UPDATE {$this->UrlListTableName}
           SET
                url='$model->url',
                content_type='$model->content_type',
                http_code=$model->http_code,
                redirect_count=$model->redirect_count,
                redirect_url='$model->redirect_url',
                primary_ip='$model->primary_ip',
                primary_port=$model->primary_port,
                has_content=$model->has_content,
                content_table_name='$model->table_name',
                update_timestamp=$update_timestamp
            WHERE id = $model->id;
EOT;

        if($this->db->exec($query) === FALSE) {
            return FALSE;
        }
        return $model->id;
    }

    public function InsertOrUpdateUrl(UrlModel $urlModel) : int {
        if(empty($urlModel->id)) {
            return $this->InsertUrl($urlModel);
        } else {
            return $this->UpdateUrl($urlModel);
        }
    }

#endregion - UrlList


#region - WebPageList

    public function GetWebPageModelById(int $id) : WebPageModel {
        $query = "SELECT * FROM {$this->WebPagesTableName} WHERE id = {$id}";
        $result = $this->db->querySingle($query);
        $webPageModel = new WebPageModel($this->WebPagesTableName);
        return $webPageModel;
    }

    public function GetWebPageModelByUrl(string $url) : WebPageModel {
        $query_webpage = "SELECT * FROM {$this->WebPagesTableName} WHERE UrlList_url = '{$url}'";
        $result = $this->db->querySingle($query_webpage);
        $webPageModel = new WebPageModel($this->WebPagesTableName);
        return $webPageModel;
    }

    public function InsertWebPage(WebPageModel $model, int $UrlList_url_id) : int {
        // Insert To WebPageList
        $query = <<<EOT
        INSERT INTO {$this->WebPagesTableName} (
            language,
            title,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            meta_keywords,
            meta_description,
            top_words,
            insert_date,
            update_date,
            UrlList_url
        ) VALUES (
            '{$model->language}',
            '{$model->title}',
            '{$model->h1}',
            '{$model->h2}',
            '{$model->h3}',
            '{$model->h4}',
            '{$model->h5}',
            '{$model->h6}',
            '{$model->meta_keywords}',
            '{$model->meta_description}',
            '{$model->top_words}',
            '{$model->insert_timestamp->date}',
            '{$model->update_timestamp->date}',
            '{$UrlList_url_id}'
        );
EOT;

        $insert_webpage_id = FALSE;
        if($this->db->exec($query)) {
            $insert_webpage_id = $this->db->lastInsertRowID();
        }
        return $insert_webpage_id;
    }

    function UpdateWebPage(WebPageModel $model, int $UrlList_url_id) : int {
        // Insert To WebPageList
        $query = <<<EOT
            UPDATE {$this->WebPagesTableName}
            SET
                language='{$model->language}',
                title='{$model->title}',
                h1='{$model->h1}',
                h2='{$model->h2}',
                h3='{$model->h3}',
                h4='{$model->h4}',
                h5='{$model->h5}',
                h6='{$model->h6}',
                meta_keywords='{$model->meta_keywords}',
                meta_description='{$model->meta_description}',
                top_words='{$model->top_words}',
                update_date='{$model->update_timestamp->date}',
                UrlList_url='{$UrlList_url_id}'
            WHERE id = {$model->id}
EOT;

        //$this->db->changes();
        if($this->db->exec($query) === FALSE) {
            return FALSE;
        }
        return $model->id;
    }

    function InsertOrUpdateWebPage(WebPageModel $webPageModel, int $UrlList_url_id): int {
        if(empty($webPageModel)) {
            return $this->InsertWebPage($webPageModel, $UrlList_url_id);
        } else {
            return $this->UpdateWebPage($webPageModel, $UrlList_url_id);
        }
    }

#region - WebPageList

    function __destruct() {
        $this->db->close(); // Close DB connection
        parent::__destruct();
    }
}