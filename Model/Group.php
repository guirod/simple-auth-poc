<?php

class Group 
{
    private string $name;

    // public function __construct(string $name)
    // {
    //     $this->name = $name;
    // }
    public function __construct()
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    /*
     * TODO 
     */
    public static function hydrate(array $properties): Group
    {
        return new Group();
    }
}