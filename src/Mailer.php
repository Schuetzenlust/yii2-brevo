<?php
/**
 * Brevo Mailer Class.
 *
 * @category   Yii
 * @package    yii2-brevo
 * @author     Daniel Lucas <daniel.lucas@neusser-schuetzenlust.de>
 * @license    BSD-3-Clause https://opensource.org/licenses/BSD-3-Clause 
 * @link       https://www.neusser-schuetzenlust.de
 */

namespace schuetzenlust\brevo;

use Yii;
use yii\mail\BaseMailer;
use yii\base\Model;
use yii\base\InvalidConfigException;
use yii\helpers\VarDumper;
use Brevo;

/**
 * Mailer is the class that consuming the Message object sends emails thorugh
 * the Brevo API.
 *
 */
class Mailer extends BaseMailer
{

    /**
     * Brevo API key
     * 
     * @var string 
     */
    public $apikey;

    /**
     * Object for this instance
     * 
     * @var The Brevo\Client\Configuration 
     */
    public $config;
    
    /**
     * Keeps the last transaction result
     * 
     * @var object 
     */
    protected $lastError;    
    

    /**
     * Checks that the API key has indeed been set.
     *
     * @inheritdoc
     * 
     * @throws InvalidConfigException 
     * @return void
     */
    public function init()
    {

        if (! $this->hasConfig() ) {
            $this->config = Brevo\Client\Configuration::getDefaultConfiguration();
        }
        
        if ($this->apikey ) {
            $this->config->setApiKey('api-key', $this->apikey);
        } 
        
        if (! $this->config->getApiKey('api-key') ) {
            throw new InvalidConfigException('Brevo API key cannot be null.');
        }
        
    }

    /**
     * Sets the API key for Brevo
     *
     * @param string $apikey the Brevo API key
     * 
     * @throws InvalidConfigException
     * @return void
     */
    public function setApikey($apikey)
    {
        if (!is_string($apikey)) {
            throw new InvalidConfigException('"' . get_class($this) . '::apikey" should be a string, "' . gettype($apikey) . '" given.');
        }

        $trimmedApikey = trim($apikey);
        if (!strlen($trimmedApikey) > 0) {
            throw new InvalidConfigException('"' . get_class($this) . '::apikey" length should be greater than 0.');
        }

        $this->apikey = $trimmedApikey;
        
        if ($this->config instanceof Brevo\Client\Configuration ) {
            $this->config->setApiKey('api-key', $this->apikey);
        }
        
    }

    /**
     * Check if this instance has a config set
     * 
     * @return bool
     */
    public function hasConfig()
    {
        return ($this->config instanceof Brevo\Client\Configuration );
    }    

    /**
     * Return the current Brevo configuration object
     *
     * @return Brevo\Client\Configuration
     * @since  1.0.0
     */
    public function getConfig()
    {
        return $this->config;
    }
    
    /**
     * Return the last error occurred
     *
     * @return object
     * @since  1.0.0
     */
    public function getLastError()
    {
        return $this->lastError;
    }    

    /**
     *
     * @inheritdoc
     */
    public function compose($view = null, array $params = [ 'PLACEHOLDER' => '' ])
    {
        
        if (is_numeric($view) ) {
            $message = $this->createMessage();
            $message->setTemplate($view);
            $message->setAttributes($params);
        } else {
            $message =  parent::compose($view, $params);    
        }
        
        return $message;
    }
    
    /**
     *
     * @inheritdoc
     */
    protected function createMessage()
    {
        
        $config = $this->messageConfig;
        
        if (!array_key_exists('class', $config)) {
            $config['class'] = Message::class;
        }
        
        $config['mailer'] = $this;
        return Yii::createObject($config);
    }    

    /**
     *
     * @inheritdoc
     */
    protected function sendMessage($message)
    {
        Yii::debug('Brevo Mailer sending SMTP email for message: ' . VarDumper::export($message), __METHOD__);

        $apiInstance = new Brevo\Client\Api\TransactionalEmailsApi(null, $this->config);
        
        try {
            $result = $apiInstance->sendTransacEmail($message->getBrevoModel());   
            
            Yii::info('Brevo Mailer sent SMTP email with result: ' . VarDumper::export($result), __METHOD__);
        } catch ( Brevo\Client\ApiException $e ) {
            $this->lastError = json_decode($e->getResponseBody());
            Yii::error('Brevo API client exception: ' . VarDumper::export($this->lastError), __METHOD__);
            return false;
        } catch ( Exception $e ) {
            Yii::error('Brevo API exception: ' . $e->getMessage(), __METHOD__);
            return false;            
        }

        return true;
    }

    
}