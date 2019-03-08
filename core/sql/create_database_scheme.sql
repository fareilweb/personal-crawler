CREATE TABLE UrlList (
    id                  INTEGER        DEFAULT (1)
                                       UNIQUE
                                       NOT NULL,
    info_url            VARCHAR (1000) PRIMARY KEY
                                       UNIQUE
                                       NOT NULL,
    info_content_type   VARCHAR (50),
    info_http_code      INTEGER,
    info_redirect_count INTEGER (3),
    info_redirect_url   VARCHAR (1000),
    info_primary_ip     VARCHAR (50),
    info_primary_port   INTEGER (6),
    content_table_name  VARCHAR (20),
    insert_date         DATETIME,
    update_date         DATETIME
);

CREATE TABLE WebPageList (
    id               INTEGER       PRIMARY KEY AUTOINCREMENT
                                   UNIQUE
                                   NOT NULL
                                   DEFAULT (1),
    language         VARCHAR (6),
    title            VARCHAR (300),
    h1               VARCHAR (500),
    h2               VARCHAR (500),
    h3               VARCHAR (500),
    h4               VARCHAR (500),
    h5               VARCHAR (500),
    h6               VARCHAR (500),
    meta_keywords    VARCHAR (300),
    meta_description VARCHAR (300),
    top_words        VARCHAR (300),
    insert_date      DATETIME,
    update_date      DATETIME,
    UrlList_info_url INT           REFERENCES UrlList (id) ON DELETE CASCADE
                                                           ON UPDATE CASCADE
);
