<?php
if (!se_db_is_field('person','sunscriber_news')){ 
    se_db_add_field('person', 'subscriber_news', "enum('Y','N') NOT NULL default 'N' AFTER `loyalty`");
    se_db_query("ALTER TABLE  `person` ADD INDEX (`subscriber_news`);");
}
