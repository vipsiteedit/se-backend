<?php
    if (!se_db_is_field('main_log', 'id')) 
        se_table_migration('main_log');
