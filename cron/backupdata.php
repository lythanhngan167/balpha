<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set("memory_limit", "-1");
  require '../configuration.php';

  date_default_timezone_set('Asia/Ho_Chi_Minh');

  $now = date('d-m-Y');
  $nowTimeFrom  = $now . ' 00:30';
  $nowTimeTo    = $now. ' 00:59';

  // $nowTimeFrom  = $now . ' 21:30';   //Test
  // $nowTimeTo    = $now. ' 23:59';

  $timeFrom     = strtotime($nowTimeFrom);
  $timeTo       = strtotime($nowTimeTo);

  $nowWithTime = date('d-m-Y H:i');
  $nowTime = strtotime($nowWithTime);

  $dir = dirname(__FILE__);

  function backup_tables($host, $user, $pass, $name, $tables = '*')
  {
    $data = "\n/*---------------------------------------------------------------" .
    "\n  SQL DB BACKUP " . date("d.m.Y H:i") . " " .
    "\n  HOST: {$host}" .
    "\n  DATABASE: {$name}" .
    "\n  TABLES: {$tables}" .
    "\n  ---------------------------------------------------------------*/\n";
    $link = mysqli_connect($host, $user, $pass, $name);
    $link->set_charset("utf8");

    if ($tables == '*') { //get all of the tables
      $tables = array();
      $result = $link->query("SHOW TABLES");
      while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
      }
    } else {
      $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    foreach ($tables as $table) {
      $data .= "\n/*---------------------------------------------------------------" .
      "\n  TABLE: `{$table}`" .
      "\n  ---------------------------------------------------------------*/\n";
      $data .= "DROP TABLE IF EXISTS `{$table}`;\n";
      $res = $link->query("SHOW CREATE TABLE `{$table}`");
      if ($res) {
        $row = $res->fetch_row();
      }

      $data .= $row[1] . ";\n";

      $result = $link->query("SELECT * FROM `{$table}`");
      $num_rows = mysqli_num_rows($result);

      if ($num_rows > 0) {
        $vals = array();
        $z = 0;
        for ($i = 0; $i < $num_rows; $i++) {
          $items = $result->fetch_row();
          $vals[$z] = "(";
          for ($j = 0; $j < count($items); $j++) {
            if (isset($items[$j])) {
              $vals[$z] .= "'" . mysqli_real_escape_string($link, $items[$j]) . "'";
            } else {
              $vals[$z] .= "NULL";
            }
            if ($j < (count($items) - 1)) {
              $vals[$z] .= ",";
            }
          }
          $vals[$z] .= ")";
          $z++;
        }
        $data .= "INSERT INTO `{$table}` VALUES ";
        $data .= "  " . implode(";\nINSERT INTO `{$table}` VALUES ", $vals) . ";\n";
      }
    }
    mysqli_close($link);
    return $data;
  }

  function deleteBackupFile($path, $nowTime, $timeFrom, $timeTo, $directDelete)
  {
      if ($nowTime >= $timeFrom && $nowTime <= $timeTo) {                            // Kiểm tra thời gian xóa file

        $files = scandir($path);                                                    // Quét file trong thư mục hiện tại

        if ($files !== false) {
          foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
              $fileDate = substr($file, 14, 10);
              $fileTime = substr($file, 25, 5);
              $fileTime = str_replace('-', ':', $fileTime);
              $fileDateTime = strtotime($fileDate . ' ' . $fileTime);
              if (($nowTime - $fileDateTime) > 172800 || $directDelete) {                        // Kiểm tra file quá 2 ngày
                chmod($path."/". $file, 0777);
                unlink($path."/". $file);                                              //Xóa file trong thư mục hiện tại
              }
            }
          }
        }
      }
  }

  function uploadFTP($fileName, $path, $ftpServer, $userName, $passWord) {
    $ftpConn = ftp_connect($ftpServer) or die("Không thể kết nối đến server!");
    $login = ftp_login($ftpConn, $userName, $passWord) or die("Không thể login!");
    if(ftp_put($ftpConn, $fileName, $path, FTP_BINARY)) {
      echo 'success';
    } else {
      echo 'fail';
    }
    ftp_close($ftpConn);
  }

  function deleteExpiredDataLandingpage($url){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 0,
        CURLOPT_URL => $url.'/index.php?option=com_customer&task=customers.deleteExpiredDataLandingpage',
        CURLOPT_USERAGENT => 'Biznet',
        CURLOPT_SSL_VERIFYPEER => false
    ));

    $resp = curl_exec($curl);
    //Kết quả trả tìm kiếm trả về dạng JSON
    $weather = json_decode($resp);
    //var_dump($weather); // dump kết quả
    curl_close($curl);
  }

  function tranferExpiredDataToProject($url){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 0,
        CURLOPT_URL => $url.'/index.php?option=com_customer&task=customers.tranferExpiredDataToProject',
        CURLOPT_USERAGENT => 'Biznet',
        CURLOPT_SSL_VERIFYPEER => false
    ));

    $resp = curl_exec($curl);
    //Kết quả trả tìm kiếm trả về dạng JSON
    $weather = json_decode($resp);
    //var_dump($weather); // dump kết quả
    curl_close($curl);
  }

  function deleteExpiredData($url){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 0,
        CURLOPT_URL => $url.'/index.php?option=com_customer&task=customers.deleteExpiredData',
        CURLOPT_USERAGENT => 'Biznet',
        CURLOPT_SSL_VERIFYPEER => false
    ));

    $resp = curl_exec($curl);
    //Kết quả trả tìm kiếm trả về dạng JSON
    $weather = json_decode($resp);
    //var_dump($weather); // dump kết quả
    curl_close($curl);
  }

  function url(){
    // return sprintf(
    //   "%s://%s%s",
    //   isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    //   $_SERVER['SERVER_NAME'],
    //   ''
    // );
    if($_SERVER['SERVER_NAME'] == 'locahost'){
      return 'http://localhost/biznetweb';
    }else{
      return 'https://biznet.com.vn';
    }
  }

  $config = new JConfig();

  $type = $_REQUEST['type'];

  if(!isset($type)) {
    echo 'Error';
    die();
  }


  switch($type) {
    case 'high':
      deleteExpiredDataLandingpage($url);
      deleteExpiredData($url);
      // Sao lưu table quan trọng
      /*
      $highPriorityTable = "wmspj_users,wmspj_user_usergroup_map,wmspj_customers,wmspj_recharge";
      $highPriorityBackupData = backup_tables($config->host, $config->user, $config->password, $config->db, $highPriorityTable);
      $path = $dir.'/high';
      $backup_file = '/backup-biznet-'.date('d-m-Y-H-i').'.sql';

      //delete file backup after 3 day
      deleteBackupFile($path, $nowTime, $timeFrom, $timeTo, false);

      $url = url();
      if($url == 'http://localhost'){
        $url = 'http://localhost/biznetweb';
      }

      //tranferExpiredDataToProject($url);

      // save to file
      $handle = fopen($path.$backup_file,'w+');
      fwrite($handle, $highPriorityBackupData);
      fclose($handle);
      */
      //ftp
      //uploadFTP($backup_file, $path.$backup_file, '192.168.1.4', 'test', '12345678');

    break;

    case 'medium':

      // Sao lưu table kém quan trọng
      /*
      $mediumPriorityTable = "wmspj_registration,wmspj_projects,wmspj_orders,wmspj_transaction_history";
      $mediumPriorityBackupData = backup_tables($config->host, $config->user, $config->password, $config->db, $mediumPriorityTable);
      $path = $dir.'/medium';
      $backup_file = '/backup-biznet-'.date('d-m-Y-H-i').'.sql';

      //delete file backup after 3 day
      deleteBackupFile($path, $nowTime, $timeFrom, $timeTo, false);

      // save to file
      $handle = fopen($path.$backup_file,'w+');
      fwrite($handle, $mediumPriorityBackupData);
      fclose($handle);
      */
    break;

    case 'low':
      /*
      // Sao lưu tất cả table
      $lowPriorityTable = "*";
      //wmspj_users,wmspj_user_usergroup_map,wmspj_registration,wmspj_recharge,wmspj_projects,wmspj_orders,wmspj_customers,wmspj_transaction_history
      $lowPriorityBackupData = backup_tables($config->host, $config->user, $config->password, $config->db, $lowPriorityTable);
      $path = $dir.'/low';
      $backup_file = '/backup-biznet-'.date('d-m-Y-H-i').'.sql';

      // save to file
      $handle = fopen($path.$backup_file,'w+');
      fwrite($handle, $lowPriorityBackupData);
      fclose($handle);

      //ftp
      //uploadFTP($backup_file, $path.$backup_file, '192.168.43.120', 'test', '12345678');
      //delete file backup after 3 day
      deleteBackupFile($path, $nowTime, $nowTime, $nowTime, true);
        */

    break;

  }

?>
