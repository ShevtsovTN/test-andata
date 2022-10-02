<?php

namespace App\classes;

use Exception;

class Validator

{
    private array $modelValidate;

    public function __construct(array $modelValidate)
    {
        $this->modelValidate = $modelValidate;
    }

    /**
     * Валидация входных данных
     *
     * @throws Exception
     */
    public function validated(array $data): array
    {
        if (isset($data['POST'])) {
            foreach ($data['POST'] as $index => $datum) {
                foreach ($this->modelValidate[$index] as $i => $item) {
                    $optionValidate = explode(':',$item);
                    if ($optionValidate[0] == 'min') {
                        if (strlen($datum) < $optionValidate[1]) {
                            throw new Exception(sprintf('Wrong min %s', $index), 422);
                        }
                    }
                    if ($optionValidate[0] == 'max') {
                        if (strlen($datum) > $optionValidate[1]) {
                            throw new Exception(sprintf('Wrong max %s', $index), 422);
                        }
                    }
                    if ($optionValidate[0] == 'email') {
                        if (!filter_var($datum, FILTER_VALIDATE_EMAIL)) {
                            throw new Exception('Email is not valid', 422);
                        }
                    }
                }
            }
        }
        return $data;
    }
}