<?php

declare(strict_types=1);

namespace App\Core;

class Validator
{
    private array $data;
    private array $errors = [];
    private array $validated = [];

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function make(array $data): self
    {
        return new self($data);
    }

    public function required(string $field, ?string $label = null): self
    {
        $value = $this->data[$field] ?? null;
        if ($value === null || $value === '' || $value === []) {
            $this->addError($field, 'required', $label);
        } else {
            $this->validated[$field] = $value;
        }
        return $this;
    }

    public function email(string $field, ?string $label = null): self
    {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'email', $label);
        } else {
            $this->validated[$field] = $value;
        }
        return $this;
    }

    public function minLength(string $field, int $min, ?string $label = null): self
    {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && mb_strlen((string) $value) < $min) {
            $this->addError($field, 'minLength', $label, ['min' => $min]);
        } else {
            $this->validated[$field] = $value;
        }
        return $this;
    }

    public function maxLength(string $field, int $max, ?string $label = null): self
    {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && mb_strlen((string) $value) > $max) {
            $this->addError($field, 'maxLength', $label, ['max' => $max]);
        } else {
            $this->validated[$field] = $value;
        }
        return $this;
    }

    public function in(string $field, array $allowed, ?string $label = null): self
    {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && !in_array($value, $allowed, true)) {
            $this->addError($field, 'in', $label, ['values' => implode(', ', $allowed)]);
        } else {
            $this->validated[$field] = $value;
        }
        return $this;
    }

    public function numeric(string $field, ?string $label = null): self
    {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && !is_numeric($value)) {
            $this->addError($field, 'numeric', $label);
        } else {
            $this->validated[$field] = $value;
        }
        return $this;
    }

    public function integer(string $field, ?string $label = null): self
    {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && filter_var($value, FILTER_VALIDATE_INT) === false) {
            $this->addError($field, 'integer', $label);
        } else {
            $this->validated[$field] = $value;
        }
        return $this;
    }

    public function confirmed(string $field, ?string $label = null): self
    {
        $value = $this->data[$field] ?? '';
        $confirmation = $this->data[$field . '_confirmation'] ?? '';
        if ($value !== '' && $value !== $confirmation) {
            $this->addError($field, 'confirmed', $label);
        } else {
            $this->validated[$field] = $value;
        }
        return $this;
    }

    public function url(string $field, ?string $label = null): self
    {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->addError($field, 'url', $label);
        } else {
            $this->validated[$field] = $value;
        }
        return $this;
    }

    public function optional(string $field): self
    {
        if (isset($this->data[$field]) && $this->data[$field] !== '') {
            $this->validated[$field] = $this->data[$field];
        }
        return $this;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0] ?? null;
        }
        return null;
    }

    public function validated(): array
    {
        $clean = $this->validated;
        unset($clean['_csrf']);
        return $clean;
    }

    private function addError(string $field, string $rule, ?string $label, array $params = []): void
    {
        $label ??= $field;
        $locale = App::locale();
        $messages = $this->loadMessages($locale);

        $message = $messages[$rule] ?? "The {$field} field is invalid.";
        $message = str_replace(':field', $label, $message);

        foreach ($params as $key => $value) {
            $message = str_replace(':' . $key, (string) $value, $message);
        }

        $this->errors[$field][] = $message;
    }

    private function loadMessages(string $locale): array
    {
        static $cache = [];

        if (!isset($cache[$locale])) {
            $path = App::basePath("lang/{$locale}/validation.php");
            $cache[$locale] = file_exists($path) ? require $path : [];
        }

        return $cache[$locale];
    }
}
