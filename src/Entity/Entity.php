<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    /**
     * @return int
     */
    public function id(): int
    {
        return $this->getAttribute('id');
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param string $key
     * @return mixed
     */
    public function __set($key, $value)
    {
        return $this->setAttribute($key, $value);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function attribute($key): mixed
    {
        return $this->getAttribute($key);
    }
}
