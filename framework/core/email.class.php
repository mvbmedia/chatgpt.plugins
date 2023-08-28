<?php
class Email
{
    public $path;
	public $sender;
	public $email;
	public $boundary;
	public $variables = array();
	
    function __construct($path, $sender, $email)
    {
         if(!file_exists( APP . $path )){
             return;
         }
		 
         $this->path = APP . $path;
		 $this->sender = $sender;
		 $this->email = $email;
		 $this->boundary = strtoupper(md5('praskova'));
    }

    public function __set($key, $value)
    {
        $this->variables[$key] = $value;
    }

	public function headers()
	{
		# set headers
		$headers = "From: {$this->sender} <{$this->email}>\r\n";
		$headers .= "Reply-To: {$this->email}\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary = {$this->boundary}\r\n\r\n";
		
		return $headers;
	}

    public function compile()
    {
		$content = "--{$this->boundary}\r\n";
		$content .= "Content-Type: text/html; charset=UTF-8\r\n";
        $content .= "Content-Transfer-Encoding: base64\r\n\r\n"; 
		
		# start ob
        ob_start();

		# extract variables
        extract($this->variables);

        # include file
        include_once($this->path);
		
		# retrieve template content
        $html = ob_get_contents();
		
		# end ob
        ob_end_clean();

        # set content
        $content .= chunk_split(base64_encode($html));
		
		# return content
        return $content;
    }
	
	public function attach($attachment, $name, $type)
	{
        $content = "--{$this->boundary}\r\n";
        $content .= "Content-Type: " . $type . "; name=" . $name . "\r\n";
        $content .= "Content-Disposition: attachment; filename=" . $name . "\r\n";
        $content .= "Content-Transfer-Encoding: base64\r\n";
        $content .= "X-Attachment-Id: " . rand(1000, 99999) . "\r\n\r\n";
        $content .= $attachment; 
		
		return $content;
	}
	
    function send($to, $subject, $attachment = null) 
    {
		# set template
 		$headers = $this->headers();
        $html = $this->compile();

		# set attachment
		if (!empty($attachment)) {
			$html .= $this->attach($attachment);
		}
		
		$html .= "\r\n--{$this->boundary}--";
		
		return @mail($to, $subject, $html, $headers);
    }
}