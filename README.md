# validator
This package can validate data types wrapping the Respect package. It can perform validation of data types with names based on the data types used on relational databases. So it can validate values that can be null or not null of string, varchar, integer, decimal, datetime, list item or boolean.
The validation functions can take arrays as parameters to define options of the validation rules. 

The validation rules are defined in an array, such that data with several validation rules e.g. (interger, not nullable, unsigned) is validated with one function call.

## Description

The validator package allows us to utilize the Respect validation package by configuration of validation arrays. The arrays describe the rules to be used when validating the data, which the Validator service then generates the method chains to validate the input data.

## Installing

### Install application via Composer

    require "cymapgt/validator" : "^1.0.0"

## Usage

### Overview

Validator package has the following objectives:

- Create a custom validator class for each natural and derived data type for valuation e.g. strings, integers

- The validators will be parametrizable through a configuration array stored in an array that is loaded to the class at runtime. They will also have default values.

- For each derived data-type, allow it to inherit  validation rules from its parent data types

- Use assertations to force exception throwing, as well as returning of error values to the ClientSide or ErrorHandler

- Provide flag to either use customized error messages or the ‘tree’ structure of exceptions that is provided

- Provide the option to use ‘check’ or ‘assert’ when performing validations

### Using the Validator package
#### Data Types

The data types supported by the class are "borrowed" from the types used by most RDBMS.
The idea here is to simplify the base datatypes; and allow for further validation rules
to be added via an array of parameters. Passing an illegal rule option will result
in a ValidatorException.

The data types are

-strNull: String, not null

-strNotNull: String, nullable

-varNull: VarChar, not null

-varNotNull: VarChar, nullable

-intNull: Integer, not null

-intNotNull: Integer, nullable

-decimalNotNull: Floating point number, not null

-decimalNull: Floating point number, nullable

-datetimeNotNull: DateTime not null

-listItemNotNull: List item not null (can validate json objects as well as PHP arrays)

-bool: Boolean data type (not nullable)

#### Exception Handling
There are three types of validation modes, which affect how your validator responds
when it encounters invalid data type:

1.validate: The validator will return true or false, depending on whether data is valid

2.check: The validator will return true, or the first exception it encounters

3.assert: The validator will return true, or nest all the exceptions encountered in the datatype

By default, the package sets mode to assert. If there is an exception, it returns
a neat associative array with the description of the issues encountered.

To change mode, use the setMode() method:
    
    use cymapgt\core\utility\validator\TypeValidator;

    $mode = 'validate';
    TypeValidator::setMode($mode);

An example of retrieving the assertations:

    $jsonStringBroken = '
        "id": 1,
        "name": "Foo",
        "price": 123,
        "tags": [
          "Bar",
          "Eek"
        ],
        "stock": {
          "warehouse": 300,
          "retail": 20
        }
    }';
    
    $isValid = TypeValidator::listItemNotNull($jsonStringBroken));

    if (is_array($isValid)) {
        echo 'Errors found:' . PHP_EOL;
        foreach ($isValid as $key => $value)) {
            echo '-' . $key . ': ' . $value . PHP_EOL;
        }
    }

#### Type Validation

    //basic validation
    use cymapgt\core\utility\validator\TypeValidator;

    $testVal = '%rhossis83!';
    $isValid = TypeValidator::varNull($testVal);
    
    //validation with parameters
    $paramOptions = array (
        'alphaonly' => true,
        'directory' => true,
        'length' => array (
            'min' => 0, 
            'max' => 20,
            'inclusive' => true
    );

    $isValid = TypeValidator::varNotNull($testVal, $paramOptions);

    //decimal validation
    $testVal = 1000.0;
    $isValid = TypeValidator::decimalNotNull($testVal);

    //list item validation (can be array or json. Note there is also a json param for validating strings)
    $jsonString = '{
        "id": 1,
        "name": "Foo",
        "price": 123,
        "tags": [
          "Bar",
          "Eek"
        ],
        "stock": {
          "warehouse": 300,
          "retail": 20
        }
    }';

    $isValid = TypeValidator::listItemNotNull($jsonString);

### Example

This example will show how to define a validator for data entering a MySQL table that stores product listing for a small shop.

#### SQL Table Structure

    CREATE TABLE `products` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(60) NOT NULL,
      `product_number` varchar(15) NOT NULL,
      `in_stock` tinyint(1) unsigned NOT NULL,
      `reorder_level` int(4) unsigned NOT NULL,
      `safety_stock_level` int(4) unsigned NOT NULL,
      `color` varchar(30) NOT NULL,
      `size` varchar(5) NOT NULL,
      `price` decimal(10,2) unsigned NOT NULL,
      `listing_currency` varchar(3) NOT NULL,
      `weight` decimal(10,2) unsigned NOT NULL,
      `weight_uom` varchar(3) NOT NULL,
     `product_line` varchar(15) NOT NULL,
      `sell_start_date` timestamp NOT NULL DEFAULT         CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      `sell_end_date` timestamp NOT NULL,
      `special_notes` varchar(45) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#### Creating Your Validator Parameters

    <?php
    namespace Example\Product;

	class ProductSettings
	{
		public static function validation() {
			return array (
			'name' => array (
				'type' => 'varNotNull',
				'params' => array (
					'length' => array (
					'min' => 1,
					'max' => 60,
					'inclusive' => true
				)
			),
			'product_number' => array (
				'type' => 'varNotNull',
				'params' => array (
					'length' => array (
					'min' => 1,
					'max' => 15,
					'inclusive' => true
			),
			'in_stock' => array (
				'type' => 'bool',
				'params' => array()
			),
			'reorder_level' => array (
				'type' => 'intNotNull',
				'params' => array (
					'length' => array (
					'min' => 1,
					'max' => 4,
					'inclusive' => true
				),               
			'safety_stock_level' => array (
				'type' => 'intNotNull',
				'params' => array (
				'length' => array (
					'min' => 1,
					'max' => 4,
					'inclusive' => true
				),
			'color' => array (
				'type' => 'varNotNull',
				'params' => array (
					'length' => array (
					'min' => 1,
					'max' => 30,
					'inclusive' => true
			),
			'size' => array (
				'type' => 'varNotNull',
				'params' => array (
					'length' => array (
					'min' => 1,
					'max' => 5,
					'inclusive' => true
			),
			'weight' => array (
				'type' => 'decimalNotNull',
				'params' => array (
					'length' => array (
					'min' => 1,
					'max' => 11,
					'inclusive' => true
			),
			'weight_uom' => array (
				'type' => 'varcharNotNull',
				'params' => array (
					'length' => array (
					'min' => 1,
					'max' => 3,
					'inclusive' => true
			),
			'product_line' => array (
				'type' => 'varcharNotNull',
				'params' => array (
					'length' => array (
					'min' => 1,
					'max' => 15,
					'inclusive' => true
			),
			'sell_start_date' => array (
				'type' => 'datetimeNotNull',
				'params' => array ()
			),
			'sell_end_date' => array (
				'type' => 'datetimeNotNull',
				'params' => array ()
			),
			'special_notes' => array (
				'type' => 'varNull',
				'params' => array ()
			));
		}
	}
	
	

#### Validating The Data

Assuming the data has arrived to your server, which is an API endpoint, for creation of the product entry. We are using the default check mode of assert, which will store all Exceptions in an array and return them at the end for error reporting. If validation is successful, the validation method returns true.
    
    use cymapgt\core\utility\validator\TypeValidator;
    use Example\Product;
    
    //receive data from client
    $productEntriesCollection = json_decode($productJsonString, true);

	//validation func
	$validateProduct = function($productEntry) {
		//product settings config
		$validationSettings = ProductSettings::validation();
					
		foreach ($productEntry as $fieldName => $fieldValue) {			
			//get field validation settings 
			$fieldType = $validationSettings[$fieldName]['type'];
			$fieldParams = $validationSettings[$fieldName]['params'];

			//validate the field
			$result = TypeValidator::$fieldType($fieldValue, $fieldParams);
			
			//handle validation
			if ($result === true) {
				//send to queue for processing
			} elseif (is_array($result)) {
			    //iterate result and store errors for reporting back
			} else {
			    //return error
			}
		}		
	}
  
  	//validate all entriees in collection
	foreach ($productEntriesCollection as $productEntry) {
	    $validateProduct($productEntry);
	}
### Testing

PHPUnit Tests are provided with the package

### Contribute

* Email @rhossis or contact via Skype
* You will be added as author for contributions

### License

BSD-3 CLAUSE

