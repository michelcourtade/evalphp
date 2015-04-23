<?php

class Tools {

	static public function initTranslation($driver, $dbinfo, $params, $langue, $adm = false) {
	    $tr =& Translation2::factory($driver, $dbinfo, $params);
	    if(!PEAR::isError($tr)) {
	        $tr->setLang($langue);
	        $tr->setPageId(MBC_SITE);

	        $tr =& $tr->getDecorator('CacheLiteFunction');
	        $tr->setOption('caching', true);
	        $tr->setOption('cacheDir', ROOT_PATH_CACHE.'/');
	        $tr->setOption('lifeTime', 3600*24);
            $tr =& $tr->getDecorator('DefaultText');
            $tr->outputString = '%stringID%';
            $tr->emptyPrefix = 'traduire[';
            $tr->emptyPostfix = ']';
            return $tr;
        }
    }
    static function strtoupper($str) {
        if (is_array($str))
            return false;
        if (function_exists('mb_strtoupper'))
            return mb_strtoupper($str, 'utf-8');
        return strtoupper($str);
    }

		static public function removeQuotes($string) {
			return str_replace("'","",$string);		
		}
		
		/**
		 * Convert \n to <br />
		 *
		 * @param string $string String to transform
		 * @return string New string
		 */
		static public function nl2br2($string) {
			return str_replace(array("\r\n", "\r", "\n"), '<br />', $string);
		}
		/**
		* Redirect user to another page
		*
		* @param string $url Desired URL
		* @param string $baseUri Base URI (optional)
		*/
		static public function redirect($url, $baseUri = BASE_URL) {
			if (isset($_SERVER['HTTP_REFERER']) AND ($url == $_SERVER['HTTP_REFERER']))
				header('Location: '.$_SERVER['HTTP_REFERER']);
			else
				header('Location: '.$baseUri."/".$url);
			exit();
		}
		
		/**
		* Get a value from $_POST / $_GET
		* if unavailable, take a default value
		*
		* @param string $key Value key
		* @param mixed $defaultValue (optional)
		* @return mixed Value
		*/
		static public function getValue($key, $defaultValue = false) {
		 	if (!isset($key) OR empty($key) OR !is_string($key))
				return false;
			$ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $defaultValue));
	
			if (is_string($ret) === true)
				$ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
			return !is_string($ret)? $ret : stripslashes($ret);
		}
	
		static public function getIsset($key) {
		 	if (!isset($key) OR empty($key) OR !is_string($key))
				return false;
		 	return isset($_POST[$key]) ? true : (isset($_GET[$key]) ? true : false);
		}
		
		static public function truncate($string, $max_length = 100, $replacement = '...', $trunc_at_space = false)
		{
			$replacement = "...";
			
			$max_length -= strlen($replacement);
			$string_length = strlen($string);
		
			if($string_length <= $max_length)
				return $string;
		
			if( $trunc_at_space && ($space_position = strrpos($string, ' ', $max_length-$string_length)) )
				$max_length = $space_position;
		
			return substr_replace($string, $replacement, $max_length);
		}
		
		static public function isValidEmail($email) {
		global $tr, $errors;
		$goodemail = (preg_match( '/^[A-Z0-9._-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z.]{2,6}$/i', $email));
		if(!$goodemail) {
			$errors = $tr->get("invalid_email");
			return false;
		}
		return true;
		}
		
		static public function setSessionParameter($parameterName, $parameterValue) {
			$_SESSION[$parameterName] = $parameterValue;
		}
		
		static public function getSessionParameter($parameterName) {
			return $_SESSION[$parameterName];
		}
	
		static public function encodeAccents($str) {
			return htmlspecialchars_decode(htmlentities($str));
		}
		
		
		
		static public function getIp() {
			if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
				$ip = end(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
			} 
			elseif(isset($_SERVER['HTTP_CLIENT_IP'])){ 
			   $ip = $_SERVER['HTTP_CLIENT_IP'];
			} 
			else{ 
				$ip = $_SERVER['REMOTE_ADDR'];
			} 
			
			return $ip;
		}
		
		
	
	
	static public function sendEmail($from, $recipient, $subject, $message_txt, $message_html, $valid = 1) {
		global $tr, $langue;
		/**
		 * //SMTP
		 * $transport = Swift_SmtpTransport::newInstance(MAILHOST, 25);
		 *
		 * //You could alternatively use a different transport such as Sendmail or Mail:
		 *
		 * //Sendmail
		 * $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
		 *
		 * //Mail
		 * $transport = Swift_MailTransport::newInstance();
		 **/
		$transport = Swift_SmtpTransport::newInstance(MAILHOST, 25);
	
		//Create the Mailer using your created Transport
		$mailer = Swift_Mailer::newInstance($transport);
	
		//Create a message
		$message = Swift_Message::newInstance()
		->setCharset('iso-8859-1')
		//->setCharset('utf8')
		->setSubject($subject)
		->setFrom($from)
		->setTo(array($recipient, "call-center@wineandco.com"))
		->setBody($message_html, 'text/html')
		->addPart($message_txt, 'text/plain')
		;
	
		//Send the message
		$result = $mailer->send($message);
	}

}