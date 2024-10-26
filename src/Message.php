<?php
/**
 * Brevo Message Class.
 *
 * @category   Yii
 * @package    yii2-brevo
 * @author     Daniel Lucas <daniel.lucas@neusser-schuetzenlust.de>
 * @license    BSD-3-Clause https://opensource.org/licenses/BSD-3-Clause 
 * @link       https://www.neusser-schuetzenlust.de
 */

namespace schuetzenlust\brevo;

use Yii;
use yii\mail\BaseMessage;
use Brevo;

/**
 * Message is the class that is used to store the data of the email message that
 * will be sent through Brevo API.
 *
 */
class Message extends BaseMessage
{

    /**
     * The charset placeholder
     *
     * @var   string
     * @since 1.0.0
     */
    public $charset = null;
    
    /**
     * The Brevo email class.
     *
     * @var   string
     * @since 1.0.0
     */
    protected $brevoModel;     


    /**
     *
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->brevoModel = new Brevo\Client\Model\SendSmtpEmail();
    }

    /**
     * Return the Brevo Email Model
     * 
     * @return Brevo\Client\Model\SendSmtpEmail
     */
    public function getBrevoModel()
    {
        return $this->brevoModel;
    }


    /**
     * Apply a specific recipient field from Yii Message to Brevo email object
     *
     * @param yii\mail\BaseMessage                  $message   to get the recipient from
     * @param Brevo\Client\Model\SendSmtpEmail      $smtpEmail The email object to set the field to
     * @param string                                $field     The field to map (es. to, cc, bcc, replyTo, ..)
     * 
     * @return void
     */    
    protected function castRecipients( $recipients, $class )
    {
        
        if (empty($recipients) ) { 
            return;
        }
        
        if (! is_array($recipients) ) {
            $recipients = array( $recipients );
        }
        
        $emailRecipients = array();
        
        foreach ( $recipients as $i => $recipient ) {
            /* TEST */
            if (is_array($recipient) && array_key_exists("name", $recipient) && array_key_exists("email", $recipient)) {
                $emailRecipients[$i] = new $class;
                $emailRecipients[$i]->setEmail($recipient["email"]);
                $emailRecipients[$i]->setName($recipient["name"]);
            } else {
                // $container->setEmail($from);
                $emailRecipients[$i] = new $class;
                $emailRecipients[$i]->setEmail($recipient);
            }
            
            
        }
        
        return $emailRecipients;
    }
    
    /**
     * Apply a specific recipient field from Yii Message to Brevo email object
     *
     * @param yii\mail\BaseMessage                  $message   to get the recipient from
     * @param Brevo\Client\Model\SendSmtpEmail      $smtpEmail The email object to set the field to
     * @param string                                $field     The field to map (es. to, cc, bcc, replyTo, ..)
     * 
     * @return void
     */    
    protected function extractRecipientsEmail( $recipients )
    {
        if (empty($recipients) ) { 
            return array();
        }
        
        if (! is_array($recipients) ) {
            $recipients = array( $recipients );
        }
        
        $recipientsEmail = array();
        
        foreach ( $recipients as $i => $recipient ) {
            $recipientsEmail[$i] = $recipient->getEmail();
        }
        
        return $recipientsEmail;
    }    


    /**
     *
     * @inheritdoc
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     *
     * @inheritdoc
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     *
     * @inheritdoc
     */
    public function getFrom()
    {
        $container = $this->brevoModel->getSender();

        if ($container ) {
            return $container->getEmail();
        }

        return null;
    }

    /**
     *
     * @inheritdoc
     */
    public function setFrom($from)
    {
        $container = new Brevo\Client\Model\SendSmtpEmailSender();
        if (is_array($from) && array_key_exists("name", $from) && array_key_exists("email", $from)) {
            $container->setEmail($from["email"]);
            $container->setName($from["name"]);
        } else {
            $container->setEmail($from);
        }
        
        $this->brevoModel->setSender($container);

        return $this;
    }

    /**
     *
     * @inheritdoc
     */
    public function getReplyTo()
    {
        $container = $this->brevoModel->getReplyTo();

        if ($container ) {
            return $container->getEmail();
        }
        
        return null;        
    }

    /**
     *
     * @inheritdoc
     */
    public function setReplyTo($replyTo)
    {

        $container = new Brevo\Client\Model\SendSmtpEmailReplyTo();
        $container->setEmail($replyTo);
        
        $this->brevoModel->setReplyTo($container);

        return $this;
    }

    /**
     *
     * @inheritdoc
     */
    public function getTo()
    {
        $container = $this->brevoModel->getTo();

        if ($container ) {
            return $this->extractRecipientsEmail($container);
        }        
        
        return null;
    }

    /**
     *
     * @inheritdoc
     */
    public function setTo($to)
    {
        $recipients = $this->castRecipients($to, Brevo\Client\Model\SendSmtpEmailTo::class);
        
        $this->brevoModel->setTo($recipients);

        return $this;
    }

    /**
     *
     * @inheritdoc
     */
    public function getCc()
    {
        $container = $this->brevoModel->getCc();

        if ($container ) {
            return $this->extractRecipientsEmail($container);
        }            
    }

    /**
     *
     * @inheritdoc
     */
    public function setCc($cc)
    {
        $recipients = $this->castRecipients($cc, Brevo\Client\Model\SendSmtpEmailCc::class);

        $this->brevoModel->setCc($recipients);        

        return $this;
    }

    /**
     *
     * @inheritdoc
     */
    public function getBcc()
    {
        $container = $this->brevoModel->getBcc();

        if ($container ) {
            return $this->extractRecipientsEmail($container);
        }   
    }

    /**
     *
     * @inheritdoc
     */
    public function setBcc($bcc)
    {
        $recipients = $this->castRecipients($bcc, Brevo\Client\Model\SendSmtpEmailBcc::class);

        $this->brevoModel->setBcc($recipients);        

        return $this;
    }

    /**
     *
     * @inheritdoc
     */
    public function getSubject()
    {
        return $this->brevoModel->getSubject();
    }

    /**
     *
     * @inheritdoc
     */
    public function setSubject($subject)
    {
        $this->brevoModel->setSubject($subject);        

        return $this;
    }

    /**
     *
     * @inheritdoc
     */
    public function setTextBody($text)
    {
        $this->brevoModel->setTextContent($text); 

        return $this;
    }

    /**
     *
     * @inheritdoc
     */
    public function setHtmlBody($html)
    {
        $this->brevoModel->setHtmlContent($html); 

        return $this;
    }

    /**
     *
     * @inheritdoc
     */
    public function attach($fileName, array $options = [])
    {
        
        if (!array_key_exists('fileName', $options) ) {
            $options['fileName'] = basename($fileName);
        }
        
        $this->attachContent(file_get_contents($fileName), $options);
        
        return $this;
    }

    /**
     *
     * @inheritdoc
     */
    public function attachContent($content, array $options = [])
    {
        
        $attachments = $this->brevoModel->getAttachment();
        
        if (empty($attachments) ) {
            $attachments = array();
        }
        
        $attachment = new Brevo\Client\Model\SendSmtpEmailAttachment();
        $attachment->setContent(base64_encode($content));
        
        if (array_key_exists('fileName', $options) ) {
            $attachment->setName($option['fileName']);
        }
        
        $attachments[] = $attachment;
        
        $this->brevoModel->setAttachment($attachments);
        
        return $this;        
    }

    /**
     *
     * @inheritdoc
     */
    public function embed($fileName, array $options = [])
    {
        $this->attach($fileName, $options);
    }

    /**
     *
     * @inheritdoc
     */
    public function embedContent($content, array $options = [])
    {
        $this->attachContent($content, $options);
    }
    
    /**
     * Return the active template
     *
     * @since 1.0.0
     *
     * @return int
     */
    public function getTemplate()
    {
        return $this->brevoModel->getTemplateId();
    }
    
    /**
     * Sets the active template
     *
     * @param int|string $template The template ID
     *
     * @since 1.0.0
     * 
     * @return void
     */
    public function setTemplate( $template )
    {
        $this->brevoModel->setTemplateId( intval($template) );
        
        return $this;
    }

    /**
     * Return the message attributes
     *
     * @param array $flattened Return the Brevo flattned attributes or the original ones
     * 
     * @since 1.0.0
     *
     * @return array|null
     */
    public function getAttributes( $flattened = true )
    {
        if ($flattened ) {
            return $this->brevoModel->getParams();
        } else {
            return $this->attributes;
        }
    }
    
    /**
     * Sets the message attributes
     *
     * @param array $attributes The attributes to set in the message
     *
     * @since  1.0.0 
     * @return $this
     */
    public function setAttributes( $attributes )
    {
        $this->attributes = $attributes;
        $this->brevoModel->setParams(self::flatten($attributes));        
        
        return $this;
    }    

    /**
     * Flatten an array for email attibutes
     *
     * @param array  $array  The multidimensional array to be flattened
     * @param string $prefix The prefix to prepend to every record
     * 
     * @return array
     * @since  1.0.0
     */
    public static function flatten( $array, $prefix = '' )
    {
        $output = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $output = array_merge($output, self::flatten($value, $prefix . $key . '__'));
            } elseif ($value instanceof Model ) {
                $output = array_merge($output, self::flatten($value->toArray(), $prefix . $key . '__'));
            } else {
                $output[ strtoupper($prefix . $key) ] = $value;
            }
        }
    
        return $output;
    }      

    /**
     *
     * @inheritdoc
     */
    public function toString()
    {
        return $this->brevoModel->__toString();
    }    


}