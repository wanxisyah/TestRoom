<?php

class Person {
    private string $name;
    private int $age;

    public function __construct(string $name, int $age) {
        $this->name = $name;
        $this->age = $age;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getAge(): int {
        return $this->age;
    }

    public function show(): void {
        echo "Name: {$this->name}, Age: {$this->age}\n";
    }

    public function toJson(): array {
        return ['Name' => $this->name, 'Age' => $this->age];
    }

    public function toXML(): SimpleXMLElement {
        $recordNode = new SimpleXMLElement('<Record/>');
        $recordNode->addChild('Name', $this->name);
        $recordNode->addChild('Age', $this->age);
        return $recordNode;
    }
}

class Staff extends Person {
    private float $salary;

    public function __construct(string $name, int $age, float $salary) {
        parent::__construct($name, $age);
        $this->salary = $salary;
    }

    public function getSalary(): float {
        return $this->salary;
    }

    public function show(): void {
        echo "Name: {$this->getName()}, Age: {$this->getAge()}, Salary: RM " . number_format($this->salary, 2) . "\n";
    }

    public function toJson(): array {
        return array_merge(parent::toJson(), ['Salary' => "RM " . number_format($this->salary, 2)]);
    }

    public function toXML(): SimpleXMLElement {
        $recordNode = parent::toXML();
        $recordNode->addChild('Salary', 'RM ' . number_format($this->salary, 2));
        return $recordNode;
    }
}

class Room {
    private array $persons = [];

    public function addPerson(Person $person): void {
        if (count($this->persons) < 3) {
            $this->persons[] = $person;
        } else {
            echo "Room is full. Cannot add more persons.\n";
        }
    }

    public function show(): void {
        foreach ($this->persons as $person) {
            $person->show();
        }
    }

    public function getResult(string $format): string {
        if ($format === "JSON") {
            return json_encode(array_map(fn($p) => $p->toJson(), $this->persons), JSON_PRETTY_PRINT);
        } elseif ($format === "XML") {
            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Room/>');
            foreach ($this->persons as $p) {
                $recordXML = $p->toXML();
                $importedNode = dom_import_simplexml($xml)->ownerDocument->importNode(dom_import_simplexml($recordXML), true);
                dom_import_simplexml($xml)->appendChild($importedNode);
            }

            $dom = new DOMDocument("1.0", "UTF-8");
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($xml->asXML());

            return $dom->saveXML();
        } else {
            return "Invalid format.";
        }
    }
}


$room = new Room();
$room->addPerson(new Person("Wan Aisyah Mahsuri", 23));
$room->addPerson(new Staff("Kwon Beom Jin", 30, 5500.00));
$room->addPerson(new Person("Kim Sun Gu", 28));


$room->show();

echo "\nJSON Format:\n";
echo $room->getResult("JSON");

echo "\n\nXML Format:\n";
echo $room->getResult("XML");

?>
