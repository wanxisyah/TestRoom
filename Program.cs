using System;
using System.Collections.Generic;
using System.Text.Json;
using System.Xml.Linq;
using System.Linq;

class Person {
    public string Name { get; set; }
    public int Age { get; set; }

    public Person(string name, int age) {
        Name = name;
        Age = age;
    }

    public virtual void Show() {
        Console.WriteLine($"Name: {Name}, Age: {Age}");
    }

    public virtual object ToJson() {
        return new {
            Name,
            Age
        };
    }

    public virtual XElement ToXML() {
        return new XElement("Person",
            new XElement("Name", Name),
            new XElement("Age", Age)
        );
    }
}

class Staff : Person {
    private double Salary;

    public Staff(string name, int age, double salary) : base(name, age) {
        Salary = salary;
    }

    public double GetSalary() => Salary;

    public override void Show() {
        Console.WriteLine($"Name: {Name}, Age: {Age}, Salary: RM {Salary:N2}");
    }

    public override object ToJson() {
        return new {
            Name,
            Age,
            Salary = Salary > 0 ? $"RM {Salary:N2}" : null
        };
    }

    public override XElement ToXML() {
        XElement staffElement = new XElement("Staff",
            new XElement("Name", Name),
            new XElement("Age", Age)
        );

        if (Salary > 0) {
            staffElement.Add(new XElement("Salary", $"RM {Salary:N2}"));
        }

        return staffElement;
    }
}

class Room {
    private List<Person> Persons = new List<Person>();

    public void AddPerson(Person person) {
        if (Persons.Count < 3) {
            Persons.Add(person);
        } else {
            Console.WriteLine("Room is full!");
        }
    }

    public void Show() {
        foreach (var person in Persons) {
            person.Show();
        }
    }

    public string GetResult(string format) {
        if (format.ToLower() == "json") {
            var personsData = Persons.Select(p => p.ToJson()).ToList();
            return JsonSerializer.Serialize(personsData, new JsonSerializerOptions { WriteIndented = true });
        } 
        else if (format.ToLower() == "xml") {
            XElement roomElement = new XElement("Room",
                Persons.Select(p => p.ToXML())
            );
            return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" + roomElement.ToString();
        } 
        else {
            return "Invalid format.";
        }
    }
}

class Program {
    static void Main() {
        Room room = new Room();
        room.AddPerson(new Person("Wan Aisyah Mahsuri", 23));
        room.AddPerson(new Staff("Danial Haq", 50, 5000.0));
        room.AddPerson(new Person("Ainul Basyeera", 48));

        room.Show();

        Console.WriteLine("\nJSON Format:\n" + room.GetResult("json"));
        Console.WriteLine("\nXML Format:\n" + room.GetResult("xml"));
    }
}
