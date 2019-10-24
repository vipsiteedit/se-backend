<?php

if (!se_db_is_field('shop_param','typevalue')){ 
    se_db_add_field('shop_param', 'typevalue', "ENUM(  'color',  'size',  'value',  'string',  'check' ) NOT NULL DEFAULT  'value' AFTER  `nameparam`");
}
