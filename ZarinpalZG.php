<?php
/**
 * ZarinPalZG gateway library
 *
 * @category    Gateway
 * @package     Gateway
 * @author      Mohsen Khahani <mkhahani@gmail.com>
 * @copyright   2013 Jaws Development Group
 * @license     http://www.gnu.org/copyleft/lesser.html
 * @version     ZarinPalZG.php v1.0 2013-03-15 11:00:00
 */

/**
 * Constants
 */
define('ZarinPalZG_SOAP_URL', 'https://de.ZarinPalZG.com/pg/services/WebGate/wsdl');
define('ZarinPalZG_PAYMENT_URL', 'https://www.ZarinPalZG.com/pg/StartPay/');

/**
 * ZarinPalZGGateway Class
 */
class ZarinPalZGGateway
{

    /**
     * Merchant ID of your gateway
     *
     * @access private
     * @var    string;
     */
    var $_merchant_id;

    /**
     * SOAP connection handler
     *
     * @access private
     * @var    object;
     */
    var $_connection;

    /**
     * Class constructor
     *
     * @access  public
     * @param   string  $merchant_id    Merchant ID
     * @return  void
     */
    function ZarinPalZGGateway($merchant_id)
    {
        $this->_merchant_id = $merchant_id;
        $this->_connection = new SoapClient(
            ZarinPalZG_SOAP_URL, 
            array('encoding'=>'UTF-8')
        );
    }

    /**
     * Sends transaction request unto ZarinPalZG gateway via SOAP connection
     *
     * @access  public
     * @param   int     $amount         Amount
     * @param   string  $desc           Description (max 250)
     * @param   string  $return_url     URL to redirect to
     * @return  mixed   Negative integer on error or string Transaction ID(36 chars)   
     */
    function Request($amount, $desc, $return_url)
    {
        return $this->_connection->PaymentRequest(
	array(
					'MerchantID' 	=> $this->_merchant_id ,
					'Amount' 		=> $amount ,
					'Description' 	=> $desc ,
					'Email' 		=> '' ,
					'Mobile' 		=> '' ,
					'CallbackURL' 	=> $return_url

					)
	 );
		
    }



    /**
     * Redirects to ZarinPalZG payment page
     *
     * @access  public
     * @param   string  $trans_id   Transaction ID
     * @return  void
     */
    function Pay($trans_id)
    {
        require_once JAWS_PATH . 'include/Jaws/Header.php';
        Jaws_Header::Location(ZarinPalZG_PAYMENT_URL . $trans_id . '/ZarinGate');
    }

    /**
     * Verifies transaction
     *
     * @access  public
     * @param   string  $trans_id   Transaction ID
     * @param   int     $amount     Amount
     * @return  int     Result code
     */
    function Verify($trans_id, $amount)
    {
        return $this->_connection->PaymentVerification(
			array(
					'MerchantID'	 => $this->_merchant_id, ,
					'Authority' 	 => $trans_id ,
					'Amount'	 	=> $amount
				)
		);

    }
}
