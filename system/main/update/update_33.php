<?php

     if (!se_db_is_field('shop_group','description')) se_db_add_field('shop_group', 'description', 'text default NULL AFTER `keywords`');

