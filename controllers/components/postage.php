<?php

define ('POSTAGEAPP_VERSION', '1.0.0');

define ('POSTAGEAPP_APIKEY', 'PLEASE CHANGE ME TO YOUR PROJECT API KEY');
define ('POSTAGEAPP_URI', 'https://api.postageapp.com/v.1.0/send_message.json');

/**
 * PostageApp Component
 *
 * Permits email to be sent via PostageApp service
 *
 * @package PostageApp
 * @author Jon Lim, The Working Group Inc.
 * @link http://postageapp.com
 */

App::import('Component');

class PostageComponent extends Object {
	
	/**
	 * Objects that correspond to PostageApp API parameters
	 */
	var $to = null;	
	var $from = null;
	var $replyTo = null;
	var $subject = null;
	var $template = null;
	var $variables = array();
	var $attachments = array();
	var $textMessage = null;
	var $htmlMessage = null;
	
	/**
	 * Initialize the Postage component and grab the API key from the config files
	 * and the PostageApp API URI from the definitions above 
	 *
	 * @param object $controller Instantiating controller
 	 * @access public
	 */
	function initialize(&$controller, $settings = array()) {
		$this->Controller =& $controller;
		/*if (Configure::read('PostageApp.api_key') !== null) {
			$this->api_key = Configure::read('PostageApp.api_key');
		}*/
		$this->api_key = $settings['api_key'];
		$this->postage_uri = $settings['postage_uri'];
	}

	/**
	 * Startup component
	 *
	 * @param object $controller Instantiating controller
	 * @access public
	 */
	function startup(&$controller) {}
	
	/**
	* Creates a connection to the PostageApp API URI using HttpSocket and sets
	* the HTTPHEADER for the email, and posts the content from the payload()
	* function to the PostageApp API
	*
	* @return Decoded JSON of result from API call
	* @access public
	*/
	function send() {
	
		App::import('Core', 'HttpSocket');
	
		// Create HttpSocket we will use to communitcate with PostageApp API
		$this->__postageSocket =& new HttpSocket();

		$httpheader = array(
			'header' => array(
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
				'User-Agent: PostageApp CakePHP ' . POSTAGEAPP_VERSION . ' (CakePHP ' . Configure::read('Cake.version') . ', PHP ' . phpversion() . ')'
			),
		);

		$output = $this->__postageSocket->post(
			$this->postage_uri, 
			json_encode($this->payload()), 
			$httpheader
		);
		
		return json_decode($output);
	}
	
	/**
	* Creates a hash using all of the parameters of the objects set by the user
	* to be passed off to the send() function.
	*/
	function payload() {
		$content = array(
		
			/**
			 * Setting Recipients. Accepted formats for $to are (see API docs):	
			 *   -> 'recipient@example.com'
			 *   -> 'John Doe <recipient@example.com>'
			 *   -> 'recipient1@example.com, recipient2@example.com'
			 *   -> array('recipient1@example.com', 'recipient2@example.com')
			 *   -> array('recipient1@example.com' => array('variable1' => 'value',
			 *                                              'variable2' => 'value'),
			 *            'recipient2@example.com' => array('variable1' => 'value',
			 *                                              'variable2' => 'value'))
			 */
			'recipients'	=> $this->to,
			
			'headers'		=> array(
				'subject'		=> $this->subject,
				'from'			=> $this->from
			),
			'variables'		=> $this->variables
		);
		
		/**
		 * If the template object is not empty, it sets the template of the API call
		 * and ignores the other content. Otherwise, it lets you set the content for
		 * HTML and plain text formats.
		 */
		if ($this->template != null) {
			$content['template'] = $this->template;
		} else {
			$content['content'] = array(
				'text/plain'	=> $this->textMessage,
				'text/html'		=> $this->htmlMessage
			);
		}
		
		/**
		* Checks if there are attachments for the email, and encodes it and attaches
		* it to the API call
		*/
		if (!empty($this->attachments)) {
			foreach ($this->attachments as $attachment) {
				$handle = fopen($attachment, 'rb');
			    $file_content = fread($handle, filesize($attachment));
			    fclose($handle);
			    
			    $content['attachments'][basename($attachment)] = array(
			      'content_type'  => 'application/octet-stream',
			      'Content-Transfer-Encoding' => 'base64',
			      'content'       => chunk_split(base64_encode($file_content), 60, "\n")
			    );
			}
		}
	
		/**
		* Creates the hash that gets passed off to the send() function
		*/
		$message = array(
			'api_key'	=> $this->api_key,
			'uid'		=> sha1(time() . json_encode($content)),
			'arguments'	=> $content
		);
		
		return $message;
	}
	
	/**
	 * Reset all PostageComponent internal variables to be able to send out a new email.
	 *
	 * @access public
	 */
	function reset() {
		$this->to = array();
		$this->from = null;
		$this->replyTo = null;
		$this->subject = null;
		$this->template = null;
		$this->variables = array();
		$this->attachments = array();
		$this->htmlMessage = null;
		$this->textMessage = null;
		$this->__header = array();
		$this->__message = array();
	}
}

?>