<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 19.11.2017
 * Time: 10:20
 */

namespace IhorRadchenko\App\Components\Validation;

use IhorRadchenko\App\DataBase;

class Validator
{
    /**
     * @var ValidationErrorHandler $validationErrorHandler
     */
    private $validationErrorHandler;
    /**
     * Массив с возможными проверками
     * @var array $rules
     */
    private $rules = ['required', 'minLength', 'maxLength', 'email', 'alnum', 'match', 'unique'];
    /**
     * Массив с сообщениями об ошибке
     * @var array $messages
     */
    private $messages = [
        'required'  => 'Поле должно быть заполнено',
        'minLength' => 'Поле должно содержать не менее :value символов',
        'maxLength' => 'Поле должно содержать не более :value символов',
        'email'     => 'Не корректный email',
        'alnum'     => 'В строке должны быть только буквы или числа',
        'match'     => 'Поля не совпадают',
        'unique'    => 'Такое значение уже существует'
    ];
    private $data;

    public function __construct(ValidationErrorHandler $validationErrorHandler)
    {
        $this->validationErrorHandler = $validationErrorHandler;
    }

    public function check($data, $rules): self
    {
        $this->data = $data;
        foreach ($data as $item => $value) {
            if (in_array($item, array_keys($rules))) {
                $this->validate([
                    'field' => $item,
                    'value' => $value,
                    'rules' => $rules[$item]
                ]);
            }
        }
        return $this;
    }

    public function errors(): ValidationErrorHandler
    {
        return $this->validationErrorHandler;
    }

    public function fails(): bool
    {
        return $this->validationErrorHandler->hasErrors();
    }

    private function validate($item)
    {
        $field = $item['field'];
        foreach ($item['rules'] as $rule => $ruleValue) {
            if (in_array($rule, $this->rules) && (! call_user_func_array([$this, $rule], [$field, $item['value'], $ruleValue]))) {
                $this->validationErrorHandler->addError(
                    $field,
                    str_replace(':value', $ruleValue, $this->messages[$rule])
                );
            }
        }
    }

    private function required($field, $value, $ruleValue): bool
    {
        return (! empty(trim($value)));
    }

    private function minLength($field, $value, $ruleValue): bool
    {
        return mb_strlen(trim($value)) >= $ruleValue;
    }

    private function maxLength($field, $value, $ruleValue): bool
    {
        return mb_strlen(trim($value)) <= $ruleValue;
    }

    private function email($field, $value, $ruleValue): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    private function alnum($field, $value, $ruleValue): bool
    {
        return ctype_alnum($value);
    }

    private function match($field, $value, $ruleValue): bool
    {
        return ($value === $this->data[$ruleValue]);
    }

    private function unique($field, $value, $ruleValue): bool
    {
        return empty(DataBase::getInstance()->get($field, $ruleValue, $value));
    }
}