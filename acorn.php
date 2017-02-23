<?php
    //require_once 'dbinit.php';

    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "products";
    
    $conn = new mysqli($hostname,$username,$password,$database);
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    $animal = new Animal;
    $item = new Item;

    class Product 
    {
        public $discount;
        public $color;
        public $type;

    }

    class Animal extends Product 
    {
        public $name, $lifespan, $age, $breed, $gender;
    }

    class Item extends Product{}


    function addToCatalog($product,$conn)
    {
        if($product instanceof Animal){
            $discount = NULL;
            if($product->age >= ($product->lifespan)/2){
                $discount = 25;
            }

            $query    = "INSERT INTO animals (name,age,lifespan,type,breed,gender,color,discount) VALUES " .
            "('$product->name', '$product->age', '$product->lifespan', '$product->type', '$product->breed', '$product->gender', '$product->color', '$discount')";
            $result   = $conn->query($query);
        } else if ($product instanceof Item){
            $discount = NULL;
            $query    = "INSERT INTO items(name,type,color,discount) VALUES " .
            "('$product->name','$product->type', '$product->color', '$discount')";
            $result   = $conn->query($query);
        }
        
        if (!$result) echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";
    }

    // Adds to the Query which item was selected in the drop down menu 
    function sortProducts($query,$conn)
    {
        $select; 
        if(isset($_POST['animal'])){
            $select = $_POST['animal'];
        }
        else if(isset($_POST['item'])){
            $select = $_POST['item'];
        }

        $query = $query . "$select";
        listProducts($conn,$query);
    }

    function filterProducts($query,$conn)
    {
            $first = TRUE;
            $allEmpty = TRUE;
            foreach($_POST as $key => $value){
                if($value != "" && $key != 'btn'){
                    if($first == TRUE){
                        $query = $query . "$key='$value' ";
                        $first = FALSE;
                        $allEmpty = FALSE;
                    } else{
                        $query = $query . "AND $key='$value' ";
                    }
                    
                }
            }
             if($allEmpty == TRUE){
                 echo "You need to enter at least one filter criteria";
             }
             else{
                listProducts($conn,$query);
             }  
    }

    function listProducts($conn,$query)
    {
        $result = $conn->query($query);
        if (!$result) die ("Database access failed: " . $conn->error);

        // Prints the column titles of the table no matter the query. This was one of the hardest parts!
        $rows = $result->num_rows;
        echo "<table><thead><tr>";  
        $finfo = mysqli_fetch_fields($result);
        foreach($finfo as $val){
            echo "<th>$val->name</th>";
        }
        echo "</tr></thead>";

        // Prints each row in the table
        for($j = 0; $j < $rows; ++$j){
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_NUM);
            echo "<tr>";
            for($k = 0; $k < count($row); ++$k){  
                echo "<td>$row[$k]</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    // Just there to make sure there isn't a NOT NULL field left empty upon insertion. Also adds the data into the Product object
    function validate($product,$conn){
        $fieldEmpty = 'false';
        foreach($_POST as $key => $value){
            if($value == ""){
                $fieldEmpty = 'true';
                break;
            }
            else if($key == 'btn'){
                break;
            }
            else{
                $product->$key = $value;
            }
        }
        if($fieldEmpty == 'true'){
            echo "You are missing one of the required fields";
        }
        else{
            addToCatalog($product,$conn);
        }
    }

// If the interface had been a consideration for this project I wouldn't have printed the html out this way and probably would have stuck the
// methods in a different file. '
echo <<<_END
        <form action="acorn.php" method="post"><pre>
        Name <input type="text" name="name">
        Age <input type="text" name="age">
        Animal Type <input type="text" name="type">
        Lifespan <input type="text" name="lifespan">
        Breed <input type="text" name="breed">
        Gender <input type="text" name="gender">
        Color <input type="text" name="color">
        <input type="hidden" name="btn" value="addAnimal"/>
        <input type="submit" value="Add Animal">
    </pre></form>
_END;

echo <<<_END
        <form action="acorn.php" method="post"><pre>
        Name <input type="text" name="name">
        Item Type <input type="text" name="type">
        Color <input type="text" name="color">
        <input type="hidden" name="btn" value="addItem"/>
        <input type="submit" value="Add Item">
    </pre></form>
_END;

echo <<<_END
        <form action="acorn.php" method="post">
        <input type="hidden" name="btn" value="listAnimals"/>
        <input type="submit" value="List Animals">
        </form>
_END;

echo <<<_END
        <form action="acorn.php" method="post">
        <input type="hidden" name="btn" value="listItems"/>
        <input type="submit" value="List Items">
        </form>
_END;

echo <<<_END
        
        <form action="acorn.php" method="post">
        <select id="animal" name="animal">                      
        <option value="0">--Sort Animal By--</option>
        <option value="name">Name</option>
        <option value="age">Age</option>
        <option value="type">Type</option>
        <option value="breed">Breed</option>
        <option value="color">Color</option>
        </select>
        <input type="hidden" name="btn" value="sortAnimals"/>
        <input type="submit" value="Sort Animals">
        </form>
_END;

echo <<<_END
        
        <form action="acorn.php" method="post">
        <select id="animal" name="animal">                      
        <option value="0">--Sort Item By--</option>
        <option value="name">Name</option>
        <option value="type">Type</option>
        <option value="color">Color</option>
        </select>
        <input type="hidden" name="btn" value="sortItems"/>
        <input type="submit" value="Sort Items">
        </form>
_END;

echo <<<_END
        <p> Filter by categories </p> 
        <form action="acorn.php" method="post"><pre>
        Age <input type="text" name="age">
        Animal Type <input type="text" name="type">
        Breed <input type="text" name="breed">
        Gender <input type="text" name="gender">
        Color <input type="text" name="color">
        <input type="hidden" name="btn" value="filterAnimals"/>
        <input type="submit" value="Filter Animals">
    </pre></form>
_END;

echo <<<_END
        <p> Filter by categories </p> 
        <form action="acorn.php" method="post"><pre>
        Item Type <input type="text" name="type">
        Color <input type="text" name="color">
        <input type="hidden" name="btn" value="filterItems"/>
        <input type="submit" value="Filter Items">
    </pre></form>
_END;

    //My book I was learning from had me accomplish this by verrifying each field with ISSET.
    //I didn't like all that code and it didn't apply to each thing we are doing with the data. 
    // So, I opted for hidden fields instead. 
    if(isset($_POST['btn'])){
    switch (get_post($conn, 'btn')) {
    case "addAnimal":
        validate($animal,$conn);
        break;
    case "addItem":
        validate($item,$conn);
        break;
    case "listAnimals":
        $query = "SELECT * FROM animals";
        listProducts($conn,$query);
        break;
    case "listItems":
        $query = "SELECT * FROM items";
        listProducts($conn,$query);
        break;
    case "sortAnimals":
        $query = "SELECT * FROM animals ORDER BY ";
        sortProducts($query,$conn);
        break;
    case "sortItems":
        $query = "SELECT * FROM items ORDER BY ";
        sortProducts($query,$conn);
        break;
    case "filterAnimals":
        $query = "SELECT * FROM animals WHERE ";
        filterProducts($query,$conn);
        break;
    case "filterItems":
        $query = "SELECT * FROM items WHERE ";
        filterProducts($query,$conn);
        break;
    }
    }

    // Not sure why these throw errors...
    // $result->close();
    // $conn->close();
  
    function get_post($conn, $var)
    {
        return $conn->real_escape_string($_POST[$var]);
    }
    //TODO: have at least 5 of every object type (Dogs, Cats, Reptiles, Toys, Carriers) - offer at least 5 items with a discount
?>