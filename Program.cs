using System;
using System.Collections.Generic;
using System.Text.Json;
using System.Xml.Linq;

class Person {
    private string Name;
    private int Age;

    public Person(string name, int age) {
        Name = name;
        Age = age;
    }

    public string GetName() => Name;
    public void SetName(string name) => Name = name;

    public int GetAge() => Age;
    public void SetAge(int age) => Age = age;

    public void Show() {
        Console.WriteLine($"Name: {Name}, Age: {Age}");
    }

    public string ToJson() {
        return JsonSerializer.Serialize(new { Name, Age }, new JsonSerializerOptions { WriteIndented = true });
    }

    public string ToXML() {
        return new XElement("person",
            new XElement("name", Name),
            new XElement("age", Age)
        ).ToString();
    }
}

class Staff : Person {
    private double Salary;

    public Staff(string name, int age, double salary) : base(name, age) {
        Salary = salary;
    }

    public double GetSalary() => Salary;
    public void SetSalary(double salary) => Salary = salary;
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
            return JsonSerializer.Serialize(Persons, new JsonSerializerOptions { WriteIndented = true });
        } else {
            XElement roomElement = new XElement("room");
            foreach (var person in Persons) {
                roomElement.Add(XElement.Parse(person.ToXML()));
            }
            return roomElement.ToString();
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
