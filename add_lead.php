<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Document</title>
    <style>
        
        input[type="submit"] {
            background-color: #4c9aff; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius:20px;
        }

        w3-card-4{
            margin:200px;
            border-radius:50px;
        }
    </style>
</head>
<body>
<?php
    $mkh=$_GET['mkh'];
    // $sql="SELECT HOTEN FROM KHACHHANG WHERE MAKH='$mkh'";
    // $rs= $connect->query($sql);
    // while ($rows = $rs->fetch_row()){
    //     echo "Mã khách hàng
    //     <input type='text' name='mkh' value='".$mkh."'/><br>
    //     Họ và tên
    //     <input type='text' name='mkh' value='".$rows[0]."'/><br>";
    // };

    /**
 * Write data to log file.
 *
 * @param mixed $data
 * @param string $title
 *
 * @return bool
 */
function writeToLog($data, $title = '') {
    $log = "\n------------------------\n";
    $log .= date("Y.m.d G:i:s") . "\n";
    $log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n";
    $log .= print_r($data, 1);
    $log .= "\n------------------------\n";
    file_put_contents(getcwd() . '/hook.log', $log, FILE_APPEND);
    return true;
   }
   
   $defaults = array('contact_id' => '', 'title' => '', 'comment' => '');
   
   if (array_key_exists('saved', $_REQUEST)) {
    $defaults = $_REQUEST;
    $defaults['title']=$mkh;
    writeToLog($_REQUEST, 'webform');
   
    $queryUrl = 'https://b24-xlo357.bitrix24.vn/rest/1/ko03wkulsxfrp78v/crm.lead.add.json';
    $queryData = http_build_query(array(
    'fields' => array(
    "TITLE" => $_REQUEST['title'],
    "CONTACT_ID" => $_REQUEST['contact_id'],
    "COMMENTS" => $_REQUEST['comment'],
    "STATUS_ID" => "NEW",
    "OPENED" => "Y",
    "ASSIGNED_BY_ID" => 1,
    ),
    'params' => array("REGISTER_SONET_EVENT" => "Y")
    ));
   
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl,
    CURLOPT_POSTFIELDS => $queryData,
    ));
   
    $result = curl_exec($curl);
    curl_close($curl);
   
    $result = json_decode($result, 1);
    writeToLog($result, 'webform result');
   
    if (array_key_exists('error', $result)) echo "Error saving Lead: ".$result['error_description']."
   ";
   }
    
?>
<div class="w3-card-4" style="margin:100px;padding:50px;">
    <div class="w3-container w3-green">
      <h2>Tạo lead </h2>
    </div>
    <form method="post" action="" >
    Contact ID: <input type="text" class="w3-input" name="contact_id" size="15" value="<?php $echo?><?=$mkh?>"><br/>
    Title: <input type="text" class="w3-input" name="title" size="15" value="<?=$defaults['title']?>"><br/>
    Comments: <input type="text" class="w3-input" name="comment" size="100" value="<?=$defaults['comment']?>"><br/>
    <input type="hidden" name="saved" value="yes">
    <input type="submit" value="Send">
</form>
</body>
</html>
