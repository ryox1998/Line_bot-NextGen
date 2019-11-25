<?php
    $accessToken = "s7wlpFPogKjEnvYUFGQUzRUiCSdxa5kx2egLN2n7M93DTMckZuMyOO9WcjABKijGGW4hRkBOr7fPHIOdYT+zywFiuACYH0WxRoUqB0vCTJtvnynlf/AtCwTNeFxVs2HBNpS5dD9JwXl93O0ldaI7sQdB04t89/1O/w1cDnyilFU=";
    $content = file_get_contents('php://input');
    $arrayJson = json_decode($content, true);
    $arrayHeader = array();
    $arrayHeader[] = "Content-Type: application/json";
    $arrayHeader[] = "Authorization: Bearer {$accessToken}";
    $message = $arrayJson['events'][0]['message']['text']; //รับข้อความจากผู้ใช้
	
	if($message == "status" || $message == "Status"){
        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = "Process Success !!";

			$siteURL = "https://www.timeanddate.com/worldclock/fullscreen.html?n=28";
            $googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$siteURL&screenshot=true");
            $googlePagespeedData = json_decode($googlePagespeedData, true);
            $screenshot = $googlePagespeedData['screenshot']['data'];
            $screenshot = str_replace(array('_', '-'), array('/', '+'), $screenshot);

			$data = base64_decode ($screenshot);
			$im = imageCreateFromString($data);
			$img_file = 'images/image.png';
			imagepng($im, $img_file);
			
			$image_url = "https://botnextgen.000webhostapp.com/images/image.png?".time();
			$arrayPostData['messages'][1]['type'] = "image";
			$arrayPostData['messages'][1]['originalContentUrl'] = $image_url;
			$arrayPostData['messages'][1]['previewImageUrl'] = $image_url;

			replyMsg($arrayHeader,$arrayPostData);	
	}
	
	
	else if ($message == "ok" ) {
		
		$arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = "Thank you.";
	}
	
	else 
	{
		$arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = "Please key status message thank you.";
		$arrayPostData['messages'][1]['type'] = "sticker";
        $arrayPostData['messages'][1]['packageId'] = "4";
        $arrayPostData['messages'][1]['stickerId'] = "631";
        replyMsg($arrayHeader,$arrayPostData);
	}

	function replyMsg($arrayHeader,$arrayPostData)
	{
        $strUrl = "https://api.line.me/v2/bot/message/reply";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$strUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);    
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arrayPostData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close ($ch);
    }
	  
   exit;
	?>