<?php

/**
 * @author Dave Stith
 * @copyright 2014
 * @goal Creating a book database using object-oriented programming with vanilla javascript form checking
 */
 
// mysqli Connection
$oMySQLi = new mysqli("localhost", "root", "password", "database_name");

if (mysqli_connect_errno()) {
    die("Database connection failure: <br />" . mysqli_connect_error());
}

abstract class cAuthor
{
    protected $fname;
    protected $lname;
    protected $btitle;
    protected $isbn;
    
    function __construct($fname, $lname, $btitle, $isbn)
    {
        global $oMySQLi;
        
        $this->SQL = $oMySQLi;
        $this->fname = $oMySQLi->real_escape_string($fname);
        $this->lname = $oMySQLi->real_escape_string($lname);
        $this->btitle = $oMySQLi->real_escape_string($btitle);
        $this->isbn = $oMySQLi->real_escape_string($isbn);
    }
    
    function addAuthor($fname, $lname)
    {
      // This needs to add the author's id into the mix
      $q = "INSERT INTO authors (last_name, first_name) VALUES ('$lname', '$fname')";
      $this->SQL->query($q);
      $id = mysqli_insert_id($this->SQL);
      
      return $id;
    }
    
    function removeAuthor($author_id)
    {
        $q = "DELETE FROM books WHERE author_id = $author_id";
        return $this->SQL->query($q);
    }
    
}

class cBooks extends cAuthor {
    
    function addBook()
    {
        
      // This needs to add the author's id into the mix
      $author_id = $this->addAuthor($this->fname, $this->lname);
      
      // Insert
      $q = "INSERT INTO books (author_id, title, isbn) VALUES ('$author_id', '$this->btitle', $this->isbn)";
      return $this->SQL->query($q);
    }
    
    function removeBook($isbn)
    {
      
      $q = "DELETE FROM books WHERE isbn = $isbn";
      return $this->SQL->query($q);
    }
    
    function editBook($field, $value, $isbn)
    {
        $q = "UPDATE books SET $field = '$value' WHERE isbn = '$isbn'";
        return $this->SQL->query($q);
    }
    
    function findBook($isbn)
    {
        
      $q = "SELECT books.isbn, books.title, authors.first_name, authors.last_name FROM books JOIN authors ON books.author_id = authors.author_id";
        
      $result = $this->SQL->query($q);
      
      // Error occurred, return given name by default 
      if(!$result || (mysqli_numrows($result) < 1))
      {
         return null;
      }
      
      // Return result array 
      $dbarray = mysqli_fetch_array($result);
      return $dbarray;
    }
    
}

// Checking for post data
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    
    // Execute database functions
    $book1 = new cBooks($_POST['fname'], $_POST['lname'], $_POST['btitle'], $_POST['isbn']);
    $book1->addBook();
    
}

?>

<!DOCTYPE HTML>
<head>
    <title>Add Book</title>
    
    <script type="text/javascript">
    function validateForm()
    {
        var fname = document.forms["bookForm"]["fname"].value;
        var lname = document.forms["bookForm"]["lname"].value;
        var btitle = document.forms["bookForm"]["btitle"].value;
        var isbn = document.forms["bookForm"]["isbn"].value;
        var submit = true;
        
        // check numeric
        var isbncheck = /^[0-9]+$/;
        
        if (fname == null || fname == "")
        {
            submit = false;
            document.getElementById('fname_e').innerHTML = "First name is required.";
        }
        else
        {
            document.getElementById('fname_e').innerHTML = "";
        }
        
        if (lname == null || lname == "")
        {
            submit = false;
            document.getElementById('lname_e').innerHTML = "Last name is required.";
        }
        else
        {
            document.getElementById('lname_e').innerHTML = "";
        }
        
        if (btitle == null || btitle == "")
        {
            submit = false;
            document.getElementById('btitle_e').innerHTML = "Book title is required.";
        }
        else
        {
            document.getElementById('btitle_e').innerHTML = "";
        }
        
        if (isbn == null || isbn == "")
        {
            submit = false;
            document.getElementById('isbn_e').innerHTML = "ISBN is required.";
        }
        else if (!isbn.match(isbncheck))
        {
            submit = false;
            document.getElementById('isbn_e').innerHTML = "ISBN must be numeric ONLY.";
        }
        
        
        
        
        if (submit == true)
        {
            return true;
        }
        else
        {
            return false;
        }
        
        
        
        
    }
    
    </script>
</head>
<body>

<h1>Add a Book to our Database</h1>
<h2>All fields required</h2>
<form name="bookForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validateForm();">
<p>Author's First Name: <input type="text" id="fname" name="fname" /><div id="fname_e"></div></p>
<p>Author's Last Name: <input type="text" id="lname" name="lname" /><div id="lname_e"></div></p>
<p>Book Title: <input type="text" id="btitle" name="btitle" /><div id="btitle_e"></div></p>
<p>Book ISBN: <input type="text" id="isbn" name="isbn" /><div id="isbn_e"></div></p>

<p><input type="submit" value="Add this book" />

</form>

</body>
</html>