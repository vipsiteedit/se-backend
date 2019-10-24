<?php

     if (!se_db_is_field('main','domain')) se_db_add_field('main', 'domain', 'varchar(255) default NULL AFTER `basecurr`');

