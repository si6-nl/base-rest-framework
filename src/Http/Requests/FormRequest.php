<?php

namespace Si6\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;

abstract class FormRequest extends HttpFormRequest
{
    use DataTypeRule;

    protected $required = false;

    protected $requiredWith = [];

    protected $nullable = true;

    protected function notRequire()
    {
        $this->required = false;
        $this->nullable();

        return $this;
    }

    protected function require()
    {
        $this->required = true;
        $this->notNull();

        return $this;
    }

    protected function requireWith($field)
    {
        $this->requiredWith = ["required_with:$field"];

        return $this;
    }

    protected function nullable()
    {
        $this->nullable = true;

        return $this;
    }

    protected function notNull()
    {
        $this->nullable = false;

        return $this;
    }

    protected function resetRequire()
    {
        $this->notRequire();
        $this->requiredWith = [];

        return $this;
    }

    protected function rule()
    {
        $required     = $this->required ? ['required'] : [];
        $requiredWith = $this->requiredWith ?: [];
        $nullable     = $this->nullable ? ['nullable'] : [];

        $rule = array_merge($required, $requiredWith, $nullable);
        $this->resetRequire();

        return $rule;
    }

    protected function ids()
    {
        return ['required', 'array'];
    }

    protected function idsElement()
    {
        $rule = ['required'];

        return array_merge($rule, $this->unsignedBigInteger());
    }

    public function messages()
    {
        return require __DIR__ . '/../../../resources/lang/en/validation.php';
    }
}
