[PostageApp](http://postageapp.com) for CakePHP
===================================================

This is a component for CakePHP to replace the EmailComponent and allows you to send emails with PostageApp service.
Personalized, mass email sending can be offloaded to PostageApp via the JSON based API.

### [API Documentation](http://help.postageapp.com/faqs/api) &bull; [PostageApp FAQs](http://help.postageapp.com/faqs) &bull; [PostageApp Help Portal](http://help.postageapp.com)

Installation
------------
 - Put `postage.php` into `app/controllers/components/`
 - Edit `components/postage.php` to include your PostageApp Project API key. (At the top of `postage.php`)

Usage
-----
PostageApp for CakePHP works very similarly to built-in EmailComponent. Here's a simple example (in a controller):

    var $components = array('Postage');
    
    $this->Postage->from = 'sender@test.test';
    $this->Postage->to = 'recipient@test.test';
    $this->Postage->subject = 'Example Email';
    $this->Postage->htmlMessage = '<strong>Example Message</strong>';
    $this->Postage->textMessage = 'Example Message';
    $this->Postage->attachments = array('/path/to/a/file.ext', '/path/to/a/file2.ext');
    
    $this->Postage->template = 'test-template';
    $this->Postage->variables = array('variable' => 'value');
    
    $this->Postage->send(); # returns JSON response from the server
    
Recipients can be specified in a number of ways. Here's how you define a list of them with variables attached:

    $this->Postage->to = array(
      'recipient1@example.com' => array('variable1' => 'value',
                                        'variable2' => 'value'),
      'recipient2@example.com' => array('variable1' => 'value',
                                        'variable2' => 'value')
    );
    
For more information about formatting of recipients, templates and variables please see [documentation](http://help.postageapp.com/kb/api/send_message)