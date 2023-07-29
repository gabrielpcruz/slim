<?php

namespace App\Service\Utils;

use DomainException;
use stdClass;

class Dynamic extends stdClass
{
    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (!property_exists($this, $name)) {
            $class = get_class($this);

            throw new DomainException("Property {$name} not exists on {$class}");
        }

        return $this->{$name};
    }

    /**
     * @param $object
     * @return Dynamic
     */
    public static function fromStdObject($object): Dynamic
    {
        $dinamic = new Dynamic();

        foreach (get_object_vars($object) as $propety => $value) {
            $dinamic->{$propety} = $value;
        }

        return $dinamic;
    }

    /**
     * @param $json
     * @return Dynamic
     */
    public static function fromJson($json): Dynamic
    {
        $content = json_decode($json);

        if (!($content instanceof stdClass)) {
            throw new DomainException("Invalid Json.");
        }

        return Dynamic::fromStdObject($content);
    }

    /**
     * @param array $array
     * @return Dynamic
     */
    public static function fromArray(array $array): Dynamic
    {
        $dinamic = new Dynamic();

        foreach ($array as $property => $value) {
            if (is_array($value)) {
                $dinamic->{$property} = Dynamic::fromArray($value);
            } else {
                $dinamic->{$property} = $value;
            }
        }

        return $dinamic;
    }

    /**
     * @param array $attributes
     * @return Dynamic
     */
    public function whithout(array $attributes): Dynamic
    {
        $dinamic = new Dynamic();

        foreach (get_object_vars($this) as $property => $value) {
            if (in_array($property, $attributes)) {
                continue;
            }

            $dinamic->{$property} = $value;
        }

        return $dinamic;
    }

    /**
     * @param array $attributes
     * @return Dynamic
     */
    public function only(array $attributes): Dynamic
    {
        $dinamic = new Dynamic();

        foreach (get_object_vars($this) as $property => $value) {
            if (!in_array($property, $attributes)) {
                continue;
            }

            $dinamic->{$property} = $value;
        }

        return $dinamic;
    }

    /**
     * @param array $properties
     * @return bool
     */
    public function propertiesExist(array $properties): bool
    {
        foreach ($properties as $property) {
            if (!property_exists($this, $property)) {
                return false;
            }
        }

        return true;
    }
}
