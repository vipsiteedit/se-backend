<?php

require_once dirname(__FILE__) . "/base/seBaseCompany.class.php";
/**
 * @filesource seBaseCompany.class.php
 * @copyright VIPSITEEDIT
 */

class seCompany extends seBaseCompany
{
    public function Create($req = array())
    {
        if (!$this->validateInn($req['inn'])) {
            return false;
        }
        $this->where("inn = ?", $req['inn']);
        $result = $this->fetchOne();
        if (!$result['id']) {
            // Регистрируем еоную компанию
            $this->insert();
            $this->inn = $req['inn'];
            $this->name = $req['name'];
            $this->fullname = $req['fullname'];
            $this->kpp = $req['kpp'];
            $this->phone = $req['phone'];
            $this->email = $req['email'];
            $this->address = $req['address'];
            $this->note = $req['note'];
            $this->reg_date = date('Y-m-d');
            return $this->save();
        }
        return $result['id'];
    }

    public function AddPerson($companyId, $personId)
    {
        $cp = new seTable('company_person');
        $cp->where("id_person = ? AND id_company = ?", $personId, $companyId);
        if (!$cp->isFind()) {
            $cp->insert();
            $cp->id_company = $companyId;
            $cp->id_person = $personId;
            return $cp->save();
        }
        return false;
    }

    private function validateInn($inn)
    {
        return (!empty($inn) && strlen($inn) >= 10);
    }
}
