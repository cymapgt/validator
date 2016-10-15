<?php
namespace cymapgt\core\utility\validator;

use cymapgt\Exception\ValidatorException;

/**
 * class ValidatorBootstrap
 * 
 * This class provides mappings to enable usage of the Respect validator by our Validator classes
 * 
 * @author    - Cyril Ogana <cogana@gmail.com>
 * @package   - cymapgt.core.utility.validator
 * @copyright - CYMAP BUSINESS SOLUTIONS
 */
class ValidatorBootstrap
{
   /**
       * Cyril Ogana <cogana@gmail.com> - 2014-11-23
       *   
       * Return mappings for methods for configurations to the Respect validator methods
       * @return    array
       */    
    public static function getMethodMap() {
        return array (
            //types
            'array'         => 'arr',
            'bool'          => 'bool',
            'date'          => 'date',
            'float'         => 'float',
            'instance'      => 'instance',
            'int'           => 'int',
            'null'          => 'nullValue',
            'numeric'       => 'numeric',
            'object'        => 'object',
            'string'        => 'string',
            'hex'           => 'xdigit',
            'notempty'      => 'notEmpty', 
            'optional'      => 'optional',
            //generics
            'call'          => 'call',
            'callback'      => 'callback',
            'callabletype'  => 'callableType',
            'not'           => 'not',
            'when'          => 'when',
            'alwaysvalid'   => 'alwaysValid',
            'alwaysinvalid' => 'alwaysinvalid',
            //comparisons
            'between'       => 'between',
            'equals'        => 'equals',
            'max'           => 'max',
            'min'           => 'min'  ,
            //numbers
            'even'          => 'even',
            'multiple'      => 'multiple',
            'negative'      => 'negative',
            'odd'           => 'odd',
            'perfectsquare' => 'perfectsquare',
            'positive'      => 'positive',
            'primenumber'   => 'primenumber',
            'roman'         => 'roman',
            //string
            'alphanumeric'  => 'alnum',
            'alphaonly'     => 'alpha',
            'charset'       => 'charset',
            'consonant'     => 'consonant',
            'contains'      => 'contains',
            'control'       => 'cntrl',
            'digit'         => 'digit',
            'endswith'      => 'endsWith',
            'substr'        => 'in',
            'graphical'     => 'graph',
            'length'        => 'length',
            'lowercase'     => 'lowercase',
            'nowhitespace'  => 'noWhitespace',
            'graphicalw'    => 'prnt',
            'punctuated'    => 'punct',
            'regex'         => 'regex',
            'slug'          => 'slug',
            'spaceonly'     => 'space',
            'startswith'    => 'startsWith',
            'uppercase'     => 'uppercase',
            'versioned'     => 'version',
            'vowel'         => 'vowel',
            //arrays
            'contains'      => 'contains',
            'each'          => 'each',
            'endswitharr'   => 'endsWith',
            'inarr'         => 'in',
            'key'           => 'key',
            'lengtharr'     => 'length',
            'notemptyarr'   => 'notEmpty',
            'startswitharr' => 'startsWith',
            //objects
            'attribute'     => 'attribute',
            'instance'      => 'instance',
            'lengthobj'     => 'length',
            //datetime
            'leadpdate'       => 'leapDate',
            'leapyear'        => 'leapYear',
            'minimumage'      => 'minimumAge',
            'age'             => 'age',
            //group validators
            'allof'           => 'allof',
            'noneof'          => 'noneof',
            'oneof'           => 'oneof',
            //regional
            'topleveldomain'  => 'tld',
            'countrycode'     => 'countryCode',
            'subdivisioncode' => 'subdivisionCode',
            //files
            'directory'       => 'directory',
            'executable'      => 'executable',
            'extension'       => 'extension',
            'exists'          => 'exists',
            'file'            => 'file',
            'readable'        => 'readable',
            'symlink'         => 'symbolicLink',
            'uploaded'        => 'uploaded',
            'writable'        => 'writable',
            //other
            'creditcard'      => 'creditCard',            
            'domainname'      => 'domain',
            'email'           => 'email',
            'ipaddress'       => 'ip',
            'json'            => 'json',
            'macaddress'      => 'macAddress',
            'phone'           => 'phone',
            'url'             => 'url',
            'videourl'        => 'videoUrl',
            //math
            'factor'          => 'factor'
        );
    }
    
    /**
       * Cyril Ogana <cogana@gmail.com> - 2014-11-23
       *  
       *  Bootstrap the string validators
       * 
       * @param array $params - Parameters for evaluating a particular validation
       * 
       * @return    array
       */
    public static function bootstrapStringValidation(array $params) {
        //get the method map
        $vMethodMap = self::getMethodMap();
        
        //establish if we have some invalid params
        $paramsDiff = array_diff_key($params, $vMethodMap);
        
        //throw exception if we have some invalid params
        if (!(empty($paramsDiff))) {
            throw new ValidatorException('An attempt has been made to bootstrap the string validator with illegal parameters!');
        }
        
        //instantiate the call chain
        $paramCallChain = array();
        $a = 0;
        
        foreach($params as $paramType => $strParam) {
            if (is_null($strParam)) {
                continue;
            }
            
            $paramCall = self::_parseStringValidation($paramType, $strParam);
            
            $paramCallChain = array_merge($paramCallChain, $paramCall);
            //print_r($paramCallChain);
            ++$a;
        }
        
        return $paramCallChain;
    }
    
    /**
        * Cyril Ogana <cogana@gmail.com> - 2014-11-23
       * 
        * @param string $paramType - The validation type requried for the string
        * @param array  $strParam - none, one or more parameters for the validation method
        * 
        * @return array  (of the method and params to add to method chain)
        */
    private static function _parseStringValidation($paramType, $strParam) {
        switch ($paramType) {
            case 'string':
                return array('stringType' => array());
            case 'alphanumeric':
                return array('alnum' => array());
            case 'alphaonly':
                return array('alpha' => array());
            case 'boolval':
                return array('boolVal' => array());
            case 'charset':
                return array('charset' => array($strParam['charset'], $strParam['encoding']));
            case 'consonant':
                return array('consonant' => array());
            case 'contains':
                return array('contains' => array($strParam['value'], $strParam['identical']));
            case 'control':
                return array('cntrl' => array($strParam['additional']));
            case 'digit':
                return array('digit' => array());
            case 'endswith':
                return array('endsWith' => array($strParam['endwith']));
            case 'substr':
                return array('in' => array($strParam['haystack'], $strParam['identical']));
            case 'graphical':
                return array('graph' => array($strParam['additional']));
            case 'length':
                return array('length' => array($strParam['min'], $strParam['max'], $strParam['inclusive']));
            case 'lowercase':
                return array('lowercase' => array());
            case 'notempty':
                return array('notEmpty' => array());
            case 'nowhitespace':
                return array('noWhitespace' => array());
            case 'graphicalw':
                return array('prnt' => array($strParam['additional']));
            case 'punctuated':
                return array('punct' => array($strParam['punct']));
            case 'regex':
                return array('regex' => array($strParam['regex']));
            case 'slug':
                return array('slug' => array());
            case 'spaceonly':
                return array('space' => array($strParam['additional']));
            case 'startswith':
                return array('startsWith' => array($strParam['startswith'], $strParam['identical']));
            case 'uppercase':
                return array('uppercase' => array());
            case 'versioned':
                return array('version' => array());
            case 'vowel':
                return array('vowel' => array());
            case 'topleveldomain':
                return array('tld' => array());
            case 'countrycode':
                return array('countryCode' => array());
            case 'subdivisionCode':
                return array('subdivisionCode' => array($strParam['countryCode']));
            case 'directory':
                return array('directory' => array());
            case 'executable':
                return array('executable' => array());
            case 'extension':
                return array('extension' => array());
            case 'exists':
                return array('exists' => array());
            case 'file':
                return array('file' => array());
            case 'readable':
                return array('readable' => array());
            case 'symlink':
                return array('symbolicLink' => array());
            case 'uploaded':
                return array('uploaded' => array());
            case 'writable':
                return array('writable' => array());
            case 'creditcard':
                return array('creditCard' => array($strParam['ccBrand']));
            case 'domainname':
                return array('domain' => array());
            case 'email':
                return array('email' => array());
            case 'ipaddress':
                return array('ip' => array());
            case 'json':
                return array('json' => array());
            case 'macaddress':
                return array('macAddress' => array());
            case 'phone':
                return array('phone' => array());
            case 'url':
                return array('url' => array());
            case 'videourl':
                return array('site' => array());
            default:
                throw new ValidatorException('Illegal parameter type issued when parsing string validator!');
        }
    }

    /**
       * Cyril Ogana <cogana@gmail.com> - 2014-11-23
       *  
       *  Bootstrap the number validators
       * 
       * @param array $params - Parameters for evaluating a particular validation
       * 
       * @return    array
       */
    public static function bootstrapNumberValidation(array $params) {
        //get the method map
        $vMethodMap = self::getMethodMap();
        
        //establish if we have some invalid params
        $paramsDiff = array_diff_key($params, $vMethodMap);
        
        //throw exception if we have some invalid params
        if (!(empty($paramsDiff))) {
            throw new ValidatorException('An attempt has been made to bootstrap the number validator with illegal parameters!');
        }
        
        //instantiate the call chain
        $paramCallChain = array();
        $a = 0;
        
        foreach($params as $paramType => $strParam) {
            if (is_null($strParam)) {
                continue;
            }
            
            $paramCall = self::_parseNumberValidation($paramType, $strParam);
            $paramCallChain = array_merge($paramCallChain, $paramCall);
            ++$a;
        }
        
        return $paramCallChain;
    }
    
    /**
        * Cyril Ogana <cogana@gmail.com> - 2014-11-23
       * 
        * @param string $paramType - The validation type requried for the number
        * @param array  $strParam - none, one or more parameters for the validation method
        * 
        * @return array  (of the method and params to add to method chain)
        */
    private static function _parseNumberValidation($paramType, $strParam) {
        switch ($paramType) {
            case 'int':
                return array('intType' => array());
            case 'float':
                return array('floatType' => array());
            case 'numeric':
                return array('numeric' => array());
            case 'hex':
                return array('xdigit' => array());
            case 'notempty':
                return array('notEmpty' => array());                
            case 'even':
                return array('even' => array());
            case 'multiple':
                return array('multiple' => array($strParam['multipleof']));
            case 'negative':
                return array('negative' => array());
            case 'odd':
                return array('odd' => array());
            case 'pefectsquare':
                return array('perfectSquare' => array());
            case 'positive':
                return array('positive' => array());
            case 'primenumber':
                return array('primeNumber' => array());
            case 'roman':
                return array('roman' => array());
            case 'factor':
                return array('factor' => array($strParam['dividend']));
            case 'length':
            return array('length' => array($strParam['min'], $strParam['max'], $strParam['inclusive']));                
            default;
                throw new ValidatorException('Illegal parameter type issued when parsing number validator!');
        }
    }
    
    /**
       * Cyril Ogana <cogana@gmail.com> - 2014-11-23
       *  
       *  Bootstrap the date/time validators
       * 
       * @param array $params - Parameters for evaluating a particular validation
       * 
       * @return    array
       */
    public static function bootstrapDatetimeValidation(array $params) {
        //get the method map
        $vMethodMap = self::getMethodMap();
        
        //establish if we have some invalid params
        $paramsDiff = array_diff_key($params, $vMethodMap);
        
        //throw exception if we have some invalid params
        if (!(empty($paramsDiff))) {
            throw new ValidatorException('An attempt has been made to bootstrap the date/time validator with illegal parameters!');
        }
        
        //instantiate the call chain
        $paramCallChain = array();
        $a = 0;
        
        foreach($params as $paramType => $strParam) {
            if (is_null($strParam)) {
                continue;
            }
            
            $paramCall = self::_parseDatetimeValidation($paramType, $strParam);
            $paramCallChain = array_merge($paramCallChain, $paramCall);
            ++$a;
        }
        
        return $paramCallChain;
    }
    
    /**
        * Cyril Ogana <cogana@gmail.com> - 2014-11-23
       * 
        * @param string $paramType - The validation type requried for the number
        * @param array  $strParam - none, one or more parameters for the validation method
        * 
        * @return array  (of the method and params to add to method chain)
        */
    private static function _parseDatetimeValidation($paramType, $strParam) {
        switch ($paramType) {
            case 'date':
                return array ('date' => array());
            case 'notempty':
                return array ('notEmpty' => array());
            case 'leapdate':
                return array ('leapDate' => array($strParam['format']));
            case 'leapyear':
                return array ('leapYear' => array());
            case 'minimumage':
                return array ('minimumAge' => array($strParam['age']));
            case 'age':
                return array ('age' => array($strParam['min'], $strParam['max']));
            default:
                throw new ValidatorException('Illegal parameter type issued when parsing datetime validator!');
        }
    }
    
    /**
       * Cyril Ogana <cogana@gmail.com> - 2014-11-23
       *  
       *  Bootstrap the list validators
       * 
       * @param array $params - Parameters for evaluating a particular validation
       * 
       * @return    array
       */
    public static function bootstrapListValidation(array $params) {
        //get the method map
        $vMethodMap = self::getMethodMap();
        
        //establish if we have some invalid params
        $paramsDiff = array_diff_key($params, $vMethodMap);
        
        //throw exception if we have some invalid params
        if (!(empty($paramsDiff))) {
            throw new ValidatorException('An attempt has been made to bootstrap the list validator with illegal parameters!');
        }
        
        //instantiate the call chain
        $paramCallChain = array();
        $a = 0;
        
        foreach($params as $paramType => $strParam) {
            if (is_null($strParam)) {
                continue;
            }
            
            $paramCall = self::_parseListValidation($paramType, $strParam);
            $paramCallChain = array_merge($paramCallChain, $paramCall);
            ++$a;
        }
        return $paramCallChain;
    }
    
    /**
        * Cyril Ogana <cogana@gmail.com> - 2014-11-23
       * 
        * @param string $paramType - The validation type requried for the number
        * @param array  $strParam - none, one or more parameters for the validation method
        * 
        * @return array  (of the method and params to add to method chain)
        */
    private static function _parseListValidation($paramType, $strParam) {
        switch ($paramType) {
            case 'array':
                return array('arrayType' => array());
            case 'contains':
                return array('contains' => array($strParam['value'], $strParam['identical']));
            case 'inarr':
                return array('in' => array($strParam['haystack'], $strParam['identical']));
            case 'endswitharr':
                return array('endsWith' => array($strParam['value']));
            case 'lengtharr':
                return array('length' => array($strParam['min'], $strParam['max'], $strParam['inclusive']));
            case 'notempty':
                return array('notEmpty' => array());
            case 'startswitharr':
                return array('startsWith' => array($strParam['value']));
            default:
                throw new ValidatorException('Illegal parameter type issued when parsing list validator!');
        }
    }

    
    /**
       * Cyril Ogana <cogana@gmail.com> - 2016-10-15
       *  
       *  Bootstrap the boolean validators
       * 
       * @param array $params - Parameters for evaluating a particular validation
       * 
       * @return    array
       */
    public static function bootstrapBoolValidation(array $params) {
        //get the method map
        $vMethodMap = self::getMethodMap();
        
        //establish if we have some invalid params
        $paramsDiff = array_diff_key($params, $vMethodMap);
        
        //throw exception if we have some invalid params
        if (!(empty($paramsDiff))) {
            throw new ValidatorException('An attempt has been made to bootstrap the bool validator with illegal parameters!');
        }
        
        //instantiate the call chain
        $paramCallChain = array();
        $a = 0;
        
        foreach($params as $paramType => $strParam) {
            if (is_null($strParam)) {
                continue;
            }
            
            $paramCall = self::_parseBoolValidation($paramType, $strParam);
            $paramCallChain = array_merge($paramCallChain, $paramCall);
            ++$a;
        }
        return $paramCallChain;
    }
    
    /**
        * Cyril Ogana <cogana@gmail.com> - 2016-10-15
       * 
        * @param string $paramType - The validation type requried for the number
        * @param array  $strParam - none, one or more parameters for the validation method
        * 
        * @return array  (of the method and params to add to method chain)
        */
    private static function _parseBoolValidation($paramType, $strParam) {
        switch ($paramType) {
            case 'bool':
                return array('boolType' => array());
            default:
                throw new ValidatorException('Illegal parameter type issued when parsing Bool validator!');
        }
    }
    
    /**
       * Cyril Ogana <cogana@gmail.com> - 2014-11-23
       *  
       *  Bootstrap the list validators
       * 
       * @param array $params - Parameters for evaluating a particular validation
       * 
       * @return    array
       */
    public static function bootstrapConstraintValidation(array $params) {
        //get the method map
        $vMethodMap = self::getMethodMap();
        
        //establish if we have some invalid params
        $paramsDiff = array_diff_key($params, $vMethodMap);
        
        //throw exception if we have some invalid params
        if (!(empty($paramsDiff))) {
            throw new ValidatorException('An attempt has been made to bootstrap the constraint validator with illegal parameters!');
        }
        
        //instantiate the call chain
        $paramCallChain = array();
        $a = 0;
        
        foreach($params as $paramType => $strParam) {
            if (is_null($strParam)) {
                continue;
            }
            
            $paramCall = self::_parseConstraintValidation($paramType, $strParam);
            $paramCallChain = array_merge($paramCallChain, $paramCall);
            ++$a;
        }
        
        return $paramCallChain;
    }
    
    /**
        * Cyril Ogana <cogana@gmail.com> - 2014-11-23
       * 
        * @param string $paramType - The validation type requried for the number
        * @param array  $strParam - none, one or more parameters for the validation method
        * 
        * @return array  (of the method and params to add to method chain)
        */
    private static function _parseConstraintValidation($paramType, $strParam) {
        switch ($paramType) {

            case 'between':
                return array('between' => array($strParam['start'], $strParam['end'], $strParam['inclusive']));
            case 'equals':
                return array('equals' => array($strParam['value'], $strParam['identical']));
            case 'max':
                return array('max' => array($strParam['value'], $strParam['inclusive']));
            case 'min':
                return array('min' => array($strParam['value'], $strParam['inclusive']));
            default:
                throw new ValidatorException('Illegal parameter type issued when parsing constraint validator!');
        }
    }
}
