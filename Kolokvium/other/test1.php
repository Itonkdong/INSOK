<?php

class MyClass
{
    private ?string $myString;

    /**
     * @param ?string $myString
     */
    public function __construct(?string $myString)
    {
        $this->myString = $myString;
    }

    public function getValue(string $valueName):mixed
    {
        return $this->$valueName;
    }

    public function __get(string $name)
    {
        if (property_exists($this, $name)){
            return $this->$name;
        }

        echo "No variable $name";
        return null;
    }


}

enum MyEnum: string
{
    case CASE1 = "case1";
    case CASE2 = "case2";
}


$testString = "viktor 12";

$myArray = ["viktor", "matej"];

function myFunc($word): int
{
    return strlen($word);
}


print_r(array_map('myFunc', $myArray));

$value = MyEnum::CASE1;

print $value->value;

