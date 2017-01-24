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
include("functions_external.php");
include("connect.php");
include("selections.php");

$message = "";

if(isset($_GET["name"]) && isset($_GET["movie"]) && isset($_GET["action"]) && $_GET["action"]="vote")
{
    
    // Detects multiple params with the same name (HTTP Parameter Pollution)            
    $hpp_error="";

    $hpp_error = hpp_check_1(urldecode($_SERVER["QUERY_STRING"]));

    if($hpp_error && $_COOKIE["security_level"] == 2)
    {

        $message = $hpp_error;

    }
        
    else
    {

        $movie = $_REQUEST["movie"];

        $sql = "SELECT * FROM movies WHERE id = '" . sqli_check_2($movie) . "'";

        $recordset = mysql_query($sql, $link);

        if(!$recordset)
        {

            die("Error: " . mysql_error());  

        }

        if(mysql_num_rows($recordset) != 0)
        {    

            while($row = mysql_fetch_array($recordset))         
            {

                // print_r($row);

                $message = "<p>Your favorite movie is: <b>" . $row["title"] . "</b></p>"; 
                $message.= "<p>Thank you for submitting your vote!</p>";                   

            }

        }

        else
        {

             $message = "<font color=\"red\">Something went wrong...</font>";       

        }
        
        mysql_close($link);

    }

}

else
{

    header("Location: xss_href-1.php");

    exit;
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

    <h1>XSS - Reflected (HREF)</h1>

    <?php echo $message ?>

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