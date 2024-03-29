<?php

namespace SE\Shop;

require_once $_SERVER['DOCUMENT_ROOT'] . '/api/lib/Spout/Autoloader/autoload.php';

use SE\DB as DB;
use SE\Exception;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

class Contact extends Base
{
    protected $tableName = "person";


    // получить настройки
    protected function getSettingsFetch()
    {

        /** Премножение на валюты с использованием временной таблицы валют
         * 1 запросБД базовой валюты
         * 2 запросБД на состав валют на получение
         * 3 получение валют у ЦБ
         * 4 запросБД на наличие таблицы : на создание таблицы при ее отсутствие
         * 5 чистим таблицу валют перед записью
         * 6 запросБД на заполение
         * 7 запросБД на расчет с перемножением на текущие курсы
         */

        $u = new DB('main', 'm'); // 1
        $u->select('mt.name, mt.title, mt.name_front');
        $u->innerJoin('money_title mt', 'm.basecurr = mt.name');
        $this->currData = $u->fetchOne();
        unset($u);

        $u = new DB('shop_order', 'so'); // 2
        $u->select("so.curr");
        $u->groupBy('so.curr');
        $data = $u->getList();
        unset($u);

        foreach ($data as $k => $item) { // 3
            $course = DB::getCourse($this->currData["name"], $item["curr"]);
            $result[] = array(
                "curr"   => $item["curr"],
                "course" => $course,
            );
        };

        DB::query("
            CREATE TABLE IF NOT EXISTS `courses` (
                `curr` varchar(255),
                `course` float,
                PRIMARY KEY (curr)
            )
        "); // 4
        DB::query("TRUNCATE TABLE courses"); // 5
        DB::insertList('courses', $result); // 6

        return array( // 7
            "select" => 'p.*,
                         CONCAT_WS(
                            " ",
                            p.last_name,
                            p.first_name,
                            p.sec_name
                        ) display_name, 
                        c.name company,
                        sug.group_id id_group,
                        COUNT(so.id) count_orders,
                        SUM(
                            so.amount
                            * ( SELECT c.course
                                FROM `courses` `c`
                                WHERE c.curr = so.curr
                                LIMIT 1
                            )
                        ) amount_orders,
                        SUM(
                            sop.amount
                            * ( SELECT c.course
                                FROM `courses` `c`
                                WHERE c.curr = sop.curr
                                LIMIT 1
                            )
                        ) paid_orders,
                        su.username username,
                        su.password password,
                        (su.is_active = "Y") is_active',
            "joins" => array(
                array(
                    "type" => "inner",
                    "table" => 'se_user su',
                    "condition" => 'p.id = su.id'
                ),
                array(
                    "type" => "left",
                    "table" =>
                    '(
                            SELECT 
                                so.id,
                                so.id_author,
                                so.curr,
                                (
                                    SUM((sto.price - IFNULL(sto.discount, 0))* sto.count)
                                    - IFNULL(so.discount, 0)
                                    + IFNULL(so.delivery_payee, 0)
                                ) amount 
                            FROM shop_order so 
                            INNER JOIN shop_tovarorder sto ON sto.id_order = so.id AND is_delete="N"
                            GROUP BY so.id
                        ) so',
                    "condition" => 'so.id_author = p.id'
                ),
                array(
                    "type" => "left",
                    "table" => 'shop_order_payee sop',
                    "condition" => 'sop.id_order = so.id'
                ),
                array(
                    "type" => "left",
                    "table" => "se_user_group sug",
                    "condition" => "p.id = sug.user_id"
                ),
                array(
                    "type" => "left",
                    "table" => 'company_person cp',
                    "condition" => 'cp.id_person = p.id'
                ),
                array(
                    "type" => "left",
                    "table" => 'company c',
                    "condition" => 'c.id = cp.id_company'
                )
            ),
            "patterns" => array("displayName" => "p.last_name"),
            "convertingValues" => array(
                "amountOrders",
                "paidOrders",
            ),
            "groupBy" => "p.id, c.name, sug.group_id"
        );
    } // получить настройки

    protected function correctItemsBeforeFetch($items = [])
    {
        foreach ($items as &$item) {
            $item['phone'] = $this->correctPhone($item['phone']);
            $item['regDate'] = date("d.m.Y", strtotime($item['regDate']));
        }

        return $items;
    }

    // @@@@@@   @@@@@@ @@@@@@    @@    @@     @@@@@@   @@@@@@ @@@@@@    @@    @@@@@@
    // @@  @@   @@  @@ @@  @@   @@@@   @@         @@   @@  @@ @@  @@   @@@@   @@  @@
    // @@  @@   @@  @@ @@  @@  @@  @@  @@@@@@ @@@@@    @@  @@ @@  @@  @@  @@  @@@@@@
    // @@  @@   @@  @@ @@  @@ @@    @@ @@  @@     @@   @@  @@ @@  @@ @@    @@  @@ @@
    // @@  @@   @@  @@ @@@@@@ @@    @@ @@@@@@ @@@@@@   @@  @@ @@@@@@ @@    @@ @@  @@
    // получить пользовательские поля
    private function getCustomFields($idContact)
    {
        $u = new DB('shop_userfields', 'su');
        $u->select("cu.id, cu.id_person, cu.value, su.id id_userfield, 
                    su.name, su.required, su.enabled, su.type, su.placeholder, su.description, su.values, sug.id id_group, sug.name name_group");
        $u->leftJoin('person_userfields cu', "cu.id_userfield = su.id AND id_person = {$idContact}");
        $u->leftJoin('shop_userfield_groups sug', 'su.id_group = sug.id');
        $u->where('su.data = "contact"');
        $u->groupBy('su.id, cu.id');
        $u->orderBy('sug.sort');
        $u->addOrderBy('su.sort');
        $result = $u->getList();

        $groups = array();
        foreach ($result as $item) {
            $key = (int)$item["idGroup"];
            $group = key_exists($key, $groups) ? $groups[$key] : array();
            $group["id"] = $item["idGroup"];
            $group["name"] = empty($item["nameGroup"]) ? "Без категории" : $item["nameGroup"];
            if ($item['type'] == "date")
                $item['value'] = date('Y-m-d', strtotime($item['value']));
            if (!key_exists($key, $groups))
                $groups[$key] = $group;
            $groups[$key]["items"][] = $item;
        }
        return array_values($groups);
    } // получить пользовательские поля


    // @@    @@ @@  @@ @@@@@@@@@ @@@@@@
    // @@   @@@ @@  @@ @@  @  @@ @@  @@
    // @@  @@@@ @@@@@@ @@  @  @@ @@  @@
    // @@@@  @@ @@  @@ @@@ @ @@@ @@  @@
    // @@@   @@ @@  @@     @     @@@@@@
    // информация
    public function info($id = null)
    {
        $id = empty($id) ? $this->input["id"] : $id;
        try {
            $u = new DB('person', 'p');
            $u->select('p.*,
                        CONCAT_WS(
                            " ",
                            p.last_name,
                            p.first_name,
                            p.sec_name
                        ) display_name,
                        p.avatar imageFile,
                        su.username login,
                        su.password,
                        (su.is_active = "Y") isActive,
                        uu.company,
                        uu.director,
                        uu.tel,
                        uu.fax,
                        uu.uradres,
                        uu.fizadres,
                        CONCAT_WS(
                            " ",
                            pr.last_name,
                            pr.first_name,
                            pr.sec_name
                        ) refer_name,
                        CONCAT_WS(
                            " ",
                            pm.last_name,
                            pm.first_name,
                            pm.sec_name
                        ) manager_name
                        ');
            $u->leftJoin('se_user su', 'p.id=su.id');
            $u->leftJoin('user_urid uu', 'uu.id=su.id');
            $u->leftJoin('person pr', 'pr.id=p.id_up');
            $u->leftJoin('person pm', 'pm.id=p.manager_id');
            $u->orderBy("p.id");
            $contact = $u->getInfo($id);
            $contact["birthDate"] = date("d.m.Y", strtotime($contact["birthDate"]));
            $contact["regDate"] = date("d.m.Y", strtotime($contact["regDate"]));
            $contact['groups'] = $this->getGroups($contact['id']);
            $contact['groupsCount'] = count($contact['groups']);
            $contact['companyRequisites'] = $this->getCompanyRequisites($contact['id']);
            $contact['personalAccount'] = $this->getPersonalAccount($contact['id']);
            $accountTypeOperations = new BankAccountTypeOperation();
            $contact['accountOperations'] = $accountTypeOperations->fetch();
            $contact["customFields"] = $this->getCustomFields($contact["id"]);
            if ($count = count($contact['personalAccount']))
                $contact['balance'] = $contact['personalAccount'][$count - 1]['balance'];
            $this->result = $contact;
        } catch (Exception $e) {
            $this->error = "Не удаётся получить информацию о контакте! " . $e;
        }

        return $this->result;
    }

    // @@  @@ @@@@@@     @@       @@    @@@@@@ @@  @@ @@    @@ @@@@@@
    // @@  @@ @@   @@   @@@@     @@@@   @@     @@  @@ @@   @@@ @@
    //  @@@@  @@   @@  @@  @@   @@  @@  @@@@@@ @@@@@@ @@  @@@@ @@@@@@
    //   @@   @@   @@ @@@@@@@@ @@    @@ @@     @@  @@ @@@@  @@ @@
    //   @@   @@@@@@  @@    @@ @@    @@ @@@@@@ @@  @@ @@@   @@ @@@@@@
    // удаление
    public function delete()
    {
        $emails = array();
        $u = new DB('person');
        $u->select('email');
        if (!empty($this->input["ids"]))
            $u->where('id IN (?)', implode(",", $this->input["ids"]));
        $u->andWhere('email IS NOT NULL');
        $u->andWhere('email <> ""');
        $list = $u->getList();
        foreach ($list as $value)
            $emails[] = $value["email"];
        if (parent::delete()) {
            if (!empty($emails))
                foreach ($emails as $email) {
                    $emailProvider = new EmailProvider();
                    $emailProvider->removeEmailFromAllBooks($email);
                }
            $u = new DB('se_user');
            $u->where('id IN (?)', implode(",", $this->input["ids"]));
            $u->deleteList();
            return true;
        }
        return false;
    }

    // @@  @@ @@  @@ @@@@@@ @@@@@@@@   @@@@@@    @@    @@@@@@ @@    @@ @@@@@@ @@
    // @@  @@ @@  @@ @@        @@          @@   @@@@   @@  @@ @@   @@@ @@     @@
    //  @@@@  @@@@@@ @@@@@@    @@      @@@@@   @@  @@  @@  @@ @@  @@@@ @@     @@@@@@
    //   @@       @@ @@        @@          @@ @@@@@@@@ @@  @@ @@@@  @@ @@     @@  @@
    //   @@       @@ @@@@@@    @@      @@@@@@ @@    @@ @@  @@ @@@   @@ @@@@@@ @@@@@@
    private function getPersonalAccount($id)
    {
        /** Получить личную учетную запись
         * 1 получаем поступления/расходы по аккаунту
         * 2 получаем базовую валюту
         * 3 запрашиваем курс и конвертируем столбцы по списку (с прибавлением данных по базовой валюте)
         *
         * @param int $id idАккаунта
         * @reuturn array $account массивы с данными транзакицй по клиенту
         */
        $u = new DB('se_user_account'); // 1
        $u->where('user_id = ?', $id);
        $u->orderBy("date_payee");
        $result = $u->getList();

        $u = new DB('main', 'm'); // 2
        $u->select('mt.name, mt.title, mt.name_front');
        $u->innerJoin('money_title mt', 'm.basecurr = mt.name');
        $this->currData = $u->fetchOne();
        unset($u);

        $account = array();
        $balance = 0;
        foreach ($result as $item) {
            $balance += ($item['inPayee'] - $item['outPayee']);
            $item['balance'] = $balance;

            $course = DB::getCourse($this->currData["name"], $item["curr"]); // 3
            $convertingValues = array('inPayee', 'outPayee', 'balance');
            foreach ($convertingValues as $key => $i) {
                $item[$i] = $item[$i] * $course;
            }
            unset($item["curr"]);
            $item["nameFlang"] = $this->currData["name"];
            $item["titleCurr"] = $this->currData["title"];
            $item["nameFront"] = $this->currData["nameFront"];
            $account[] = $item;
        }
        return $account;
    }

    // @@@@@@ @@@@@@ @@  @@ @@@@@    @@  @@ @@@@@@ @@     @@ @@@@@@
    // @@  @@ @@     @@ @@  @@  @@   @@ @@  @@  @@ @@@   @@@ @@  @@
    // @@@@@@ @@@@@@ @@@@   @@@@@    @@@@   @@  @@ @@ @@@ @@ @@  @@
    // @@     @@     @@ @@  @@  @@   @@ @@  @@  @@ @@  @  @@ @@  @@
    // @@     @@@@@@ @@  @@ @@@@@    @@  @@ @@@@@@ @@     @@ @@  @@
    // получить реквизиты компании
    private function getCompanyRequisites($id)
    {
        $u = new DB('user_rekv_type', 'urt');
        $u->select('ur.id, ur.value, urt.code rekv_code, urt.size, urt.title');
        $u->leftJoin('user_rekv ur', "ur.rekv_code = urt.code AND ur.id_author = {$id}");
        $u->groupBy('urt.code, urt.id, ur.id, urt.size, urt.title');
        $u->orderBy('urt.id');
        return $u->getList();
    }

    // @@@@@@  @@@@@@ @@  @@ @@@@@@ @@@@@@
    // @@      @@  @@ @@  @@ @@  @@ @@  @@
    // @@      @@@@@@  @@@@  @@  @@ @@  @@
    // @@      @@       @@   @@  @@ @@  @@
    // @@      @@       @@   @@  @@ @@  @@
    // получить группы
    private function getGroups($id)
    {
        $u = new DB('se_group', 'sg');
        $u->select('sg.id, sg.title name');
        $u->innerjoin('se_user_group sug', 'sg.id = sug.group_id');
        $u->where('sg.title IS NOT NULL AND sg.name <> "" AND sg.name IS NOT NULL AND sug.user_id = ?', $id);
        return $u->getList();
    }


    // @@@@@@@@ @@@@@@    @@    @@  @@ @@@@@@    @@    @@    @@ @@@@@@@@
    //    @@    @@  @@   @@@@   @@  @@ @@       @@@@   @@   @@@    @@
    //    @@    @@@@@@  @@  @@  @@@@@@ @@      @@  @@  @@  @@@@    @@
    //    @@    @@     @@@@@@@@ @@  @@ @@     @@    @@ @@@@  @@    @@
    //    @@    @@     @@    @@ @@  @@ @@@@@@ @@    @@ @@@   @@    @@
    // транслит (перевод знаков в латинский алфавит)
    private function getUserName($lastName, $userName, $id = 0)
    {
        // преобразование $userName в транслит
        if (empty($userName))
            $userName = strtolower(rus2translit($lastName));
        $username_n = $userName;

        $u = new DB('se_user', 'su');
        $i = 2;
        while ($i < 1000) {
            if ($id)
                $result = $u->findList("su.username='$username_n' AND id <> $id")->fetchOne();
            else $result = $u->findList("su.username='$username_n'")->fetchOne();
            if ($result["id"])
                $username_n = $userName . $i;
            else return $username_n;
            $i++;
        }
        return uniqid();
    }

    // @@@@@@ @@@@@@ @@  @@ @@@@@@@@    @@
    // @@  @@ @@  @@ @@  @@    @@      @@@@
    // @@  @@ @@  @@ @@@@@@    @@     @@  @@
    // @@  @@ @@  @@     @@    @@    @@@@@@@@
    // @@  @@ @@@@@@     @@    @@    @@    @@
    // Добавить адрес электронной почты в адресную книгу
    private function addInAddressBookEmail($idsContacts, $idsNewsGroups, $idsDelGroups)
    {
        $emails = array();
        $u = new DB('person');
        $u->select("email, concat_ws(' ', first_name, sec_name) as name");
        $u->where('id IN (?)', implode(",", $idsContacts));
        $u->andWhere('email IS NOT NULL');
        $u->andWhere('email <> ""');
        $list = $u->getList();
        foreach ($list as $value) {
            if (se_CheckMail($value['email']))
                $emails[] = array(
                    'email' => $value['email'],
                    'variables' => array('name' => $value['name'])
                );
        }
        if (empty($emails))
            return;

        if ($idsNewsGroups && ($idsBooks = ContactCategory::getIdsBooksByIdGroups($idsNewsGroups))) {
            $emailProvider = new EmailProvider();
            $emailProvider->addEmails($idsBooks, $emails);
        }
        if ($idsDelGroups && ($idsBooks = ContactCategory::getIdsBooksByIdGroups($idsDelGroups))) {
            $emailProvider = new EmailProvider();
            $emailProvider->removeEmails($idsBooks, $emails);
        }
    }

    // @@@@@@ @@@@@@ @@  @@ @@@@@@   @@@@@@  @@@@@@ @@  @@ @@@@@@
    // @@     @@  @@  @@@@  @@  @@   @@      @@  @@ @@  @@ @@  @@
    // @@     @@  @@   @@   @@@@@@   @@      @@@@@@  @@@@  @@  @@
    // @@     @@  @@  @@@@  @@       @@      @@       @@   @@  @@
    // @@@@@@ @@@@@@ @@  @@ @@       @@      @@       @@   @@  @@
    // сохранеие группы
    private function saveGroups($groups, $idsContact, $addGroup = false)
    {
        try {
            $newIdsGroups = array();
            foreach ($groups as $group)
                $newIdsGroups[] = $group["id"];
            $idsGroupsS = implode(",", $newIdsGroups);
            $idsContactsS = implode(",", $idsContact);

            if (!$addGroup) {
                $u = new DB('se_user_group', 'sug');
                $u->select("id, group_id, user_id");
                if ($newIdsGroups)
                    $u->where("NOT group_id IN ($idsGroupsS) AND user_id IN ($idsContactsS)");
                else
                    $u->where("user_id IN ($idsContactsS)");
                $groupsDel = $u->getList();

                $idsGroupsDelEmail = array();
                foreach ($groupsDel as $group)
                    $idsGroupsDelEmail[$group["userId"]][] = $group["groupId"];
                $u->deleteList();

                //writeLog($idsGroupsDelEmail);
                foreach ($idsGroupsDelEmail as $userId => $gr) {
                    if (!empty($gr) && $userId) {
                        $this->addInAddressBookEmail(array($userId), false, $gr);
                    }
                }
            }

            $u = new DB('se_user_group', 'sug');
            $u->select("group_id, user_id");
            $u->where("user_id IN ($idsContactsS)");
            $objects = $u->getList();

            $idsExists = array();
            foreach ($objects as $object)
                $idsExists[$object["userId"]][] = $object["groupId"];

            if (!empty($newIdsGroups)) {
                $data = array();
                foreach ($newIdsGroups as $id) {
                    $idsContactsNewEmail = array();
                    if (!empty($id)) {
                        foreach ($idsContact as $idContact) {
                            if (!in_array($id, $idsExists[$idContact])) {
                                $data[] = array('user_id' => $idContact, 'group_id' => $id);
                                $idsContactsNewEmail[] = $idContact;
                            }
                        }
                    }
                    if (!empty($idsContactsNewEmail))
                        $this->addInAddressBookEmail($idsContactsNewEmail, array($id), array());
                }
                if (!empty($data)) {
                    DB::insertList('se_user_group', $data);
                }
            }
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить группы контакта!";
            throw new Exception($this->error);
        }
    }


    // @@@@@@ @@@@@@ @@  @@ @@@@@@   @@@@@@ @@@@@@ @@  @@ @@@@@  @@    @@ @@@@@@
    // @@     @@  @@  @@@@  @@  @@   @@  @@ @@     @@ @@  @@  @@ @@   @@@     @@
    // @@     @@  @@   @@   @@@@@@   @@@@@@ @@@@@@ @@@@   @@@@@  @@  @@@@ @@@@@
    // @@     @@  @@  @@@@  @@       @@     @@     @@ @@  @@  @@ @@@@  @@     @@
    // @@@@@@ @@@@@@ @@  @@ @@       @@     @@@@@@ @@  @@ @@@@@  @@@   @@ @@@@@@
    // Сохранить реквизиты компании
    private function saveCompanyRequisites($id, $input)
    {
        try {
            foreach ($input["companyRequisites"] as $requisite) {
                $u = new DB("user_rekv");
                $requisite["idAuthor"] = $id;
                $u->setValuesFields($requisite);
                $u->save();
            }
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить реквизиты компании!";
            throw new Exception($this->error);
        }
    }


    // @@@@@@ @@@@@@ @@  @@ @@@@@@      @@    @@  @@ @@  @@
    // @@     @@  @@  @@@@  @@  @@     @@@@   @@ @@  @@ @@
    // @@     @@  @@   @@   @@@@@@    @@  @@  @@@@   @@@@
    // @@     @@  @@  @@@@  @@       @@@@@@@@ @@ @@  @@ @@
    // @@@@@@ @@@@@@ @@  @@ @@       @@    @@ @@  @@ @@  @@
    // Сохранить личные аккаунты
    private function savePersonalAccounts($id, $accounts)
    {
        try {
            $idsUpdate = null;
            foreach ($accounts as $account)
                if ($account["id"]) {
                    if (!empty($idsUpdate))
                        $idsUpdate .= ',';
                    $idsUpdate .= $account["id"];
                }

            $u = new DB('se_user_account', 'sua');
            if (!empty($idsUpdate))
                $u->where("NOT id IN ($idsUpdate) AND user_id = ?", $id)->deleteList();
            else $u->where("user_id = ?", $id)->deleteList();

            foreach ($accounts as $account) {
                $u = new DB('se_user_account');
                $account["userId"] = $id;
                $u->setValuesFields($account);
                $u->save();
            }
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить лицевой счёт контакта!";
            throw new Exception($this->error);
        }
    }

    // @@@@@@    @@    @@@@@@     @@    @@@@@@@@ @@         @@@@@@  @@@@@@ @@  @@ @@@@@@
    //     @@   @@@@   @@   @@   @@@@      @@    @@         @@      @@  @@ @@  @@ @@  @@
    // @@@@@   @@  @@  @@   @@  @@  @@     @@    @@@@@@     @@      @@@@@@  @@@@  @@  @@
    //     @@ @@@@@@@@ @@   @@ @@@@@@@@    @@    @@  @@     @@      @@       @@   @@  @@
    // @@@@@@ @@    @@ @@@@@@  @@    @@    @@    @@@@@@     @@      @@       @@   @@  @@
    // задать группу пользователя
    private function setUserGroup($idUser)
    {
        try {
            $u = new DB('se_group', 'sg');
            $u->select("id");
            $u->where("title = 'User'");
            $result = $u->fetchOne();
            $idGroup = $result["id"];
            if (!$idGroup) {
                $u = new DB('se_group', 'sg');
                $data["title"] = "User";
                $data["level"] = 1;
                $u->setValuesFields($data);
                $idGroup = $u->save();
            }

            $u = new DB('se_user_group', 'sug');
            $u->select("id");
            $u->where("sug.group_id = {$idGroup} AND sug.user_id = {$idUser}");
            $result = $u->fetchOne();
            $id = $result["id"];
            if (!$id) {
                $u = new DB('se_user_group', 'sug');
                $data["groupId"] = $idGroup;
                $data["userId"] = $idUser;
                $u->setValuesFields($data);
                $u->save();
            }
        } catch (Exception $e) {
            $this->error = "Не удаётся сохранить группу контакта!";
            throw new Exception($this->error);
        }
    }

    // @@@@@@ @@@@@@ @@  @@ @@@@@@   @@@@@@ @@@@@@    @@    @@     @@@@@@
    // @@     @@  @@  @@@@  @@  @@   @@  @@ @@  @@   @@@@   @@         @@
    // @@     @@  @@   @@   @@@@@@   @@  @@ @@  @@  @@  @@  @@@@@@ @@@@@
    // @@     @@  @@  @@@@  @@       @@  @@ @@  @@ @@    @@ @@  @@     @@
    // @@@@@@ @@@@@@ @@  @@ @@       @@  @@ @@@@@@ @@    @@ @@@@@@ @@@@@@
    // сохранить пользовательские поля
    private function saveCustomFields()
    {
        // если присутствуют нстраиваемые поля - передать правду
        if (!isset($this->input["customFields"]))
            return true;

        // сохраняем информацию о контакте
        try {
            $idContact = $this->input["id"];
            $groups = $this->input["customFields"];
            $customFields = array();
            foreach ($groups as $group)
                foreach ($group["items"] as $item)
                    $customFields[] = $item;
            foreach ($customFields as $field) {
                $field["idPerson"] = $idContact;
                $u = new DB('person_userfields', 'cu');
                $u->setValuesFields($field);
                $u->save();
            }
            return true;
        } catch (Exception $e) {
            // ошибка сохранения
            $this->error = "Не удаётся сохранить доп. информацию о контакте!";
            throw new Exception($this->error);
        }
    }


    // @@@@@@ @@@@@@ @@  @@ @@@@@@
    // @@     @@  @@  @@@@  @@  @@
    // @@     @@  @@   @@   @@@@@@
    // @@     @@  @@  @@@@  @@
    // @@@@@@ @@@@@@ @@  @@ @@
    // сохранить
    public function save($contact = null)
    {
        try {
            // добавляем группу (привязка по ids)
            if ($contact)
                $this->input = $contact;
            if ($this->input["add"] && !empty($this->input["ids"]) && !empty($this->input["groups"])) {
                $this->saveGroups($this->input["groups"], $this->input["ids"], true);
                $this->info();
                return $this;
            }
            if ($this->input["upd"] && !empty($this->input["ids"]) && !empty($this->input["groups"])) {
                $this->saveGroups($this->input["groups"], $this->input["ids"]);
                $this->info();
                return $this;
            }
            if ($this->input["upd"] && !empty($this->input["ids"]) && isset($this->input["priceType"])) {
                $priceType = intval($this->input["priceType"]);
                $ids = join(',', $this->input["ids"]);
                DB::query("UPDATE `person` SET `price_type`='{$priceType}' WHERE id IN ({$ids})");
                $this->info();
                return $this;
            }


            // начать транзакцию БД
            DB::beginTransaction();

            $ids = array();
            // если ids пустой и id определен: добавляем в ids
            if (empty($this->input["ids"]) && !empty($this->input["id"]))
                $ids[] = $this->input["id"];
            else $ids = $this->input["ids"];
            // инициализация поля ввода нового пользователя
            $isNew = empty($ids);
            // если присутствует логин, то выдаем логин, иначе ноль
            $userName = isset($this->input["login"]) ? $this->input["login"] : null;
            if (!empty($this->input["birthDate"]))
                $this->input["birthDate"] = date("Y-m-d", strtotime($this->input["birthDate"]));
            if (!empty($this->input["regDate"]))
                $this->input["regDate"] = date("Y-m-d", strtotime($this->input["regDate"]));
            // создаем новый контакт
            if ($isNew) {
                // разбиваем ФИО на фамилию, имя, отчество
                $lastfirstsec = explode(" ", $this->input["firstName"]);
                if (count($lastfirstsec) == 1) {
                    $this->input["firstName"] = $lastfirstsec[0];
                } elseif (count($lastfirstsec) == 2) {
                    $this->input["lastName"] = $lastfirstsec[0];
                    $this->input["firstName"] = $lastfirstsec[1];
                } elseif (count($lastfirstsec) == 3) {
                    $this->input["lastName"] = $lastfirstsec[0];
                    $this->input["firstName"] = $lastfirstsec[1];
                    $this->input["secName"] = $lastfirstsec[2];
                } elseif (count($lastfirstsec) > 3) {
                    $this->input["lastName"] = $lastfirstsec[0];
                    $this->input["firstName"] = $lastfirstsec[1];
                    $secN = array_slice($lastfirstsec, 2);
                    $secN = implode(" ", $secN);
                    $this->input["secName"] = $secN;
                } else {
                    throw new Exception("Не удаётся сохранить контакт!");
                }

                // если ИМЯ не пустое, то удаляя пробелы в начале и конце строки выдаем ИМЯ, в ином случае передаем фамилию
                $login = !empty($this->input["lastName"]) ? trim($this->input["lastName"]) : $this->input["firstName"]; // входной параметр
                $this->input["username"] = $this->getUserName($login, $userName);
                if (!empty($this->input["username"])) {
                    // таблиблица в БД, куда передается username
                    $u = new DB('se_user', 'su');
                    $u->setValuesFields($this->input);
                    $ids[] = $u->save();
                }
            } else {
                $u = new DB('se_user', 'su');
                if (!empty($this->input["login"])) {
                    $login = !empty($this->input["lastName"]) ? trim($this->input["lastName"]) : $this->input["firstName"];
                    $this->input["username"] = $this->getUserName($login, $userName, $ids[0]);
                }
                $u->setValuesFields($this->input);
                $u->save();
            }

            // если поле нового контакта не пустое...
            if ($isNew || !empty($ids)) {
                if ($isNew)
                    $this->input["regDate"] = date("Y-m-d H:i:s");
                $this->input["id"] = $ids[0];
                if (isset($this->input["imageFile"]))
                    $this->input["avatar"] = $this->input["imageFile"];
                $u = new DB('person', 'p'); // вписываем БД
                $u->setValuesFields($this->input);
                $id = $u->save($isNew);
                if (empty($id))
                    throw new Exception("Не удаётся сохранить контакт!");

                // обработать имя $this->input // explode(...)

                $u = new DB('user_urid', 'uu');
                $u->setValuesFields($this->input);
                $u->save(true);

                $this->saveCompanyRequisites($ids[0], $this->input);
                if ($ids && isset($this->input["personalAccount"]))
                    $this->savePersonalAccounts($ids[0], $this->input["personalAccount"]);
                if (isset($this->input["isAdmin"]) && $this->input["isAdmin"])
                    $this->input["idsGroups"][] = 3;
                if ($ids && isset($this->input["groups"]))
                    $this->saveGroups($this->input["groups"], $ids);
                else {
                    if (isset($this->input["isAdmin"]) && !$this->input["isAdmin"]) {
                        $u = new DB('se_user_group', 'sug');
                        $u->where('group_id = 3 AND user_id = ?', $ids[0])->deleteList();
                    }
                }
                $this->setUserGroup($ids[0]);
                if ($ids && isset($this->input["customFields"]))
                    $this->saveCustomFields();
            }
            DB::commit();
            $this->info();

            return $this;
        } catch (Exception $e) {
            DB::rollBack();
            $this->error = empty($this->error) ? "Не удаётся сохранить контакт!" : $this->error;
        }
    }

    public function export()
    {
        /**
         * экспорт
         *
         * @param array $rusCols    ключи-заголовки столбцов
         * @param array $contacts   массив контактов с ассоциативными колонками
         * @param array $writeArray массив строк-столбцов array(0=>array(0=>sdfs,1=>sdgdg...), 1=>array(0=>sdfs,1=>sdgdg...))
         */

        /** проверяем на наличие записей в id */
        if (!empty($this->input["id"])) {
            $this->exportItem();
            return;
        }

        /** объявляем переменные */
        $fileName = "export_persons.xlsx";
        $tempFilePath = DOCUMENT_ROOT . "/files/tempfiles";
        if (!file_exists($tempFilePath) || !is_dir($tempFilePath))
            mkdir($tempFilePath);
        $filePath = $tempFilePath . "/{$fileName}";
        $urlFile = 'http://' . HOSTNAME . "/files/tempfiles/{$fileName}";

        $this->rmdir_recursive($tempFilePath);
        /** очистка директории с временными файлами */
        if (!file_exists($tempFilePath) || !is_dir($tempFilePath))
            mkdir($tempFilePath, 0766, true);
        /** рекурсивное создание директорий */

        /** поднимаем записи из БД */
        $rusCols = array(
            "regDateTime"   => "Время регистрации",
            "username"      => "Код",
            "lastName"      => "Фамилия",
            "firstName"     => "Имя",
            "secName"       => "Отчество",
            "gender"        => "Пол",
            "birthDate"     => "Дата рождения",
            "email"         => "Email",
            "phone"         => "Телефон",
            "note"          => "Заметка"
        );

        $u = new DB('person', 'p');
        //$u->select('p.reg_date regDateTime, su.username, p.last_name, p.first_name Name, p.sec_name patronymic,
        //    p.sex gender, p.birth_date, p.email, p.phone, p.note');
        //$u->select('p.reg_date regDateTime, su.username, p.last_name, p.first_name, p.sec_name,
        //    p.sex gender, p.birth_date, p.email, p.phone, p.note');
        $u->select('p.reg_date regDateTime, su.username, p.last_name, p.first_name, p.sec_name, 
            p.sex gender, p.birth_date, p.email, p.phone, p.note');
        $u->innerJoin('se_user su', 'p.id = su.id');
        $u->leftJoin('se_user_group sug', 'p.id = sug.user_id');
        $u->groupBy('p.id');
        $u->orderBy('p.id');
        $contacts = $u->getList();

        /** формирование массива на запись */
        $writeArray = array(0 => array());
        $columnNumbers = array();
        $num = 0;
        foreach ($rusCols as $k => $i) {
            array_push($writeArray[0], $i);
            $columnNumbers[$k] = $num;
            $num += 1;
        }
        unset($rusCols);
        foreach ($contacts as $k => $i) {
            $writeArrayUnit = array();
            foreach ($i as $k2 => $i2)
                $writeArrayUnit[$columnNumbers[$k2]] = $i2;
            array_push($writeArray, $writeArrayUnit);
            unset($contacts[$k]);
        }
        unset($contacts);

        /** запись в файл xlsx */
        $writer = WriterFactory::create(Type::XLSX);
        $writer->setTempFolder($tempFilePath);
        /** директория хранения временных файлов */
        $writer->openToFile($filePath);
        /** директория сохраниния XLSX */
        $writer->addRows($writeArray);
        $writer->close();
        unset($writer, $writeArray);

        /** передача в JS */
        if (file_exists($filePath) && filesize($filePath)) {
            $this->result['url'] = $urlFile;
            $this->result['name'] = $fileName;
        } else $this->result = "Не удаётся экспортировать контакты!";
    } // экспорт

    private function exportItem()
    {
        /**
         * экспорт контактА
         *
         * @param array $contact     вся информация по контакту
         * @param array $columnValue заголовок-значение информации по контакту
         * @param array $writeArray массив строк-столбцов array(0=>array(0=>sdfs,1=>sdgdg...), 1=>array(0=>sdfs,1=>sdgdg...))
         */
        /** проверка параметров / библиотек */
        $idContact = $this->input["id"];
        if (!$idContact) {
            $this->result = "Отсутствует параметр: id контакта!";
            return;
        }

        /** инициализация контакта */
        $contact = new Contact();
        $contact = $contact->info($idContact);

        /** сборка данных */
        $columnValue = array(
            'Ид. № ' . $contact["id"] => '',
            'Ф.И.О.'                  => $contact["displayName"],
            'Телефон:'                => $contact["phone"],
        );
        if ($contact["email"])  $columnValue['Эл. почта:'] = $contact["email"];
        if ($contact["addr"])   $columnValue['Адрес:']     = $contact["addr"];
        if ($contact["docSer"]) $columnValue['Документ:']  = $contact["docSer"] . " " . $contact["docNum"] . " " . $contact["docRegistr"];

        /** формирование массива на запись*/
        $writeArray = array();
        foreach ($columnValue as $k => $i) {
            $unit = array(0 => $k, 1 => $i);
            $writeArray[] = $unit;
        }

        /** объявляем переменные */
        $fileName = "export_person_{$idContact}.xlsx";
        $tempFilePath = DOCUMENT_ROOT . "/files/tempfiles";
        if (!file_exists($tempFilePath) || !is_dir($tempFilePath))
            mkdir($tempFilePath);
        $filePath = $tempFilePath . "/{$fileName}";
        $urlFile = 'http://' . HOSTNAME . "/files/tempfiles/{$fileName}";

        $this->rmdir_recursive($tempFilePath);
        /** очистка директории с временными файлами */
        if (!file_exists($tempFilePath) || !is_dir($tempFilePath))
            mkdir($tempFilePath, 0766, true);
        /** рекурсивное создание директорий */

        /** запись в файл xlsx */
        $writer = WriterFactory::create(Type::XLSX);
        $writer->setTempFolder($tempFilePath);
        /** директория хранения временных файлов */
        $writer->openToFile($filePath);
        /** директория сохраниния XLSX */
        $writer->addRows($writeArray);
        $writer->close();
        unset($writer, $writeArray);

        /** передача в JS */
        if (file_exists($filePath) && filesize($filePath)) {
            $this->result['url'] = $urlFile;
            $this->result['name'] = $fileName;
        } else $this->result = "Не удаётся экспортировать данные контакта!";
    } // экспорт контактА

    // @@@@@@ @@@@@@ @@@@@@ @@@@@@@@
    // @@  @@ @@  @@ @@        @@
    // @@  @@ @@  @@ @@        @@
    // @@  @@ @@  @@ @@        @@
    // @@  @@ @@@@@@ @@@@@@    @@
    // пост
    public function post($tempFile = false)
    {
        if ($items = parent::post(true)) {
            $this->import($items[0]["name"], $_POST);
        }
    }

    public function import($fileName, $param)
    {
        /**
         * импорт (обновление!!)
         *
         * @param  array $fileName       имя файла
         * @param  array $param          delimiter разделит столбцов, limiterField ограничитель поля, skip отступ
         * @param  array $headers        упорядоченный массив заголовков в БД формате
         * @param  array $contacts       массив строк файла  array{0=>array(0=>'dfg',1=>'dsg'),1=>array(...)...}
         * @param  array $enCols         заголовки в БД
         * @param  array $arrayNewRow    оработанная строка
         * @param  array $newArray       массив с разложенными данными
         * @param  array $arrayUserName  массив кодов пользователей (оригинальных значений)
         * @method array getDataFromFile получаем массив из файла
         * @method array importHandler   раскладываем файл на параметры
         * @method array save            сохранение по одному
         * @method array rmdir_recursive очистка директории с временными файлами
         */

        $enCols   = array(
            "Время регистрации"     => "regDateTime",
            "Код"                   => "username",
            "Фамилия"               => "lastName",
            "Имя"                   => "firstName",
            "Отчество"              => "secName",
            "Пол"                   => "gender",
            "Дата рождения"         => "birthDate",
            "Email"                 => "email",
            "Телефон"               => "phone",
            "Заметка"               => "note"
        );

        $skip     = $param["skip"];
        $path     = DOCUMENT_ROOT . "/files/tempfiles";
        $filePath = $path . "/{$fileName}";

        $contacts = $this->getDataFromFile($fileName, $filePath, $param);
        $newArray = $this->importHandler($contacts, $enCols, $skip);
        foreach ($newArray as $k => $i)  $this->save($i);

        $this->rmdir_recursive($path);
        if (!file_exists($path) || !is_dir($path))
            mkdir($path, 0766, true);
        /** рекурсивное создание директорий */
    } // импорт (обновление!!)

    private function importHandler($contacts, $enCols, $skip)
    {
        /** раскладываем файл */

        $headers       = array();
        $newArray      = array();
        $arrayUserName = array();
        foreach ($contacts as $k => $row) {

            if ($k == 0) {
                /** линия заголовка */
                foreach ($row as $kCell => $cell)
                    if ($enCols[$cell]) $headers[] = $enCols[$cell];
            } elseif ($k >= $skip) {
                /** если обычные строки (с учетом пользовательского отступа) */
                $arrayNewRow = array();
                foreach ($row as $kCell => $cell) {
                    $key = $headers[$kCell];
                    $arrayNewRow[$key] = $cell;
                }

                /** фильтрация пустых значений */
                if ($arrayNewRow['birthDate'] === '0000-00-00')
                    unset($arrayNewRow['birthDate']);
                foreach ($arrayNewRow as $kHead => $head)
                    if ($head == '')  unset($arrayNewRow[$kHead]);


                $newArray[]                                     = $arrayNewRow;
                if ($arrayNewRow["username"])  $arrayUserName[] = '"' . $arrayNewRow["username"] . '"';
                unset($contacts[$k]);
            } else {
            }
        }
        unset($contacts);


        /** получение связки код-id (если есть) */
        $u = new DB('se_user', 'su');
        $u->select('id, username');
        $u->where('username in (?)', implode(",", $arrayUserName));
        //$u->andWhere('p.sec_name   = "?"', implode(",", $secName)); // проверка по отчеству
        $arrayDataId = $u->getList();

        $nameId = array();
        foreach ($arrayDataId as $k => $i) {
            $nameId[$i["username"]] = $i["id"];
            unset($arrayDataId[$k]);
        }
        unset($arrayDataId);

        foreach ($newArray as $k => $i)
            if ($nameId[$i["username"]])  $newArray[$k]["id"] = $nameId[$i["username"]];

        return $newArray;
    } // раскладываем файл

    private function getDataFromFile($filename, $filePath, $options)
    {
        /** Получить данные из файла
         * @param  str        $filename
         * @param  array      $options  параметры пользователя из первого шага импорта
         * @return array                массив строк файла  array{0=>array(0=>'dfg',1=>'dsg'),1=>array(...)...}
         * @throws \Exception
         */

        try {
            $temporaryFilePath = DOCUMENT_ROOT . "/files/tempfiles/";
            $file              = $temporaryFilePath . $filename;
            if (file_exists($file) and is_readable($file)) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);

                if ($extension == 'xlsx')
                    return $this->getDataFromFileXLSX($filePath);
                elseif ($extension == 'csv')
                    return $this->getDataFromFileCSV($file, $options);
                else
                    writeLog('НЕ КОРРЕКТНОЕ РАСШИРЕНИЕ ФАЙЛА ' . $file);
            } else {
                writeLog('ФАЙЛА НЕТ ' . $file);
            }
        } catch (Exception $e) {
            writeLog($e->getMessage());
        }
    } // Получить данные из файла

    private function getDataFromFileXLSX($filePath)
    {
        /**
         * чтение файлов xlsx в windows1251 и utf-8
         * @param obj   $reader    объект с ячейками эксель
         * @param array $arrayRows массив строк файла  array{0=>array(0=>'dfg',1=>'dsg'),1=>array(...)...}
         */

        $arrayRows = array();
        $reader = ReaderFactory::create(Type::XLSX);
        $reader->open($filePath);
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                if (!is_array($row)) $row = array();
                foreach ($row as $k => $cell) {
                    $encoding = mb_check_encoding($cell, 'UTF-8');
                    if ($encoding != 1) $row[$k] = mb_convert_encoding($cell, 'utf-8', 'windows-1251');
                }
                array_push($arrayRows, $row);
            }
        }
        $reader->close();

        return $arrayRows;
    } // чтение файлов xlsx в windows1251 и utf-8

    private function getDataFromFileCSV($file, $options)
    {
        /**
         * чтение файлов csv в windows1251 и utf-8 (в том числе с автоопределителем разделителя в csv)
         * @param  string $delimiter    разделитель колонок
         * @param  int    $skip         отступ (строк)
         * @param  string $limiterField разделитель вложенного текста
         * @param  obj    $handle       корректный файловый указатель на файл
         * @param  array  $line         массив ячеек строчки
         * @return array  $arrayRows    массив строк файла  array{0=>array(0=>'dfg',1=>'dsg'),1=>array(...)...}
         */

        $arrayRows    = array();
        $delimiter    = $options['delimiter'];
        $skip         = $options['skip'];
        $limiterField = $options['limiterField'];

        /** автоопределитель разделителя (чувствителен к порядку знаков - по убывающей приоритетности) */
        if ($delimiter == 'auto') {
            $delimiters_first_line  = array('\t' => 0, ';'  => 0, ':'  => 0);
            $delimiters_second_line = $delimiters_first_line;
            $delimiters_final       = array();

            /** читаем первые 2 строки для обработки (вторая строка с учетом пользовательского отступа) */
            $handle      = fopen($file, 'r');
            $first_line  = fgets($handle);
            for ($c = 0; $c < $skip; $c++)  $second_line = fgets($handle);
            fclose($handle);

            /** производим подсчет знаков из $delimiters_first/second_line в обеих строках */
            foreach ($delimiters_first_line as $delimiter => &$count)
                $count = count(str_getcsv($first_line, $delimiter, $limiterField));
            foreach ($delimiters_second_line as $delimiter => &$count)
                $count = count(str_getcsv($second_line, $delimiter, $limiterField));
            $delimiter = array_search(max($delimiters_first_line), $delimiters_first_line);

            /** сопоставляем колво знаков - совпадает, в $delimiters_final */
            foreach ($delimiters_first_line as $key => $value)
                if ($delimiters_first_line[$key] == $delimiters_second_line[$key])
                    $delimiters_final[$key] = $value;

            /** получаем максимальное совпадение из $delimiters_final - переназначаем разделитель с ";" */
            if (count($delimiters_final) > 1) {
                $delimiters_final2 = array_keys($delimiters_final, max($delimiters_final));
                foreach ($delimiters_final2 as $value) $delimiter = $value;
            } else
                foreach ($delimiters_final as $key => $value) $delimiter = $key;
        };

        /** формируем массив */
        if (($handle = fopen($file, "r")) !== FALSE) {
            /** перебирает все строчки */
            while (($line = fgetcsv($handle, 10000, $delimiter, $limiterField)) !== FALSE) {

                $num = count($line);
                $row = array();

                for ($c = 0; $c < $num; $c++) {
                    $auto_encoding = mb_check_encoding($line[$c], 'UTF-8');
                    if ($auto_encoding != 1) $cell = mb_convert_encoding($line[$c], 'UTF-8', "windows-1251");
                    else                    $cell = $line[$c];
                    $row[$c] = $cell;
                }
                $arrayRows[] = $row;
            }
            fclose($handle);

            return $arrayRows;
        }
    } // чтение файлов csv в windows1251 и utf-8

    static public function correctPhone($phone)
    {
        $phoneIn = $phone;
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) < 10)
            return $phoneIn;

        if (strlen($phone) == 10)
            $phone = '7' . $phone;
        if ((strlen($phone) == 11) && ($phone[0] == '8'))
            $phone[0] = 7;
        $result = null;
        for ($i = 0; $i < strlen($phone); $i++) {
            $result .= $phone[$i];
            if ($i == 0)
                $result .= ' (';
            if ($i == 3)
                $result .= ') ';
            if ($i == 6 || $i == 8)
                $result .= '-';
        }
        return '+' . $result;
    }
}
