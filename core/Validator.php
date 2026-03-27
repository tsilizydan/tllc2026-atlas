<?php
/**
 * TSILIZY CORE - Input Validator
 * Form validation utilities
 */

class Validator
{
    private array $errors = [];
    private array $data = [];

    /**
     * Create a new validator instance
     */
    public function __construct(array $data = [])
    {
        $this->data = $data ?: $_POST;
    }

    /**
     * Static factory method
     */
    public static function make(array $data = []): self
    {
        return new self($data);
    }

    /**
     * Validate required field
     */
    public function required(string $field, string $label = null): self
    {
        $label = $label ?? ucfirst(str_replace('_', ' ', $field));
        
        $value = $this->data[$field] ?? '';
        if (!isset($this->data[$field]) || trim((string)$value) === '') {
            $this->errors[$field] = "{$label} is required.";
        }
        
        return $this;
    }

    /**
     * Validate email format
     */
    public function email(string $field, string $label = null): self
    {
        $label = $label ?? ucfirst(str_replace('_', ' ', $field));
        
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "{$label} must be a valid email address.";
        }
        
        return $this;
    }

    /**
     * Validate minimum length
     */
    public function minLength(string $field, int $min, string $label = null): self
    {
        $label = $label ?? ucfirst(str_replace('_', ' ', $field));
        
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $min) {
            $this->errors[$field] = "{$label} must be at least {$min} characters.";
        }
        
        return $this;
    }

    /**
     * Validate maximum length
     */
    public function maxLength(string $field, int $max, string $label = null): self
    {
        $label = $label ?? ucfirst(str_replace('_', ' ', $field));
        
        if (!empty($this->data[$field]) && strlen($this->data[$field]) > $max) {
            $this->errors[$field] = "{$label} must not exceed {$max} characters.";
        }
        
        return $this;
    }

    /**
     * Validate numeric value
     */
    public function numeric(string $field, string $label = null): self
    {
        $label = $label ?? ucfirst(str_replace('_', ' ', $field));
        
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = "{$label} must be a number.";
        }
        
        return $this;
    }

    /**
     * Validate integer value
     */
    public function integer(string $field, string $label = null): self
    {
        $label = $label ?? ucfirst(str_replace('_', ' ', $field));
        
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_INT)) {
            $this->errors[$field] = "{$label} must be an integer.";
        }
        
        return $this;
    }

    /**
     * Validate minimum value
     */
    public function min(string $field, float $min, string $label = null): self
    {
        $label = $label ?? ucfirst(str_replace('_', ' ', $field));
        
        if (!empty($this->data[$field]) && (float)$this->data[$field] < $min) {
            $this->errors[$field] = "{$label} must be at least {$min}.";
        }
        
        return $this;
    }

    /**
     * Validate maximum value
     */
    public function max(string $field, float $max, string $label = null): self
    {
        $label = $label ?? ucfirst(str_replace('_', ' ', $field));
        
        if (!empty($this->data[$field]) && (float)$this->data[$field] > $max) {
            $this->errors[$field] = "{$label} must not exceed {$max}.";
        }
        
        return $this;
    }

    /**
     * Validate date format
     */
    public function date(string $field, string $format = 'Y-m-d', string $label = null): self
    {
        $label = $label ?? ucfirst(str_replace('_', ' ', $field));
        
        if (!empty($this->data[$field])) {
            $date = DateTime::createFromFormat($format, $this->data[$field]);
            if (!$date || $date->format($format) !== $this->data[$field]) {
                $this->errors[$field] = "{$label} must be a valid date.";
            }
        }
        
        return $this;
    }

    /**
     * Validate field matches another field
     */
    public function matches(string $field, string $otherField, string $label = null): self
    {
        $label = $label ?? ucfirst(str_replace('_', ' ', $field));
        
        if (($this->data[$field] ?? '') !== ($this->data[$otherField] ?? '')) {
            $this->errors[$field] = "{$label} does not match.";
        }
        
        return $this;
    }

    /**
     * Validate against a list of allowed values
     */
    public function in(string $field, array $allowed, string $label = null): self
    {
        $label = $label ?? ucfirst(str_replace('_', ' ', $field));
        
        if (!empty($this->data[$field]) && !in_array($this->data[$field], $allowed)) {
            $this->errors[$field] = "{$label} contains an invalid value.";
        }
        
        return $this;
    }

    /**
     * Validate URL format
     */
    public function url(string $field, string $label = null): self
    {
        $label = $label ?? ucfirst(str_replace('_', ' ', $field));
        
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
            $this->errors[$field] = "{$label} must be a valid URL.";
        }
        
        return $this;
    }

    /**
     * Validate phone number (basic)
     */
    public function phone(string $field, string $label = null): self
    {
        $label = $label ?? ucfirst(str_replace('_', ' ', $field));
        
        if (!empty($this->data[$field])) {
            $phone = preg_replace('/[^0-9+]/', '', $this->data[$field]);
            if (strlen($phone) < 10 || strlen($phone) > 15) {
                $this->errors[$field] = "{$label} must be a valid phone number.";
            }
        }
        
        return $this;
    }

    /**
     * Validate unique value in database
     */
    public function unique(string $field, string $table, string $column = null, int $exceptId = null, string $label = null): self
    {
        $label = $label ?? ucfirst(str_replace('_', ' ', $field));
        $column = $column ?? $field;
        
        if (!empty($this->data[$field])) {
            $where = "{$column} = ?";
            $params = [$this->data[$field]];
            
            if ($exceptId !== null) {
                $where .= " AND id != ?";
                $params[] = $exceptId;
            }
            
            if (Database::exists($table, $where, $params)) {
                $this->errors[$field] = "{$label} already exists.";
            }
        }
        
        return $this;
    }

    /**
     * Custom validation rule
     */
    public function custom(string $field, callable $callback, string $message): self
    {
        if (!$callback($this->data[$field] ?? null, $this->data)) {
            $this->errors[$field] = $message;
        }
        
        return $this;
    }

    /**
     * Check if validation passed
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }

    /**
     * Check if validation failed
     */
    public function fails(): bool
    {
        return !$this->passes();
    }

    /**
     * Get all validation errors
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Get first error for a field
     */
    public function error(string $field): ?string
    {
        return $this->errors[$field] ?? null;
    }

    /**
     * Get validated data
     */
    public function validated(): array
    {
        $validated = [];
        foreach (array_keys($this->data) as $field) {
            if (!isset($this->errors[$field])) {
                $validated[$field] = $this->data[$field];
            }
        }
        return $validated;
    }

    /**
     * Add custom error
     */
    public function addError(string $field, string $message): self
    {
        $this->errors[$field] = $message;
        return $this;
    }
}
