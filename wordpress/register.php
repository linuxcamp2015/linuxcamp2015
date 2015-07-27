<?php
/* Email Detials */
  $mail_to = "$_GET[regemail]";
  $from_mail = "yzkim9501@naver.com";
  $from_name = "yezi";
  $reply_to = "yzkim9501@naver.com";
  $subject = "linuxcamp";
  $message = "<TABLE border=1><TR><TD>결제번호</TD><TD>$creditnum</TD></TR><TR><TD>입금자명</TD><TD>$toName</TD></TR><TR><TD>이메일</TD><TD>$toEmail</TD></TR><TR><TD>비밀번호</TD><TD></TD></TR><TR><TD>소속</TD><TD>$company</TD></TR><TR><TD>전화번호</TD><TD>$tel</TD></TR><TR><TD>핸드폰</TD><TD>$phone</TD></TR><TR><TD>입금일</TD><TD>date</TD></TR></TABLE><H3>참가비</H3><TABLE border=1><TR><TD>항목명</TD><TD>비용</TD></TR><TD>추계등록비</TD><TD>$money</TD><TR></TR></TABLE>";

   
/* Set the email header */
  // Generate a boundary
  $boundary = md5(uniqid(time()));
   
  // Email header
  $header = "From: ".$from_name." <".$from_mail.">".PHP_EOL;
  $header .= "Reply-To: ".$reply_to.PHP_EOL;
  $header .= "MIME-Version: 1.0".PHP_EOL;
   
  // Multipart wraps the Email Content and Attachment
  $header .= "Content-Type: multipart/mixed; boundary=\"".$boundary."\"".PHP_EOL;
  $header .= "This is a multi-part message in MIME format.".PHP_EOL;
  $header .= "--".$boundary.PHP_EOL;
   
  // Email content
  // Content-type can be text/plain or text/html
  $header .= "Content-type:text/plain; charset=iso-8859-1".PHP_EOL;
  $header .= "Content-Transfer-Encoding: 7bit".PHP_EOL.PHP_EOL;
  $header .= "$message".PHP_EOL;
  $header .= "--".$boundary.PHP_EOL;
   
  // Send email
  if (mail($mail_to, $subject, "", $header)) {
    echo "Sent";
  } else {
    echo "Error";
  }
?>
