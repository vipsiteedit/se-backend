<?php

	if(isset($_POST["PHPSESSID"])) 
     {
	  session_id($_POST["PHPSESSID"]);
	 }

	session_start();
	ini_set("html_errors", "0");
    
	$getcwd_dir = $_SERVER['DOCUMENT_ROOT'];  //  /account/siteedit.ru/www/modules/se_modules ----- /account/international.e-stile.ru/www

	if(!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0){
		echo "ERROR:invalid upload";
		exit(0);
	}

    $IMAGE_DIR = "/tmp/";
	if (!is_dir($getcwd_dir . $IMAGE_DIR)){
		mkdir($getcwd_dir . $IMAGE_DIR);
	}
	
	//подгружаем библиотеки (обязательно)
	//устанавливаем текущую директорию запускаемого файла                    

  // $path = dirname (__FILE__)."/../..";
   require_once ($getcwd_dir."/lib/lib_images.php");

     //загрузка изображения
	if (is_uploaded_file($_FILES['Filedata']['tmp_name'])){
            $userfile = $_FILES['Filedata']['tmp_name'];
            $userfile_size = $_FILES['Filedata']['size'];
            $user = strtolower(htmlspecialchars($_FILES['Filedata']['name'], ENT_QUOTES));

            //проверяем, что загруженный файл - картинка
            $sz = GetImageSize($userfile);   
    
            //если размер файла больше заданного
            if ($userfile_size > 2*1024*1000) {
                $error .= "Объем изображения превышает лимит";
                $flag = false;
            }
            $file = true;
    }
     //если есть изображение на загрузку
	if ($file){               
        $imgname = 'mod_'.time().'_'.$_SESSION['img_uploud_count'];
                
        //  echo getcwd();     //  /account/international.e-stile.ru/www/modules/se_modules               
        $fileextens     = substr($user, -3);
        $uploadfile     = $getcwd_dir.$IMAGE_DIR . $imgname . "." . $fileextens;
        $uploadfileprev = $getcwd_dir.$IMAGE_DIR . $imgname . "_prev." . $fileextens;
        $filename       = $imgname . '.' . $fileextens;

		$width = 1024;
		$thumbwdth = 250;

        if ($sz[0]>$width){
            $uploadfiletmp  = $getcwd_dir.$IMAGE_DIR . $imgname . ".temp";
            move_uploaded_file($userfile, $uploadfiletmp);
            ImgCreate($uploadfileprev, $uploadfile, $uploadfiletmp, $fileextens, $width, $thumbwdth);
            @unlink($uploadfiletmp);
        } else {
            move_uploaded_file($userfile, $uploadfile);
            ThumbCreate($uploadfileprev, $uploadfile, $fileextens, $thumbwdth);
        }
        $_SESSION["img_uploud_count"]++;
    } 
    echo "FILEID: http://".$_SERVER['HTTP_HOST']. $IMAGE_DIR . $imgname . "_prev." . $fileextens;