<?php
if (se_db_is_field('main','domain varchar(250) AFTER basecurr')){ 
    se_db_query("ALTER TABLE `main` DROP `domain varchar(250) AFTER basecurr`");
}
