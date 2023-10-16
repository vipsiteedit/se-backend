<?php

require_once dirname(__FILE__) . "/seBase.class.php";

/**
 * @filesource seBaseCompany.class.php
 * @copyright VIPSITEEDIT
 */
class seBaseCompany extends seBase
{

    protected function configure()
    {
        $this->table_name = 'company';
        $this->table_alias = 'c';

        $this->fields = array(
            'id' => 'integer',
            'name' => 'string',
            'fullname' => 'string',
            'inn' => 'string',
            'kpp' => 'string',
            'phone' => 'string',
            'email' => 'string',
            'address' => 'string',
            'note' => 'string',
            'reg_date' => 'datetime'
        );

        $this->fieldalias = array();
    }
}
