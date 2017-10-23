@inject('search', 'App\Http\Controllers\SmsController')

<?php

echo '<center><p style="font-size:30pt;color:red;">SMS TO HTTP INBOX<p></center>';

@$num = $_GET['num'];
@$port = $_GET['port'];
@$message = $_GET['message'];
@$time = $_GET['time'];


$Begin = "-----------------------------------Received Message------------------------------<br><br>";
$Message = "Message:<br>".$message."<br>";
$MyPhonenumber = "From:".$num."<br>";
$MyPort = "Port:".$port."<br>";
$MyTime = "Time:".$time."<br>";
$Success = " records successfully update";
$MyStr = $Begin.$MyTime.$MyPhonenumber.$MyPort.$Message.$Success."<br><br>";
if(!empty($num))
{
	$file_pointer = fopen("data.txt","a+");
	fwrite($file_pointer,$MyStr);
	fclose($file_pointer);
} 
$content = file_get_contents('C:\xampp\htdocs\driver_rfid\resources\views\sms\data.txt');
echo $content;
echo "<br><br>";

?>

