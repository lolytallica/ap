<?php

/**
 * Validator
 *
 * Extending the Laravel Validator class allows us to define more powerful validation functions than
 * with the Validator::register method.
 */
class Validator extends Illuminate\Support\Facades\Validator {
    /**
     * Implicit Attributes
     *
     * Attributes with these rules will be validated even if no value is supplied.
     */
    protected $implicit_attributes = array(
        'required',
        'accepted',
        'required_if_attribute'
    );
    /**
     * Implicit
     *
     * By default Laravel will only validate an attribute if a value was supplied, or if the rule set is 'required' or 'accepted'.
     * It'll just skip the validation, which makes 'require' being conditional impossible. Let's overwrite that to be more flexible.
     */
    protected function implicit($rule) {
        return (in_array($rule, $this->implicit_attributes));
    }




    /**
     * Required if attribute
     *
     * Validate that a required attribute exists, only if another
     * attribute satisfies the supplied conditions.
     *
     */
    public function validateRequiredIfAttribute($attribute, $value, $parameters)
    {

        $required = false;

        switch($parameters[1])
        {

            case '==':
                $required = $this->data[$parameters[0]] == $parameters[2];
                break;
            case '!=':
                $required = $this->data[$parameters[0]] != $parameters[2];
                break;
            case '===':
                $required = $this->data[$parameters[0]] === $parameters[2];
                break;
            case '!==':
                $required = $this->data[$parameters[0]] !== $parameters[2];
                break;
            case '<':
                $required = $this->data[$parameters[0]] < $parameters[2];
                break;
            case '<=':
                $required = $this->data[$parameters[0]] <= $parameters[2];
                break;
            case '>':
                $required = $this->data[$parameters[0]] > $parameters[2];
                break;
            case '>=':
                $required = $this->data[$parameters[0]] >= $parameters[2];
                break;
        }

        return $required ? $this->validateRequired($attribute, $value) : true;

    }


}




?>