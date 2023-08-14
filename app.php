<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        button a{
            text-decoration:none;
            color:white;
        }
        table {
            border-collapse: collapse;
             width: 100%;
        }

        th, td {
             padding: 8px;
             text-align: left;
             border-bottom: 1px solid #ddd;
        }
        h1{
            text-align:center;
            font-family: Helvetica;
        }
        button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius:20px;
        }
    </style>
</head>
<body>
<?php
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



 $queryUrl = 'https://b24-xlo357.bitrix24.vn/rest/1/fqm03s1k8lllt1yy/crm.deal.list.json';

 $curl = curl_init();
 curl_setopt_array($curl, array(
 CURLOPT_SSL_VERIFYPEER => 0,
 CURLOPT_POST => 1, 
 CURLOPT_HEADER => 0,
 CURLOPT_RETURNTRANSFER => 1,
 CURLOPT_URL => $queryUrl,
//  CURLOPT_POSTFIELDS => $queryData,
CURLOPT_POSTFIELDS => 0,
 ));

 $result = curl_exec($curl);

 curl_close($curl);

 $dealinfo = json_decode($result);
 $deal_array = $dealinfo->result;

 writeToLog($result, 'webform result');
 

$stage_id=array_column($deal_array, 'STAGE_ID');

$i=0;
echo "<h1> Ứng dụng tạo lead thông qua chu kì ngứa</h1>";
echo "<table border='1' style='margin:5%;width:90%'>";
echo "<tr><th>STT</th><th>Tên Deal</th><th>ID Khách Hàng</th><th>Trạng thái Deal</th><th>Ngày đã qua</th><th>Chức năng</th></tr>";
while($i < count($deal_array) )
{
    if (array_column($deal_array,'STAGE_ID')[$i] == 'WON'){
        $datetime1 = new DateTime(array_column($deal_array,'DATE_MODIFY')[$i]);
        $datetime2 = new DateTime(date(DATE_ATOM));
        echo "<tr>
        <td>".($i+1)."</td>
        <td>".array_column($deal_array,'TITLE')[$i]." </td>
        <td>".array_column($deal_array,'CONTACT_ID')[$i]."</td>
        <td>".array_column($deal_array,'STAGE_ID')[$i]."</td>
        <td>".$datetime1->diff($datetime2)->days."</td>
        <td><button class='button'><a href='add_lead.php?mkh=".array_column($deal_array,'CONTACT_ID')[$i]."'>Add Lead</a></button></td>
        </tr>";
    }
    $i++;
}
echo"</table>";


?>

</body>
</html>
