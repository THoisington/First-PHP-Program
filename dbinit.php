<?php
// Run this before acorn.php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "products";

$conn = new mysqli($hostname, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Create database
$query = "CREATE DATABASE $database";
$result = $conn->query($query);
if(!$result) die ("Database access failed: " . $conn->error);

$conn = new mysqli($hostname, $username, $password, $database);

// Create the animals table
$query = "CREATE TABLE animals (
    id SMALLINT NOT NULL AUTO_INCREMENT, 
    name VARCHAR(16) NOT NULL,
    age SMALLINT NOT NULL,
    lifespan SMALLINT NOT NULL,
    type VARCHAR(16) NOT NULL,
    breed VARCHAR(32) NOT NULL,
    gender VARCHAR(6) NOT NULL,
    color VARCHAR(16) NOT NULL, 
    discount SMALLINT,
    PRIMARY KEY(id),
    INDEX(type(10)),
    INDEX(breed(32)),
    INDEX(gender(1)),
    INDEX(color(10))
    )";

$result = $conn->query($query);
if(!$result) die ("Database access failed: " . $conn->error);

// Next the Item table 
$query = "CREATE TABLE items (
    id SMALLINT NOT NULL AUTO_INCREMENT,
    name VARCHAR(16) NOT NULL,
    type VARCHAR(16) NOT NULL,
    color VARCHAR(16) NOT NULL, 
    discount SMALLINT,
    INDEX(type(10)),
    INDEX(color(10)),
    PRIMARY KEY (id)
)";

$result = $conn->query($query);
if(!$result) die ("Database access failed: " . $conn->error);

// Dummy data
$query = "INSERT INTO animals(name,age,lifespan,type,breed,gender,color,discount) VALUES
    ('Crookshanks','8','18','cat','ginger','female','orange','25'),
    ('Catdog','5','100','cat','dog','male','orange','0'),
    ('Garfield','10','18','cat','tabby','male','orange','25'),
    ('Blofelds cat','4','18','cat','persian','female','white','0'),
    ('Felix','4','10','cat','cartoon','male','black','0'),
    ('Sam','8','20','dog','freelance police','male','brown','0'),
    ('Snowy','8','20','dog','wire fox terrier','male','white','0'),
    ('Amaterasu','600','1000','dog','goddess ','female','white','25'),
    ('Dogbert','5','20','dog','office dog','male','white','0'),
    ('Mr. Peabody','19','20','dog','historian','male','white','25'),
    ('King K. Rool','30','50','reptile','crocodile','male','green','25'),
    ('Geico','8','10','reptile','gecko','male','green','25'),
    ('Donatello','17','30','reptile','ninja turtle','male','green','25'),
    ('Gex','6','10','reptile','gecko','male','green','25'),
    ('Trump','70','71','reptile','no comment','male','orange','100')";

$result = $conn->query($query);
if(!$result) die ("Database access failed: " . $conn->error);

$query = "INSERT INTO items(name,type,color,discount) VALUES
    ('Tuggie','Toy','red','0'),
    ('Tennis Ball','Toy','blue','33'),
    ('Product C','Toy','black','0'),
    ('bone','Toy','red','0'),
    ('Couch Pillow','Toy','gray','0'),
    ('Dog Carrier','Carrier','red','0'),
    ('Cat Carrier','Carrier','purple','0'),
    ('Ferret Carrier','Carrier','red','25'),
    ('Hamster Carrier','Carrier','black','0'),
    ('Ant Carrier','Carrier','black','75')";

$result = $conn->query($query);
if(!$result) die ("Database access failed: " . $conn->error);

echo "proceed to acorn.php";
// $result->close();
// $conn->close();
?>