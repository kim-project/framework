<?php

namespace Kim\Request;

use Kim\Support\Arrayable;

class Validator
{
    use Arrayable;

    private array $validated = [];

    private array $errors = [];

    private Request|Arrayable|array $request;

    public function __construct(Request|Arrayable|array $request, array $rules)
    {
        $this->request = $request;
        foreach ($rules as $field => $rule) {
            $this->validateRule($field, $rule);
        }
    }

    public function validateRule(string $field, string|array $rule): bool
    {
        if (! is_array($rule)) {
            $rule = explode('||', $rule);
        }
        if ($this->request instanceof Request) {
            $val = $this->request->input($field);
            if (! $val) {
                $val = $this->request->file($field);
            }
        } else {
            if ($this->request instanceof Arrayable) {
                $val = $this->request->toArray();
            } else {
                $val = $this->request;
            }

            if (array_key_exists($field, $val)) {
                $val = $val[$field];
            } else {
                $val = null;
            }
        }

        if ($val === null) {
            if (in_array('required', $rule)) {
                $this->errors[] = "$field is required!";

                return false;
            } else {
                $this->validated[$field] = $val;

                return true;
            }
        }

        foreach ($rule as $value) {
            switch ($value) {
                case 'file':
                    if (! $val instanceof File) {
                        $this->errors[] = "$field should be a file!";

                        return false;
                    }

                    continue;

                case 'required':

                    continue;

                case 'string':
                    if (! is_string($val)) {
                        $this->errors[] = "$field is not a string!";

                        return false;
                    }

                    continue;

                case 'int':
                    if (! $val = filter_var($val, FILTER_VALIDATE_INT)) {
                        $this->errors[] = "$field is not an integer!";

                        return false;
                    }

                    continue;

                case 'numeric':
                    if (! is_numeric($val)) {
                        $this->errors[] = "$field is not numeric!";

                        return false;
                    }

                    continue;

                case 'bool':
                    if (! $val = filter_var($val, FILTER_VALIDATE_BOOLEAN)) {
                        $this->errors[] = "$field is not a boolean!";

                        return false;
                    }

                    continue;

                case 'array':
                    if (! is_array($val)) {
                        $this->errors[] = "$field is not an array!";

                        return false;
                    }

                    continue;

                case 'float':
                    if (! $val = filter_var($val, FILTER_VALIDATE_FLOAT)) {
                        $this->errors[] = "$field is not a float!";

                        return false;
                    }

                    continue;

                case 'email':
                    if (! filter_var($val, FILTER_VALIDATE_EMAIL)) {
                        $this->errors[] = "$field is not a valid email!";

                        return false;
                    }

                    continue;

                case 'url':
                    if (! filter_var($val, FILTER_VALIDATE_URL)) {
                        $this->errors[] = "$field is not a valid url!";

                        return false;
                    }

                    continue;

                case 'domain':
                    if (! filter_var($val, FILTER_VALIDATE_DOMAIN)) {
                        $this->errors[] = "$field is not a valid domain!";

                        return false;
                    }

                    continue;

                case 'ip':
                    if (! filter_var($val, FILTER_VALIDATE_IP)) {
                        $this->errors[] = "$field is not a valid ip!";

                        return false;
                    }

                    continue;

                case 'mac':
                    if (! filter_var($val, FILTER_VALIDATE_MAC)) {
                        $this->errors[] = "$field is not a valid mac address!";

                        return false;
                    }

                    continue;
            }

            $value = explode(':', $value);
            if (! isset($value[1])) {
                continue;
            }

            switch ($value[0]) {
                case 'mime':
                    $allowed = explode(',', $value[1]);
                    if (! $val instanceof File || ! in_array($val->mimeType(), $allowed)) {
                        $this->errors[] = "$field invalid file type!";

                        return false;
                    }

                    continue;

                case 'min':
                    $options = ['options' => ['min_range' => $value[1]]];
                    if (! filter_var($val, FILTER_VALIDATE_INT, $options) && ! filter_var($val, FILTER_VALIDATE_FLOAT, $options)) {
                        $this->errors[] = "$field should be bigger than {$value[1]}!";

                        return false;
                    }

                    continue;

                case 'max':
                    $options = ['options' => ['max_range' => $value[1]]];
                    if (! filter_var($val, FILTER_VALIDATE_INT, $options) && ! filter_var($val, FILTER_VALIDATE_FLOAT, $options)) {
                        $this->errors[] = "$field should be smaller than {$value[1]}!";

                        return false;
                    }

                    continue;

                case 'min-len':
                    if (! is_string($val)) {
                        $this->errors[] = "$field is not a string!";

                        return false;
                    }
                    if (strlen($val) < $value[1]) {
                        $this->errors[] = "$field should be more than {$value[1]} characters!";

                        return false;
                    }

                    continue;

                case 'max-len':
                    if (! is_string($val)) {
                        $this->errors[] = "$field is not a string!";

                        return false;
                    }
                    if (strlen($val) > $value[1]) {
                        $this->errors[] = "$field should be less than {$value[1]} characters!";

                        return false;
                    }

                    continue;

                case 'decimals':
                    if (! $val = filter_var($val, FILTER_VALIDATE_FLOAT, ['options' => ['decimal' => $value[1]]])) {
                        $this->errors[] = "$field should have {$value[1]} decimals!";

                        return false;
                    }

                    continue;

                case 'regex':
                    if (! is_string($val)) {
                        $this->errors[] = "$field is not a string!";

                        return false;
                    }
                    if (! preg_match($value[1], $val)) {
                        $this->errors[] = "Invalid $field!";

                        return false;
                    }

                    continue;
            }
        }
        $this->validated[$field] = $val;

        return true;
    }

    public function toArray(): array
    {
        if ($this->errors !== []) {
            throw new \Exception($this->errors[0], 400);
        }

        return $this->validated;
    }

    public function validated(array|string|int $only = []): mixed
    {
        if ($only === []) {
            return $this->toArray();
        } else {
            return $this->only($only);
        }
    }

    public function errors(): array|bool
    {
        if ($this->errors === []) {
            return false;
        } else {
            return $this->errors;
        }
    }
}
