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

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getAge(): int {
        return $this->age;
    }

    public function setAge(int $age): void {
        $this->age = $age;
    }

    public function show(): void {
        echo "Name: {$this->name}, Age: {$this->age}\n";
    }

    public function toJson(): string {
        return json_encode(['Name' => $this->name, 'Age' => $this->age], JSON_PRETTY_PRINT);
    }

    public function toXML(): string {
        return "<Person><Name>{$this->name}</Name><Age>{$this->age}</Age></Person>";
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

    public function setSalary(float $salary): void {
        $this->salary = $salary;
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
            return json_encode(array_map(fn($p) => ['Name' => $p->getName(), 'Age' => $p->getAge()], $this->persons), JSON_PRETTY_PRINT);
        } elseif ($format === "XML") {
            $xml = new SimpleXMLElement('<Room/>');
            
            foreach ($this->persons as $p) {
                $personNode = $xml->addChild('Person');
                $personNode->addChild('Name', $p->getName());
                $personNode->addChild('Age', $p->getAge());
            }
    
            $dom = new DOMDocument("1.0");
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
