<?php

class SQLiteStorageManager extends BaseManager implements IStorageManager
{
    private $db;
    private $UrlListTableName;
    private $WebPagesTableName;

    function __construct()
    {
        $db_file = PATH_SQLITE . DIRECTORY_SEPARATOR . SQLITE_DB_NAME;
        $this->db = new SQLite3( $db_file );

        // Set Tables Names
        $this->UrlListTableName = DBTablesEnum::UrlListTableName;
        $this->WebPagesTableName = DBTablesEnum::WebPageListTableName;
    }

    /**
     * Get the table where the url content was stored (if exists)
     *
     * @param string $url
     * @return string - the table name if success, an empty string if url was not found, FALSE if fail
     */
    public function GetTableByUrl(string $url): string {
        $query = "SELECT content_table_name FROM {$this->UrlListTableName} WHERE url = '{$url}'";
        $result = $this->db->querySingle($query);
        if($result === FALSE) { return FALSE; } // Query Failed
        return $result['content_table_name'];
    }


#region - UrlList

    /**
     * Search for an url record by ID
     * @param int $id
     * @return array
     */
    public function GetUrlById(int $id) : UrlModel {
        $query = "SELECT * FROM {$this->UrlListTableName} WHERE id = {$id}";
        $result = $this->db->querySingle($query);
        if($result === FALSE) { return FALSE; } // Query fail

        $model = new UrlModel($this->UrlListTableName);

        return $result;
    }

    /**
     * Search for an url record by URL
     * @param string $url
     * @return array
     */
    public function GetUrlByUrl(string $url) : UrlModel {
        $query = "SELECT * FROM {$this->UrlListTableName} WHERE url = '{$url}'";
        $result = $this->db->querySingle($query);
        return $result;
    }

    /**
     * Insert Url into database
     *
     * @param WebPageModel
     * @return int - return: UrlList "lastInsertRowID" if success FALSE otherwise
     */
    public function InsertUrl(UrlModel $model) : int {
        // Insert to UrlList
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
                '{$model->url}',
                {$model->content_type},
                {$model->http_code},
                {$model->redirect_count},
                '{$model->redirect_url}',
                '{$model->primary_ip}',
                {$model->primary_port},
                {$model->has_content},
                '{$model->content_table_name}',
                '{$model->insert_timestamp->getTimestamp()}',
                '{$model->update_timestamp->getTimestamp()}'
            );
EOT;

        $insert_urllist_id = FALSE;
        if($this->db->exec($query)) {
            $insert_urllist_id = $this->db->lastInsertRowID();
        }
        return $insert_urllist_id;
    }


    /**
     * Update Url into database
     *
     * @param WebPageModel
     * @return int - return: UrlList updated ID if success FALSE otherwise
     */
    public function UpdateUrl(UrlModel $model) : int {
        // Insert to UrlList
        $query = <<<EOT
           UPDATE {$this->UrlListTableName}
           SET
                url='{$model->url}',
                content_type='{$model->content_type}',
                http_code={$model->http_code},
                redirect_count={$model->redirect_count},
                redirect_url='{$model->redirect_url}',
                primary_ip='{$model->primary_ip}',
                primary_port={$model->primary_port},
                has_content={$model->has_content},
                content_table_name='{$model->table_name}',
                update_timestamp='{$model->update_timestamp->getTimestamp()}'
            WHERE id = $model->id;
EOT;

        //$this->db->changes();
        if($this->db->exec($query) === FALSE) {
            return FALSE;
        }

        return $model->id;
    }


#endregion - UrlList


#region - WebPageList

    public function GetWebPageById(int $id) : array {
        $query = "SELECT * FROM {$this->WebPagesTableName} WHERE id = {$id}";
        $result = $this->db->querySingle($query);
        return $result;
    }

    public function GetWebPageByUrl(string $url) : array {



        $query_webpage = "SELECT * FROM {$this->WebPagesTableName} WHERE UrlList_url = '{$id}'";


        $result = $this->db->querySingle($query_webpage);
        return $result;
    }

    /**
     * Insert WebPage into database
     *
     * @param WebPageModel
     * @param int - the id of the url in UrlList table (it's a foreign key)
     * @return integer - return: WebPage "lastInsertRowID" if success FALSE otherwise
     */
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


    /**
     * Update WebPage into database
     *
     * @param WebPageModel
     * @param int - the id of the url in UrlList table (it's a foreign key)
     * @return integer - return: WebPage ID if success FALSE otherwise
     */
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

#region - WebPageList

    function __destruct() {
        $this->db->close(); // Close DB connection
        parent::__destruct();
    }
}