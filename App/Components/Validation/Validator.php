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
    private $rules = ['required', 'minLength', 'maxLength', 'email', 'alnum', 'match', 'unique', 'phone', 'length'];
    /**
     * Массив с сообщениями об ошибке
     * @var array $messages
     */
    private $messages = [
        'required'  => 'Поле :field должно быть заполнено',
        'minLength' => 'Поле :field должно содержать не менее :value символов',
        'maxLength' => 'Поле :field должно содержать не более :value символов',
        'email'     => 'Не корректный email',
        'alnum'     => 'В поле :field должны быть только буквы или числа',
        'match'     => ':field не совпадают',
        'unique'    => 'Такое значение поля :field уже существует',
        'phone'     => 'Номер телефона не валиден',
        'length'    => 'Длина поля :field должна составлять :value символов'
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
                    str_replace(
                        ['passwordAgain', 'password', 'email', 'f_name', 'l_name', 'phone_number', 'city', 'text'],
                        ['Пароли',  '\'Пароль\'', 'Email', '\'Имя\'', '\'Фамилия\'', '\'Номер телефона\'', '\'Город\'', '\'Текст\''],
                        str_replace([':value', ':field'], [$ruleValue, $field], $this->messages[$rule]))
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

    private function length($field, $value, $ruleValue): bool
    {
        return mb_strlen(trim($value)) === $ruleValue;
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

    /**
     * @param $field
     * @param $value
     * @param $ruleValue
     * @return bool
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    private function unique($field, $value, $ruleValue): bool
    {
        return empty(DataBase::getInstance()->get($ruleValue, $field, $value));
    }

    private function phone($field, $value, $ruleValue): bool
    {
        return preg_match('~^\+380[0-9]{9}$~', $value);
    }
}