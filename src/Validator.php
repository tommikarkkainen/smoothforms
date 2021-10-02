<?php

/*!
 * This is the base class from which all form field validators should be
 * derived.
 */
abstract class Validator
{
    /*!
     * Constructor for a validator object.
     * 
     * @param $validatorCommand a string describing the validator
     */
    abstract function __construct(string $validatorCommand);

    /*!
     * Checks whether $value is valid according to this validator.
     * 
     * @param $value the value being validated
     * @returns true if valid and false otherwise
     */
    public abstract function isValid(mixed $value): bool;

    /*!
     * Returns the user-friendly error string explaining what was wrong with
     * the value that was being validated.
     */
    public abstract function getErrorString(): string;

    /*!
     * Returns a Validator based on a string variable typically originating
     * from a JSON object describing a form field.
     * 
     * @param $validatorCommand a string describing the validator
     */
    public static function create(string $validatorCommand): Validator
    {
        $validatorCommand = trim(strtolower($validatorCommand));

        // Validator command may consist of multiple parts. Only take the first
        // part to determine what kind of validator to use here.
        $validatorName = explode(":", $validatorCommand);
        $validatorName = $validatorName[0];

        // simple validators
        switch ($validatorName) {
            case 'required':
                return new RequiredValidator($validatorCommand);
                break;

            case 'email':
                return new EmailValidator($validatorCommand);
                break;

            case 'minlen':
            case 'maxlen':
                return new StringLengthValidator($validatorCommand);
                break;

            case 'numeric':
            case 'integer':
                return new NumericValidator($validatorCommand);

            case 'min':
            case 'max':
                return new NumberValueValidator($validatorCommand);
        }

        return null;
    }
}

?>