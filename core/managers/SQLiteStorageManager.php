<?php

class SQLiteStorageManager extends BaseManager implements IStorageManager
{
    private $db;
    private $UrlList_table_name = DBTablesEnum::UrlList;
    private $WebPages_table_name = DBTablesEnum::WebPageList;

    function __construct()
    {
        $db_file = PATH_SQLITE . DIRECTORY_SEPARATOR . SQLITE_DB_NAME;
        $this->db = new SQLite3( $db_file );
    }

    /**
     * Get the table where the url content was stored (if exists)
     *
     * @param string $url
     * @return string - the table name if success, an empty string if url was not found, FALSE if fail
     */
    public function GetTableByUrl(string $url): string {
        $searched_table_name = "";

        $query = "SELECT * FROM {$this->UrlList_table_name} WHERE info_url = '{$url}'";
        $result = $this->db->querySingle($query);

        if($result === FALSE) { return FALSE; } // Query Failed
        return $result['content_table_name'];
    }


#region - UrlList

    public function GetUrlById(int $id) : array {
        $query = "SELECT * FROM {$this->UrlList_table_name} WHERE id = {$id}";
        $result = $this->db->querySingle($query);
        return $result;
    }

    public function GetUrlByUrl(string $url) : array {
        $query = "SELECT * FROM {$this->UrlList_table_name} WHERE info_url = '{$url}'";
        $result = $this->db->querySingle($query);
        return $result;
    }


    /**
     * Insert Url into database
     *
     * @param WebPageModel
     * @return int - return: UrlList "lastInsertRowID" if success FALSE otherwise
     */
    public function InsertUrl(WebPageModel $model) : int {
        // Insert to UrlList
        $query = <<<EOT
            INSERT INTO {$this->UrlList_table_name} (
                info_url,
                info_content_type,
                info_http_code,
                info_redirect_count,
                info_redirect_url,
                info_primary_ip,
                info_primary_port,
                content_table_name
            ) VALUES (
                '{$model->info_url}',
                '{$model->info_content_type}',
                '{$model->info_http_code}',
                '{$model->info_redirect_count}',
                '{$model->info_redirect_url}',
                '{$model->info_primary_ip}',
                '{$model->info_primary_port}',
                '{$model->table_name}'
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
    public function UpdateUrl(WebPageModel $model) : int {
        // Insert to UrlList
        $query = <<<EOT
           UPDATE {$this->UrlList_table_name}
           SET
                info_url='{$model->info_url}',
                info_content_type='{$model->info_content_type}',
                info_http_code='{$model->info_http_code}',
                info_redirect_count='{$model->info_redirect_count}',
                info_redirect_url='{$model->info_redirect_url}',
                info_primary_ip='{$model->info_primary_ip}',
                info_primary_port='{$model->info_primary_port}',
                content_table_name='{$model->table_name}'
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
        $query = "SELECT * FROM {$this->WebPages_table_name} WHERE id = {$id}";
        $result = $this->db->querySingle($query);
        return $result;
    }

    public function GetWebPageByUrlId(int $id) : array {
        $query = "SELECT * FROM {$this->WebPages_table_name} WHERE UrlList_info_url = '{$id}'";
        $result = $this->db->querySingle($query);
        return $result;
    }

    /**
     * Insert WebPage into database
     *
     * @param WebPageModel
     * @param int - the id of the url in UrlList table (it's a foreign key)
     * @return integer - return: WebPage "lastInsertRowID" if success FALSE otherwise
     */
    public function InsertWebPage(WebPageModel $model, int $UrlList_info_url_id) : int {
        // Insert To WebPageList
        $query = <<<EOT
        INSERT INTO {$this->WebPages_table_name} (
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
            UrlList_info_url
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
            '{$model->insert_date->date}',
            '{$model->update_date->date}',
            '{$UrlList_info_url_id}'
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
    function UpdateWebPage(WebPageModel $model, int $UrlList_info_url_id) : int {
        // Insert To WebPageList
        $query = <<<EOT
            UPDATE {$this->WebPages_table_name}
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
                update_date='{$model->update_date->date}',
                UrlList_info_url='{$UrlList_info_url_id}'
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