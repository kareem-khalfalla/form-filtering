<?php

use Faker\Factory;
use Faker\Generator;

trait ModelData
{
    protected ?Generator $faker = null;

    protected function faker(): Generator
    {
        if (is_null($this->faker)) {
            $this->faker = Factory::create();
        }

        return $this->faker;
    }

    /**
     * Generate book data.
     *
     * @param  array $data
     * @return array
     */
    protected function bookData(array $data = []): array
    {
        return $this->mergeData([
            'category_id' => $this->faker()->numberBetween(1, 10),
            'isbn'        => $this->faker()->isbn10,
            'year'        => $this->faker()->year,
            'title'       => $this->faker()->words(asText: true),
            'description' => $this->faker()->words(7, true),
            'price'       => $this->faker()->randomFloat(2,1,999)
        ], $data);
    }

    /**
     * mergeData
     *
     * @param  array $data
     * @param  array $userData
     * @return array
     */
    protected function mergeData(array $data, array $userData = []): array
    {
        if (!empty($userData)) {
            $data = array_merge($data, $userData);
        }
        return $data;
    }
}
