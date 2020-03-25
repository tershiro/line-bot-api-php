<?php

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'XrjMAlBfKkT6k49wPSH3HC1gb95rQjOuHhNY4Fh4LU+d/zUsIgDRBgnovSeWXi5HKSpmxBirgJ0GvbMBNrLxFDYH69bjQqSM3I7L70sxvo/oSGPanMoqssoPuWRE/ucHxa5D5kzSW69NimvcupuKHgdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if ( sizeof($request_array['events']) > 0 )
{

 foreach ($request_array['events'] as $event)
 {
  $reply_message = '';
  $reply_token = $event['replyToken'];

  if ( $event['type'] == 'message' ) 
  {
   
   if( $event['message']['type'] == 'text' )
   {
		$text = $event['message']['text'];
		
		if(($text == "อยากทราบยอด COVID-19 ครับ")
		   ||($text == "อยากทราบยอด COVID-19")
		   ||($text == "COVID-19")
		   ||($text == "โควิด")
		   ||($text == "โควิด-19")
		   ||($text == "COVID")
		   ||($text == "COVID 19")
		   ||($text == "โควิด 19")){
			$reply_message = 'รายงานสถานการณ์ ยอดผู้ติดเชื้อไวรัสโคโรนา 2019 (COVID-19) ในประเทศไทย"
					ผู้ป่วยสะสม	จำนวน 398,995 ราย
					ผู้เสียชีวิต	จำนวน 17,365 ราย
					รักษาหาย	จำนวน 103,753 ราย
					ผู้รายงานข้อมูล: นายจิรเมธ เนตรปฏิภาณ';
		}
		else if($text== "ข้อมูลส่วนตัวของผู้พัฒนาระบบ"){
			$reply_message = 'ชื่อนายจิรเมธ เนตรปฏิภาณ อายุ 22 ปี น้ำหนัก 75 kg. สูง 170cm. ขนาดรองเท้าเบอร์ 7.5 ใช้หน่วย US';
		}
		else
		{
			$reply_message = 'ไม่พบคำตอบของท่าน';
    		}
   
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
 
  if( strlen($reply_message) > 0 )
  {
   //$reply_message = iconv("tis-620","utf-8",$reply_message);
   $data = [
    'replyToken' => $reply_token,
    'messages' => [['type' => 'text', 'text' => $reply_message]]
   ];
   $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

   $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
   echo "Result: ".$send_result."\r\n";
  }
 }
}

echo "OK";

function send_reply_message($url, $post_header, $post_body)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 $result = curl_exec($ch);
 curl_close($ch);

 return $result;
}

?>
