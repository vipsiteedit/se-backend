<?php

/** -------------------------------------------------------------- //
* Отправка почты с аттачем
* @param string $subject	-	тема_письма
* @param string $to_email	-	мыло@получателя
* @param string $from_email	-	мыло@отправителя
* @param string $msg		-	тело_письма(если нужно)
* @param string $contenttype	Тип письма ('text/plain' - текст, 'text/html' - html)
* @param string $filename	-	имя_файла
* @param string $filepath	-	путь_к_файлу
* @param string $mimetype	-	тип_аттача(например image/jpeg или application/octet-stream)
* @param string $mime_filename - определять тип по имени
* Пример: 
* $mailfile = new plugin_mail("тема_письма","мыло@получателя","мыло@отправителя",
* 			"тело_письма(если нужно)",'', "имя_файла","путь_к_файлу","тип_аттача(например image/jpeg)","");
* $mailfile->sendfile();
**/ 

class plugin_mail
{
    private $mail;
    
    public function __construct($subject, $to_email, $from_email, $msg,  $contenttype= '',$filename = '', $mimetype = "application/octet-stream", $mime_filename = false)
    {
        if (!$contenttype) {
            $contenttype = 'text/plain';
        }
        
        /*$settings = new plugin_shopsettings(); 
                
        if (!empty($settings->getValue("smtp_login")) && !empty($settings->getValue("smtp_password"))) {           

            
            $this->mail = new PHPMailer\PHPMailer(true);            
            $this->mail->isSMTP();

            $this->mail->CharSet = "utf-8";
            $this->mail->SMTPDebug = 0;
            $this->mail->Debugoutput = 'html';
            $this->mail->Host = $settings->getValue("smtp_server");
            $this->mail->SMTPSecure = 'ssl'; 
            $this->mail->SMTPAuth = true;
            $this->mail->Port = $settings->getValue("smtp_port");
            $this->mail->Username = $settings->getValue("smtp_login");
            $this->mail->Password = $settings->getValue("smtp_password");            
            $this->mail->addAddress(trim($to_email));            
            $this->mail->setFrom($settings->getValue("smtp_login"));
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $msg; 

        } else {*/

			if (strpos($from_email, '@mail.')!==false || strpos($from_email, '@bk.')!==false || strpos($from_email, '@list.')!==false || strpos($from_email, '@inbox.')!==false) {
				$from_email = 'noreply@' . str_replace('www.', '', $_SERVER['HTTP_HOST']);
			}
            //$from_email = 'noreply@'. $_SERVER['HTTP_HOST'];
            $this->mail = new plugin_jmail(stripslashes($subject), $to_email, $from_email);
            $this->mail->addtext($msg, $contenttype);
            if (!empty($filename)){
                $silelist = explode(';', $filename);
                foreach($silelist as $file){
                    $file = trim($file);
                    if (empty($file)) continue;
                    $this->mail->attach($file, '', $mimetype);
                }
            }

        
    }
    
    public function sendfile()
    {        
        return $this->mail->send();
    }
}

?>