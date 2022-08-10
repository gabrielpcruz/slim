<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
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
}