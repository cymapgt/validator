# validator
A wrapper for the PHP Respect Validation Package. It captures Respect vocabulary
enabling less verbose validation. It is intended for backend validation for Web;
API or Mobile UI libraries

## Description

The validator package allows us to utilize the Respect validation package by
configuration of validation arrays. The arrays describe the rules to be used
when validating the data, which the Validator service then generates the method
chains to validate the input data.

## Installing

### Install application via Composer

    require "cymapgt/validator" : "^1.0.0"

## Usage

### Overview

Validator package has the following objectives:

- Create a custom validator class for each natural and derived data type for valuation e.g. strings, integers

- The validators will be parameterizable through a configuration array stored in an array that is loaded to the class at runtime. They will also have default values.

- For each derived datatype, allow it to inherit  validation rules from its parent data types

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

### Testing

PHPUnit Tests are provided with the package

### Contribute

* Email @rhossis or contact via Skype
* You will be added as author for contributions

### License

PROPRIETARY
