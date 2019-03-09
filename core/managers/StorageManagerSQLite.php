<?php

class StorageManagerSQLite extends BaseManager implements IStorageManager
{
    /** @var SQLite3 */
    private $db;

    /** @var string */
    private $UrlListTableName;

    /** @var string */
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
        $query = <<<EOT
            INSERT INTO {$this->UrlListTableName} (
                url,
                curl_url,
                curl_content_type,
                curl_http_code,
                curl_redirect_count,
                curl_redirect_url,
                curl_primary_ip,
                curl_primary_port,
                has_content,
                content_table_name,
                insert_timestamp,
                update_timestamp
            ) VALUES (
                ':url',
                ':curl_url'
                ':curl_content_type',
                 :curl_http_code,
                 :curl_redirect_count,
                ':curl_redirect_url',
                ':curl_primary_ip',
                 :curl_primary_port,
                 :has_content,
                ':content_table_name',
                 :insert_timestamp,
                 :update_timestamp
            );
EOT;
        $stmt = $this->db->prepare($query);

        $insert_timestamp = (new DateTime())->getTimestamp();
        $update_timestamp = $insert_timestamp;

        $stmt->bindParam(':url', $model->url, SQLITE3_TEXT);
        $stmt->bindParam(':curl_url', $model->curl_url, SQLITE3_TEXT);
        $stmt->bindParam(':content_type', $model->curl_content_type, SQLITE3_TEXT);
        $stmt->bindParam(':http_code', $model->curl_http_code, SQLITE3_INTEGER);
        $stmt->bindParam(':redirect_count', $model->curl_redirect_count, SQLITE3_INTEGER);
        $stmt->bindParam(':redirect_url', $model->curl_redirect_url, SQLITE3_TEXT);
        $stmt->bindParam(':primary_ip', $model->curl_primary_ip, SQLITE3_TEXT);
        $stmt->bindParam(':primary_port', $model->curl_primary_port, SQLITE3_INTEGER);
        $stmt->bindParam(':has_content', $model->has_content, SQLITE3_INTEGER);
        $stmt->bindParam(':content_table_name', $model->content_table_name, SQLITE3_TEXT);
        $stmt->bindParam(':insert_timestamp', $insert_timestamp, SQLITE3_INTEGER);
        $stmt->bindParam(':update_timestamp', $update_timestamp, SQLITE3_INTEGER);

        $result = $stmt->execute();

        if($result === FALSE) {
            return FALSE;
        }

        return $this->db->lastInsertRowID();
    }


    public function UpdateUrl(UrlModel $model) : int {
        $query = <<<EOT
           UPDATE {$this->UrlListTableName}
           SET
                url=':url',
                curl_url=':curl_url'
                curl_content_type=':curl_content_type',
                curl_http_code=:curl_http_code,
                curl_redirect_count=:curl_redirect_count,
                curl_redirect_url=':curl_redirect_url',
                curl_primary_ip=':curl_primary_ip',
                curl_primary_port=:curl_primary_port,
                has_content=:has_content,
                content_table_name=':table_name',
                update_timestamp=:update_timestamp
            WHERE id = :id;
EOT;
        $stmt = $this->db->prepare($query);

        $update_timestamp = (new DateTime())->getTimestamp();

        $stmt->bindParam(':url', $model->url, SQLITE3_TEXT);
        $stmt->bindParam(':curl_url', $model->curl_url, SQLITE3_TEXT);
        $stmt->bindParam(':content_type', $model->curl_content_type, SQLITE3_TEXT);
        $stmt->bindParam(':http_code', $model->curl_http_code, SQLITE3_INTEGER);
        $stmt->bindParam(':redirect_count', $model->curl_redirect_count, SQLITE3_INTEGER);
        $stmt->bindParam(':redirect_url', $model->curl_redirect_url, SQLITE3_TEXT);
        $stmt->bindParam(':primary_ip', $model->curl_primary_ip, SQLITE3_TEXT);
        $stmt->bindParam(':primary_port', $model->curl_primary_port, SQLITE3_INTEGER);
        $stmt->bindParam(':has_content', $model->has_content, SQLITE3_INTEGER);
        $stmt->bindParam(':content_table_name', $model->content_table_name, SQLITE3_TEXT);
        $stmt->bindParam(':update_timestamp', $update_timestamp, SQLITE3_INTEGER);
        $stmt->bindParam(':id', $model->id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        if($result === FALSE) {
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
        $query = "SELECT * FROM {$this->WebPagesTableName} WHERE id = {$id};";
        $result = $this->db->querySingle($query, true);
        if (empty($result)) { return FALSE; } // NULL or FALSE or [] - return FALSE
        $model = new WebPageModel($this->WebPagesTableName);
        $model->SetDataFromArray($result);
        return $model;
    }


    public function GetWebPageModelByUrlId(int $UrlList_url_id) : WebPageModel {
        $query = "SELECT * FROM {$this->WebPagesTableName} WHERE UrlList_url_id = '{$UrlList_url_id}';";
        $result = $this->db->querySingle($query, true);
        if (empty($result)) { return FALSE; } // NULL or FALSE or [] - return FALSE
        $model = new WebPageModel($this->WebPagesTableName);
        $model->SetDataFromArray($result);
        return $model;
    }


    public function InsertWebPage(WebPageModel $model, int $UrlList_url_id) : int {
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
                ':language',
                ':title',
                ':h1',
                ':h2',
                ':h3',
                ':h4',
                ':h5',
                ':h6',
                ':meta_keywords',
                ':meta_description',
                ':top_words',
                 :insert_timestamp,
                 :update_timestamp,
                 :UrlList_url_id
            );
EOT;

        $stmt = $this->db->prepare($query);

        $insert_timestamp = (new DateTime())->getTimestamp();
        $update_timestamp = $insert_timestamp;

        $stmt->bindParam(':language', $model->language, SQLITE3_TEXT);
        $stmt->bindParam(':title', $model->title, SQLITE3_TEXT);
        $stmt->bindParam(':h1', $model->h1, SQLITE3_TEXT);
        $stmt->bindParam(':h2', $model->h2, SQLITE3_TEXT);
        $stmt->bindParam(':h3', $model->h3, SQLITE3_TEXT);
        $stmt->bindParam(':h4', $model->h4, SQLITE3_TEXT);
        $stmt->bindParam(':h5', $model->h5, SQLITE3_TEXT);
        $stmt->bindParam(':h6', $model->h6, SQLITE3_TEXT);
        $stmt->bindParam(':meta_keywords', $model->meta_keywords, SQLITE3_TEXT);
        $stmt->bindParam(':meta_description', $model->meta_description, SQLITE3_TEXT);
        $stmt->bindParam(':top_words', $model->top_words, SQLITE3_TEXT);
        $stmt->bindParam(':insert_timestamp', $insert_timestamp, SQLITE3_INTEGER);
        $stmt->bindParam(':update_timestamp', $update_timestamp, SQLITE3_INTEGER);
        $stmt->bindParam(':UrlList_url_id', $UrlList_url_id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        if($result === FALSE) {
            return FALSE;
        }
        return $this->db->lastInsertRowID();
    }


    function UpdateWebPage(WebPageModel $model, int $UrlList_url_id) : int {
        $query = <<<EOT
            UPDATE {$this->WebPagesTableName}
            SET
                language=':language',
                title=':title',
                h1=':h1',
                h2=':h2',
                h3=':h3',
                h4=':h4',
                h5=':h5',
                h6=':h6',
                meta_keywords=':meta_keywords',
                meta_description=':meta_description',
                top_words=':top_words',
                update_date=:update_timestamp,
                UrlList_url=:UrlList_url_id
            WHERE :id = {:id}
EOT;
        $stmt = $this->db->prepare($query);

        $update_timestamp = (new DateTime())->getTimestamp();

        $stmt->bindParam(':id', $model->id, SQLITE3_INTEGER);
        $stmt->bindParam(':language', $model->language, SQLITE3_TEXT);
        $stmt->bindParam(':title', $model->title, SQLITE3_TEXT);
        $stmt->bindParam(':h1', $model->h1, SQLITE3_TEXT);
        $stmt->bindParam(':h2', $model->h2, SQLITE3_TEXT);
        $stmt->bindParam(':h3', $model->h3, SQLITE3_TEXT);
        $stmt->bindParam(':h4', $model->h4, SQLITE3_TEXT);
        $stmt->bindParam(':h5', $model->h5, SQLITE3_TEXT);
        $stmt->bindParam(':h6', $model->h6, SQLITE3_TEXT);
        $stmt->bindParam(':meta_keywords', $model->meta_keywords, SQLITE3_TEXT);
        $stmt->bindParam(':meta_description', $model->meta_description, SQLITE3_TEXT);
        $stmt->bindParam(':top_words', $model->top_words, SQLITE3_TEXT);
        $stmt->bindParam(':update_timestamp', $update_timestamp, SQLITE3_INTEGER);
        $stmt->bindParam(':UrlList_url_id', $UrlList_url_id, SQLITE3_INTEGER);

        $result = $stmt->execute();

        if($result === FALSE) {
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