<?php
/**
 * FIpValidator class file.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @version 1.0
 * @link http://www.yiiframework.com/extension/fmac-validator/
 * @license BSD
 */

/**
 * FIpValidator verifies if the attribute is a valid IP address.
 *
 */
final class FIpValidator extends CRegularExpressionValidator
{
	/**
	 * @var string the IP Protocol version to validate against
	 */
    private $_version='both';

    protected function setVersion($version)
    {
        $this->_version=strtolower($version);
        if (!in_array($this->_version, array('ipv4', 'ipv6', 'both')))
            throw new CException(Yii::t(__CLASS__,'Invalid IP version: {value}.', array('{value}'=>$this->_version)));
    }

    protected function getVersion()
    {
        return $this->_version;
    }

    protected function detectPattern()
    {
        switch ($this->_version) {
            case 'ipv4': // example: 192.168.0.255
                $this->pattern='/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/';
                break;
            case 'ipv6': // example:2001:0db8:85a3:08d3:1319:8a2e:0370:7334
                $this->pattern='/^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/';
                break;
        }
    }

	protected function validateAttribute($object,$attribute)
    {
		$value=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($value))
			return;

		$flags = array();
		if ($this->_version === 'ipv4' || $this->_version === 'both') {
			$flags[] = FILTER_FLAG_IPV4;
		}
		if ($this->_version === 'ipv6' || $this->_version === 'both') {
			$flags[] = FILTER_FLAG_IPV6;
		}
        $isValid=(boolean)filter_var($value, FILTER_VALIDATE_IP, array('flags' => $flags));
        if((!$this->not && !$isValid) || ($this->not && $isValid))
		{
			$message=$this->message!==null?$this->message : Yii::t(__CLASS__,'{attribute} is not a valid IP adress.');
			$this->addError($object,$attribute,$message);
		}
	}

	/**
	 * Returns the JavaScript needed for performing client-side validation.
	 * @param CModel $object the data object being validated
	 * @param string $attribute the name of the attribute to be validated.
	 * @return string the client-side validation script.
	 * @see CActiveForm::enableClientValidation
	 * @since 1.1.7
	 */
	public function clientValidateAttribute($object,$attribute)
	{
	    if (!$this->_version === 'both') {
            if (is_null($this->message))
                Yii::t(__CLASS__,'{attribute} is not a valid IP adress.');

            $this->detectPattern();
            return parent::clientValidateAttribute($object,$attribute);
        } else // no client side validation
            return '';
	}
}