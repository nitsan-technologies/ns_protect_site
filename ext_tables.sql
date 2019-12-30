#
# Modifying pages table
#
CREATE TABLE pages (
    tx_nsprotectsite_protection TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
    tx_nsprotectsite_protect_password varchar(255) DEFAULT '' NOT NULL
);
