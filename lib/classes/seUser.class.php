<?php
require_once dirname(__FILE__) . "/base/seBaseUser.class.php";

/**
 * @filesource seUser.class.php
 * @copyright EDGESTILE
 */
class seUser extends seBaseUser
{

	public function getAccess()
	{
		$group = new seGroup();
		$group->select('g.level,g.name');
		$group->innerjoin('se_user_group ug', "g.id=ug.group_id");
		$group->where('ug.user_id=?', $this->table_id);
		return $group->getList();
	}

	public function getPerson()
	{
		$person = new sePerson();
		$person->find($this->id);
		$person->id = $this->id;
		return $person;
	}

	// ������������� ����� ������� ������������
	// 0 - �������, 1 - ��� ������
	public function setAccess($arr = array(array(1, '')))
	{
		if ($this->table_id) {
			$usergroup = new seUserGroup();
			$usergroup->where('user_id=?', $this->table_id);
			$usergroup->deletelist();
			$group = new seGroup();
			foreach ($arr as $access) {
				if ($access[0] < 1 || $access[0] > 3) $access[0] = 1;
				$group->where("level=?", $access[0]);
				if ($access[1]) {
					$group->andwhere("name='?'", $access[1]);
				} else {
					$group->andwhere("(name='' OR name IS NULL)");
				}

				$group->fetchOne();
				$group_id = $group->id;
				if (!$group_id) {
					$group->level = $access[0];
					$group->name = $access[1];
					$group_id = $group->save();
				}
				$usergroup->where('group_id=?', $group_id);
				$usergroup->andwhere('user_id=?', $this->table_id);
				$usergroup->fetchOne();
				if (!$usergroup->isFind()) {
					$usergroup->group_id = $group_id;
					$usergroup->user_id = $this->table_id;
					$usergroup->save();
				}
			}
		}
	}

	public function registration($req = array(), $parent_id = 0, $level = 1, $namegroup = '')
	{
		if (empty($req)) {
			getRequestList($req, 'first_name, last_name, sec_name,  email, phone, username, passw, confirm', 3);
		}
		$username = $req['username'];

		if (!$req['first_name'])
			return 'err:first_name';
		elseif (!se_CheckMail($req['email']))
			return 'err:email';
		elseif (!$req['passw'])
			return 'err:passw';
		elseif ($req['passw'] != $req['confirm'])
			return 'err:passw-confirm';
		else {
			$parent_id = intval($parent_id || ($_SESSION['REFER']));
			if (!$parent_id) {
				$parent_id = null;
			}
			$password = md5($req['passw']);
			$email = $req['email'];
			$this->select();
			$this->where("username='?'", $username);
			$this->fetchOne();
			if ($this->isFind()) {
				return 'err:exists ' . $username;
			} else {
				se_db_query("START TRANSACTION");
				$this->username     =   $username;
				$this->password     =   $password;
				$this->is_active    =   'Y';
				if ($id_num = $this->save()) {
					$this->setAccess(array(array(1, $namegroup)));
					$person = new sePerson();;
					$person->id         = $id_num;
					if (isset($_SESSION['SITE_REFERER'])) {
						$person->addField('referer', 'text');
						$person->referer = $_SESSION['SITE_REFERER'];
					}
					if (!empty($parent_id)) {
						$person->id_up      = $parent_id;
					}
					$person->last_name  = $req['last_name'];
					$person->first_name = $req['first_name'];
					$person->sec_name     =   $req['sec_name'];
					$person->email      = $req['email'];
					if (!empty($req['birth_date']))
						$person->birth_date  = $req['birth_date'];

					if (!empty($req['country_id']))
						$person->country_id = $req['country_id'];

					if (!empty($req['state_id']))
						$person->state_id = $req['state_id'];

					if (!empty($req['town_id']))
						$person->town_id = $req['town_id'];

					if (!empty($req['addr']))
						$person->addr = $req['addr'];

					if (!empty($req['phone']))
						$person->phone      = $req['phone'];
					if (!empty($req['subscriber_news']))
						$person->subscriber_news = $req['subscriber_news'];
					$person->reg_date   = date("Y-m-d H:i:s");
					if ($person->save()) {
						se_db_query("COMMIT");
						return $id_num;
					} else {
						echo se_db_error();
						se_db_query("ROLLBACK");
					}
				}
			}
		}
	}
}
