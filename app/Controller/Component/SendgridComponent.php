<?php
App::import('Component', 'Email');
class SendgridComponent extends EmailComponent
{
	public $components = array('Session','Email', 'Cookie','Format','PhpMailer');

	/**
	 * Decides STARTTLS/SSL for a given host:port instead of relying on a
	 * hardcoded host whitelist (which never matched Gmail and most real
	 * providers, so they always connected in plaintext and got rejected).
	 * SMTP_SECURE ("tls"/"ssl"/"none") forces a mode; left blank/undefined
	 * it auto-picks by port (465=ssl, 587/25=tls).
	 */
	function smtpEncryptionOptions($host, $port)
	{
		$secure = defined('SMTP_SECURE') ? strtolower(trim(SMTP_SECURE)) : '';
		if ($secure === 'none') {
			return array('host' => $host);
		}
		if ($secure === 'ssl' || (empty($secure) && (int)$port === 465)) {
			return array('host' => 'ssl://' . $host);
		}
		if ($secure === 'tls' || (empty($secure) && in_array((int)$port, array(587, 25)))) {
			return array('host' => $host, 'tls' => true);
		}
		return array('host' => $host);
	}

	function sendGridEmail($from,$to,$subject,$message,$type,$fromname=NULL, $chkpoint=0)
	{
		App::import('helper', 'Format');
		$frmtHlpr = new FormatHelper(new View(null));
	
		$to = $frmtHlpr->emailText($to);
		$subject = $frmtHlpr->emailText($subject);
		$message = $frmtHlpr->emailText($message);
	
		$message = str_replace("<script>","&lt;script&gt;",$message);
		$message = str_replace("</script>","&lt;/script&gt;",$message);
		$message = str_replace("<SCRIPT>","&lt;script&gt;",$message);
		$message = str_replace("</SCRIPT>","&lt;/script&gt;",$message);
		$message = preg_replace('/[^(\x20-\x7F)\x0A]*/','', $message);
		if(defined("PHPMAILER") && PHPMAILER == 1){
            $this->PhpMailer->sendPhpMailer($from,$to,$subject,$message,$type,$fromname, $chkpoint);
        }else{
		$this->Email->delivery = EMAIL_DELIVERY;
		$this->Email->to = $to;
		$this->Email->replyTo = $from;
		$this->Email->subject = $subject;
		if(trim($fromname)) {
			$this->Email->from = $fromname."<".$from.">";
		}
		else {
			$this->Email->from = $from;
		}
		$this->Email->sendAs = 'html';

		if(defined('SMTP_UNAME') && defined('SMTP_PWORD') && SMTP_PWORD !== "******") {
			$email_array = array(
				'port' => SMTP_PORT,
				'timeout'=>'30',
				'client' => WEB_DOMAIN,
				'username' => SMTP_UNAME,
				'password' => SMTP_PWORD,
			);
			$email_array += $this->smtpEncryptionOptions(SMTP_HOST, SMTP_PORT);
		}
		else {
			$email_array = array('port' => SMTP_PORT);
			$email_array += $this->smtpEncryptionOptions(SMTP_HOST, SMTP_PORT);
		}
		$this->Email->smtpOptions = $email_array;
			try{
		$response = $this->Email->send($message);
				$response = true;
			} catch (Exception $e) {
				CakeLog::write('error', 'SendgridComponent::sendGridEmail SMTP send failed to ' . $to . ' via ' . SMTP_HOST . ':' . SMTP_PORT . ' - ' . $e->getMessage());
				if($chkpoint){
					return $e->getMessage();
				}else{
					$response = true;
				}
			}
		return $response;
	}
	}
	function sendgridsmtp($email, $chkpoint=0){
		$email->replyTo = FROM_EMAIL;
		$email->delivery = EMAIL_DELIVERY;
		if(defined('SMTP_UNAME') && defined('SMTP_PWORD') && SMTP_PWORD !== "******") {
			$email_array = array(
				'port' => SMTP_PORT,
				'timeout'=>'30',
				'client' => WEB_DOMAIN,
				'username' => SMTP_UNAME,
				'password' => SMTP_PWORD,
			);
			$email_array += $this->smtpEncryptionOptions(SMTP_HOST, SMTP_PORT);
		}
		else {
			$email_array = array('port' => SMTP_PORT);
			$email_array += $this->smtpEncryptionOptions(SMTP_HOST, SMTP_PORT);
		}
		$email->smtpOptions = $email_array;
		//$response = $email->send();
		try{
		$response = $email->send();
			$response = true;
		} catch (Exception $e) {
            CakeLog::write('error', 'SendgridComponent::sendgridsmtp SMTP send failed via ' . SMTP_HOST . ':' . SMTP_PORT . ' - ' . $e->getMessage());
            if($chkpoint){
				return $e->getMessage();
			}else{
				$response = true;
			}
        }
		return $response;
	}
	function sendEmail($from,$to,$subject,$message,$type)
	{
		App::import('helper', 'Format');
		$frmtHlpr = new FormatHelper(new View(null));
		
		$to = $frmtHlpr->emailText($to);
		$subject = $frmtHlpr->emailText($subject);
		$message = $frmtHlpr->emailText($message);
		
		$message = str_replace("<script>","&lt;script&gt;",$message);
		$message = str_replace("</script>","&lt;/script&gt;",$message);
		$message = str_replace("<SCRIPT>","&lt;script&gt;",$message);
		$message = str_replace("</SCRIPT>","&lt;/script&gt;",$message);
		$message = preg_replace('/[^(\x20-\x7F)\x0A]*/','', $message);
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers.= 'From:' .$from."\r\n";

		if(mail($to,$subject,$message,$headers)) {
			return true;
		}
	}	
		
}
?>