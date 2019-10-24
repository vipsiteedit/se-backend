<?php
     if (!se_db_is_field('shop_price','price_opt_corp')) se_db_add_field('shop_price', 'price_opt_corp', 'double(10,2) AFTER `price_opt`');
