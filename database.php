<?php

// The SQL to uninstall this tool
$DATABASE_UNINSTALL = array(
"drop table if exists {$CFG->dbprefix}chemtube3d"
);

// The SQL to create the tables if they don't exist
$DATABASE_INSTALL = array(

array( "{$CFG->dbprefix}chemtube3d_assigned",
"create table {$CFG->dbprefix}chemtube3d_assigned (
    link_id     INTEGER NOT NULL,
    user_id     INTEGER NOT NULL,
    url_id      INTEGER NOT NULL,
    updated_at  DATETIME NOT NULL,

    CONSTRAINT `{$CFG->dbprefix}chemtube3d_ibfk_1`
        FOREIGN KEY (`link_id`)
        REFERENCES `{$CFG->dbprefix}lti_link` (`link_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT `{$CFG->dbprefix}chemtube3d_ibfk_2`
        FOREIGN KEY (`user_id`)
        REFERENCES `{$CFG->dbprefix}lti_user` (`user_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    UNIQUE(link_id, user_id)
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),


array( "{$CFG->dbprefix}chemtube3d_url",
"create table {$CFG->dbprefix}chemtube3d_url (
    url_id     INTEGER NOT NULL AUTO_INCREMENT,
    url    VARCHAR(255) NOT NULL,
    description   text NOT NULL,
  PRIMARY KEY (url_id)
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),



);

