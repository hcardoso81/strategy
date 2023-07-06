<?php

interface IOperation
{
    public function doOperation(int $a, int $b): int;
}

class subtractionStrategy implements IOperation
{
    public function doOperation(int $a, int $b): int
    {
        return $a - $b;
    }
}

class AdditionStrategy implements IOperation
{
    public function doOperation(int $a, int $b): int
    {
        return $a + $b;
    }
}

class MultiplicationStrategy implements IOperation
{
    public function doOperation(int $a, int $b): int
    {
        return $a * $b;
    }
}
class Calculator
{
    protected IOperation $operation;
    protected array $operationsAllowed;
    public function __construct(array $operationsAllowed, IOperation $operation = null)
    {
        $this->operationsAllowed = $operationsAllowed;
        $this->operation = $operation ?? new AdditionStrategy();
    }
    public function execute(int $a, int $b): int
    {
        return $this->operation->doOperation($a, $b);
    }

    public function setOperation(IOperation $operation): void
    {
        $this->operation = $operation;
    }

    public function __call(string $name, array $arguments): int
    {
        $className = ucfirst($name) . "Strategy";
        list($a, $b) = $arguments;
        echo $className . "\n";
        if (!in_array($name, $this->operationsAllowed))
            throw new Exception("Operation not allowed");

        $this->setOperation(new $className());
        return $this->execute($a, $b);
    }
}
$operationsAllowed = ["addition", "subtraction", "multiplication"];
$calculator = new Calculator($operationsAllowed);
$result = $calculator->execute(10, 3);
echo "$result\n";
$calculator->setOperation(new subtractionStrategy());
$result = $calculator->execute(10, 3);
echo "$result\n";
$result = $calculator->addition(6, 8);
echo "$result\n";
$result = $calculator->subtraction(10, 5);
echo "$result\n";

$result = $calculator->multiplication(10, 5);
echo "$result\n";
