<?php
namespace App\Helpers;

use Ramsey\Uuid\Uuid;

class Validator
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Validate all rules for a request
     *
     * @param array $data
     * @return array
     */
    public function validate(array $data): array
    {
        $params = $this->request->getParameters();

        $validationErrors = [];
        foreach ($data as $attribute => $rules) {
            $value = $params[$attribute];

            $validationError = $this->validateRules($attribute, $value, $rules);

            if (! empty($validationError)) {
                $validationErrors[] = $validationError;
            }
        }

        return $validationErrors;
    }

    /**
     * Validate rules for every attribute
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $rules
     * @return array
     */
    public function validateRules(string $attribute, $value, array $rules): array
    {
        $invalidInput = [];
        foreach ($rules as $rule) {

            if (! $this->isValidInput($rule, $attribute, $value)) {

                $message = $this->getInvalidMessage($rule, $attribute);
                $invalidInput = [
                    'attribute' => $attribute,
                    'message' => $message
                ];

                // We just show one message at a time for same param
                break;
            }
        }

        return $invalidInput;
    }

    /**
     * Checks if validation result has errors
     *
     * @param array $validation
     * @return bool
     */
    public function hasErrors(array $validation): bool
    {
        return (! empty($validation)) ? true : false;
    }

    /**
     * Replace :attribute placeholder with actual attribute name
     *
     * @param string $rule
     * @param string $attribute
     * @return string
     */
    public function getInvalidMessage(string $rule, string $attribute): string
    {
        $message = $this->getMessage($rule);

        $message = str_replace(':attribute', $attribute, $message);
        return $message;
    }

    /**
     * Get error message for a given attribute
     *
     * @param string $rule
     * @return string
     */
    public function getMessage(string $rule): string
    {
        $messages = [
            'required' => ':attribute is required',
            'integer' => ':attribute must be a valid integer',
            'uuid' => ':attribute must be a valid uuid'
        ];

        return $messages[$rule];
    }

    /**
     * Calls appropriate function as per given validation
     *
     * @param string $rule
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    protected function isValidInput(string $rule, string $attribute, $value): bool
    {
        switch ($rule) {
            case 'required':
                return $this->checkRequired($value);
            case 'integer':
                return $this->checkInteger($attribute, $value);
            case 'uuid':
                return $this->checkUUID($value);
        }

        return true;
    }

    /**
     * Checks if request param is not empty
     *
     * @param mixed $value
     * @return bool
     */
    protected function checkRequired($value): bool
    {
        return (! empty($value));
    }

    /**
     * Checks integer value
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    protected function checkInteger(string $attribute, $value): bool
    {
        if ($this->checkRequired($value)) {

            $_REQUEST[$attribute] = is_numeric($value) ? (int) $value : $value;

            return (is_int($_REQUEST[$attribute]));
        }

        return false;
    }

    /**
     * Checks if request param is valid UUID or not
     *
     * @param string $value
     * @return bool
     */
    protected function checkUUID(string $value): bool
    {
        return Uuid::isValid($value);
    }
}