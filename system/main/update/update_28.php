<?php

if (!se_db_is_field('shop_comm','response')) se_db_add_field('shop_comm', 'response', 'text default NULL AFTER `commentary`');
