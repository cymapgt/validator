<?php
namespace cymapgt\core\utility\validator;

use cymapgt\Exception\ValidatorException;
use cymapgt\core\utility\validator\ValidatorBootstrap as vrb;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

/**
 * class TypeValidator
 * 
 * This class wraps Respect validatation library and provides parameterized
 * validation interface for data type validation of client side or API inputs
 * 
 * @author    - Cyril Ogana <cogana@gmail.com>
 * @package   - cymapgt.core.utility.validator
 * @copyright - CYMAP BUSINESS SOLUTIONS
 */
class TypeValidator
{
    private static $_respectMethodRef = null;
    private static $_vMode          = 'assert';
    
    
    public static function setMode($vMode) {
        $vModeArr = array('assert', 'check', 'validate');
        
        if (!(in_array($vMode, $vModeArr))) {
            throw new ValidatorException('Attempt to set validation mode to an invalid value!');
        }
        
        self::$_vMode = $vMode;
    }
    
    private static function _parseMethodChain($value, array $validatorCallArr) {
        //get concrete instance of respect validator
        $vObj = new v;
        
        //bucket array for the rules only
        $validatorCallArrRules = $validatorCallArr;
        
        //add the actual validation call to the method chain array
        $validatorCallArr[(self::$_vMode)] = array($value);
        
        //validate with exception handling
        try {
            foreach ($validatorCallArr as $method => $args) {
                //echo "call_user_func_array(array(vObj, $method), args)".PHP_EOL;
                call_user_func_array(array($vObj, $method), $args);
            }
            
            return true;
        } catch (ValidationException $vExc) {
            $errorsArr = array();
            foreach  ($validatorCallArrRules as $vRule => $vRuleArgs) {
                $errorsArr = array_merge($errorsArr, $vExc->findMessages(array($vRule)));
            }

            return $errorsArr;
        }
    }
    
    private static function _validateValue($type, $value, $params) {
        switch ($type) {
        case 'string':
            $validatorCallArr = vrb::bootstrapStringValidation($params);
            break;
        case 'number':
            $validatorCallArr = vrb::bootstrapNumberValidation($params);
            break;
        case 'datetime':
            $validatorCallArr = vrb::bootstrapDatetimeValidation($params);
            break;
        case 'list':
            $validatorCallArr = vrb::bootstrapListValidation($params);
            break;
        default:
            throw new ValidatorException('Unknown bootstrap type provided. Cannot bootstrap validator!'); 
        }
        
        return self::_parseMethodChain($value, $validatorCallArr);
    }
    
    /**
       * Validate that the input is a variant and can be empty
       * @param   mixed $value    The input that is to be validated
       * @param   array  $params  Configuration parameters for the input
       *  
       */
    public static function varNull($value, array $params = array()) {
        $params['notempty'] = null;
        
        return self::_validateValue('string', $value, $params);
    }
    
    public static function varNotNull($value, array $params = array()) {
        $params['notempty'] = true;
        return self::_validateValue('string', $value, $params);       
    }
    
    public static function strNull($value, array $params = array()) {
        $params['string']   = true;
        $params['notempty'] = null;
        
        return self::_validateValue('string', $value, $params);               
    }
    
    public static function strNotNull($value, array $params = array()) {
        $params['string']   = true;
        $params['notempty'] = true;
        
        return self::_validateValue('string', $value, $params);
    }
    
    public static function intNull($value, array $params = array()) {
        $params['int']      = true;
        $params['notempty'] = null;
        
        return self::_validateValue('number', $value, $params);
    }
    
    public static function intNotNull($value, array $params = array()) {
        $params['int']      = true;
        $params['notempty'] = true;
        
        return self::_validateValue('number', $value, $params);
    }
    
    public static function decimalNull($value, array $params = array()) {
        $params['float']    = true;
        $params['notempty'] = null;
        
        return self::_validateValue('number', $value, $params);
    }
    
    public static function decimalNotNull($value, array $params = array()) {
        $params['float']    = true;
        $params['notempty'] = true;
        
        return self::_validateValue('number', $value, $params);
    }
    
    public static function datetimeNotNull($value, array $params = array()) {
        $params['date']     = true;
        $params['notempty'] = true;
        
        return self::_validateValue('datetime', $value, $params);
    }
    
    public static function listItemNotNull($value, array $params = array()) {
        //verify if the value is an array or a string
        if (!(is_array($value))) {
            //if it is a string, verify if it is json and decoee it else throw an exception
            $valueJsonDecoded = \json_decode($value, true);
            
            if (\json_last_error()) {
                return array (
                    'json' => 'JSON parse error: ' . \json_last_error_msg()
                );
            }

            //assign to a convergent variable array / json 
            $valueValidate = $valueJsonDecoded;
        } else {
            //assign to a convergent variable array / json 
            $valueValidate = $value;
        }
        $params['array']     = true;
        
        //run the validation
        return self::_validateValue('list', $valueValidate, $params);
    }
    
    public static function bool($value, array $params = array()) {
        $params['bool']     = true;
        
        return self::_validateValue('number', $value, $params);
    }
}
