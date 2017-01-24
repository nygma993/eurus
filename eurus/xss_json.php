<?php

/*

bWAPP, or a buggy web application, is a free and open source deliberately insecure web application.
It helps security enthusiasts, developers and students to discover and to prevent web vulnerabilities.
bWAPP covers all major known web vulnerabilities, including all risks from the OWASP Top 10 project!
It is for security-testing and educational purposes only.

Enjoy!

Malik Mesellem
Twitter: @MME_IT

bWAPP is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (http://creativecommons.org/licenses/by-nc-nd/4.0/). Copyright © 2014 MME BVBA. All rights reserved.

*/

include("security.php");
include("security_level_check.php");
include("selections.php");
include("functions_external.php");

if(isset($_GET["title"]) and $_GET["title"])
{

    // Creates the movie table
    $movies = array("CAPTAIN AMERICA", "IRON MAN", "SPIDER-MAN", "THE INCREDIBLE HULK", "THE WOLVERINE", "THOR", "X-MEN");

    if($_COOKIE["security_level"] != "1" && $_COOKIE["security_level"] != "2")
    {

        // Retrieves the movie title
        $title = $_GET["title"];

    }

    else
    {

        $title = xss_check_3($_GET["title"]);

    }

    // Generates the output depending on the movie title received from the client
    if(in_array(strtoupper($title), $movies))
        $string = '{"movies":[{"response":"Yes! We have that movie..."}]}';
    else
        $string = '{"movies":[{"response":"' . $title . '??? Sorry, we don&#039;t have that movie :("}]}';

}

else
{

	$string = '{"movies":[{"response":"HINT: our master really loves Marvel movies :)"}]}';
}

?>
<!DOCTYPE html>
<html>
 
<head>
   
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<!--<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Architects+Daughter">-->
<link rel="stylesheet" type="text/css" href="stylesheets/stylesheet.css" media="screen" />
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />

<!--<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>-->
<script src="js/html5.js"></script>

<title>EURUS - XSS</title>

</head>

<body onload="process()">
    
<header>

<img src="images/eurus.png">

</header>   

<div id="menu">
      
    <table>
        
        <tr>
            
            <td><a href="portal.php">Bugs</a></td>

            <td><a href="security_level_set.php">Set Security Level</a></td>

            <td><a href="logout.php" onclick="return confirm('Are you sure you want to leave?');">Logout</a></td>
            <td><font color="red">Welcome <?php if(isset($_SESSION["login"])){echo ucwords($_SESSION["login"]);}?></font></td>
            
        </tr>
        
    </table>   
   
</div> 

<div id="main">

    <h1>XSS - Reflected (JSON)</h1>

    <form action="<?php echo($_SERVER["SCRIPT_NAME"]); ?>" method="GET">

    <p>

    <label for="title">Search for a movie:</label>
    <input type="text" id="title" name="title">    

    <button type="submit" name="action" value="search">Search</button>

    </p>

    </form>

    <div id="result"></div>

    <script>

        var JSONResponseString = '<?php echo $string ?>';

        // var JSONResponse = eval ("(" + JSONResponseString + ")");
        var JSONResponse = JSON.parse(JSONResponseString);

        document.getElementById("result").innerHTML=JSONResponse.movies[0].response;

    </script>

</div>
 


<div id="security_level">

    <form action="<?php echo($_SERVER["SCRIPT_NAME"]);?>" method="POST">

        <label>Set your security level:</label><br />

        <select name="security_level">

            <option value="0">low</option>
            <option value="1">medium</option>
            <option value="2">high</option> 

        </select>

        <button type="submit" name="form_security_level" value="submit">Set</button>
        <font size="4">Current: <b><?php echo $security_level?></b></font>

    </form>   

</div>
 
<div id="bug">

    <form action="<?php echo($_SERVER["SCRIPT_NAME"]);?>" method="POST">

        <label>Choose your bug:</label><br />

        <select name="bug">

<?php

// Lists the options from the array 'bugs' (bugs.txt)
foreach ($bugs as $key => $value)
{

   $bug = explode(",", trim($value));

   // Debugging
   // echo "key: " . $key;
   // echo " value: " . $bug[0];
   // echo " filename: " . $bug[1] . "<br />";

   echo "<option value='$key'>$bug[0]</option>";

}

?>


        </select>

        <button type="submit" name="form_bug" value="submit">Hack</button>

    </form>

</div>

</body>

</html>