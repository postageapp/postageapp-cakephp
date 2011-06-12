[PostageApp](http://postageapp.com) for CakePHP
===================================================

This is a component for CakePHP to replace the EmailComponent and allows you to send emails with PostageApp service.
Personalized, mass email sending can be offloaded to PostageApp via the JSON based API.

### [API Documentation](http://help.postageapp.com/faqs/api) &bull; [PostageApp FAQs](http://help.postageapp.com/faqs) &bull; [PostageApp Help Portal](http://help.postageapp.com)

Installation
------------

_Manual_

- Download this: http://github.com/postageapp/postageapp-cakephp/zipball/master
- Unzip that download.
- Copy the resulting folder to app/plugins
- Rename the folder you just copied to @postage@

_GIT Submodule_

In your app directory type:
<pre><code>git submodule add git://github.com/postageapp/postageapp-cakephp.git plugins/postage
git submodule init
git submodule update
</code></pre>

_GIT Clone_

In your plugin directory type
<pre><code>git clone git://github.com/postageapp/postageapp-cakephp.git postage</code></pre>

Usage
-----
PostageApp for CakePHP works very similarly to built-in EmailComponent. Here's a simple example (in a controller):

    var $components = array('Postage.Postage' => array(
        'api_key' => POSTAGEAPP_KEY
        'postage_uri' => POSTAGEAPP_URI,
    ));
    
    function send() {
        $this->Postage->from = 'sender@test.test';
        $this->Postage->to = 'recipient@test.test';
        $this->Postage->subject = 'Example Email';
        $this->Postage->htmlMessage = '<strong>Example Message</strong>';
        $this->Postage->textMessage = 'Example Message';
        $this->Postage->attachments = array('/path/to/a/file.ext', '/path/to/a/file2.ext');
    
        $this->Postage->template = 'test-template';
        $this->Postage->variables = array('variable' => 'value');
    
        $this->Postage->send(); # returns JSON response from the server
    }
    
Recipients can be specified in a number of ways. Here's how you define a list of them with variables attached:

    $this->Postage->to = array(
      'recipient1@example.com' => array('variable1' => 'value',
                                        'variable2' => 'value'),
      'recipient2@example.com' => array('variable1' => 'value',
                                        'variable2' => 'value')
    );
    
For more information about formatting of recipients, templates and variables please see [documentation](http://help.postageapp.com/kb/api/send_message)