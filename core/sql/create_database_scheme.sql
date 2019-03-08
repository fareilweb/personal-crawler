CREATE TABLE WebPages (
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
    update_date      DATETIME
);
