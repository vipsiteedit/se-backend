<?php

/** -------------------------------------------------------------- //
 * @param string $subject    -    Заголовок письма
 * @param string $to_email    -    Адрес назначения
 * @param string $from_email-    Адрес отправления
 * @param string $msg        -    Тело письма
 * @param string $contenttype    Тип отображения ( 'text/plain' - текст, 'text/html' - html )
 * @param string $filename    -    Имя отправляемог файла
 * @param string $mimetype    -    Тип файла( изображение image/jpeg файл application/octet-stream )
 * $mailfile = new plugin_mail( 'Загорловок', 'to@test.ru', 'from@test.ru',
 *             'текст письма', '', 'text/plain', 'файл 1;файл 2', 'application/octet-stream' );
 * $mailfile->sendfile();
 **/

class plugin_mail
{
    private $mail;

    public function __construct($subject, $to_email, $from_email, $msg, $contenttype = '', $filename = '', $mimetype = 'application/octet-stream')
    {
        if (!$contenttype) {
            $contenttype = 'text/plain';
        }

        if (strpos($from_email, '@mail.') !== false || strpos($from_email, '@bk.') !== false || strpos($from_email, '@list.') !== false || strpos($from_email, '@inbox.') !== false) {
            $from_email = 'noreply@' . str_replace('www.', '', $_SERVER['HTTP_HOST']);
        }

        $this->mail = new plugin_jmail(stripslashes($subject), $to_email, $from_email);
        $this->mail->addtext($msg, $contenttype);
        if (!empty($filename)) {
            $silelist = explode(';', $filename);
            foreach ($silelist as $file) {
                $file = trim($file);
                if (empty($file)) {
                    continue;
                }

                $this->mail->attach($file, '', $mimetype);
            }
        }
    }

    public function sendfile()
    {
        return $this->mail->send();
    }
}
