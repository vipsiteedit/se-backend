<?php

class plugin_registration {

            
            $user = new seUser();
            $user->upid         =   $_id_up;
            $user->last_name    =   $_last_name;
            $user->first_name   =   $_first_name;
            $user->sec_name     =   $_sec_name;
            $user->login        =   $_email;
            $user->access_group =   1;
            $user->email        =   $_email;
            $user->password     =   $password;
            $user->reg_date     =   $reg_date;
            $user->is_active    =   'Y';
            $id_num = $user->save();

            // Открываем доступ
            check_session(true); // Удаляем старую сессию
            $SESSION_VARS['IDUSER'] = $id_num;
            $SESSION_VARS['GROUPUSER'] = 1;
            $SESSION_VARS['USER'] = $_first_name." ".$_sec_name;
            $SESSION_VARS['AUTH_USER'] = $_email;
            $SESSION_VARS['AUTH_PW'] = $password;
            $SESSION_VARS['ADMINUSER'] = $_id_up;
            check_session(false); // Создаем новое вхождение авторизации

}
 
?>