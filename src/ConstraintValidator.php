<?php
namespace cymapgt\core\utility\validator;

use cymapgt\Exception\ValidatorException;
use cymapgt\core\utility\validator\ValidatorBootstrap as vrb;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

/**
 * class ConstraintValidator
 * 
 * This class wraps Respect validatation library and provides parameterized
 * validation interface for constraint validation of client side or API inputs
 * 
 * It should be used after types have passed necessary Type validations
 * 
 * @author    - Cyril Ogana <cogana@gmail.com>
 * @package   - cymapgt.core.utility.validator
 * @copyright - CYMAP BUSINESS SOLUTIONS
 */
class ConstraintValidator
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
        //die(print_r($validatorCallArr));
        //validate with exception handling
        try {
            foreach ($validatorCallArr as $method => $args) {
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
        case 'constraint':
            $validatorCallArr = vrb::bootstrapConstraintValidation($params);
            break;
        default:
            throw new ValidatorException('Unknown bootstrap type provided. Cannot bootstrap validator!'); 
        }
        
        return self::_parseMethodChain($value, $validatorCallArr);
    }
    
    /**
       * Validate the constraints ona particular datatype
       * @param   mixed $value    The input that is to be validated
       * @param   array  $params  Configuration parameters for the input
       *  
       */
    public static function constraints($value, array $params = array()) {
        return self::_validateValue('constraint', $value, $params);
    }
}
