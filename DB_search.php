<?php print ("<!DOCTYPE html>");?>
<html>
<head>
<meta charset="utf-8">
<title>Legomania</title>
<link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>

<!--en lista med alternativen för vad man ska söka--> 
<header>
<form name="wizard" action="DB_search.php" method="post" onsubmit="return validate()">
<p id="input"><label>
Search sets by: &nbsp;
<br>
<select name="alternativ">
<option value="SetID">SetID</option>
<option value="Setname">Setname</option>
<option value="Year">Year</option>
<option value="Categoryname">Categoryname</option>
</select>
</label></p>

<!--sökfält-->
<p id="input2">
<input type ="text" name ="search" placeholder = "Enter input!" size ="20"/>
<br/>
<input type = "submit" value = "Find lego!"/>
<input type = "reset" value = "Clear"/>
Show images: <input type = "checkbox" name = "image" value = "Show"> 
</p>
</form>

<!--
<div id="forum">
<a href="http://facebook.com/">Forum</a>
</div>
-->
</header>

<!-- Fråga hur man bäst validerar, och hur man gör för att det inte ska gå vidare! -->
<script>
   function validate(){
	
	var form = document.forms["wizard"]["search"].value;

    if (form == "Enter input!" || form == "Null" || form == "")
		{
			window.alert("Please enter input!");
			return false;
		}
		return true;
	}
</script>

<img id="background" src="background.jpg">
<div class="opacitybox">

<?php 	
if($_POST)
{
$connection = mysql_connect ("mysql.itn.liu.se", "lego", "")
                                or die ("Connection failed!");

mysql_select_db ("lego");

$value = $_POST ["alternativ"];

$search = $_POST["search"];


// Alternativ 1: Sök på SetID
if ($value == "SetID")
{
$query = "
(SELECT sets.Setname, sets.SetID, sets.Year
FROM sets
WHERE sets.SetID LIKE '%$search%')";
}
// Alternativ 2: Sök på Categoryname

if ($value == "Categoryname")
{
$query = "
(SELECT sets.Setname, sets.SetID, sets.Year
FROM sets
WHERE sets.CatID IN
                    (SELECT categories.CatID
                     FROM categories
                     WHERE categories.Categoryname LIKE '%$search%'))";
}


// Alternativ 3: Sök på Setname

if ($value == "Setname")
{
$query = "
(SELECT sets.Setname, sets.SetID, sets.Year
FROM sets
WHERE sets.setname LIKE '%$search%')";
}

// Alternativ 4: Sök på SetYear

if ($value == "Year")
{
$query = "
(SELECT sets.Setname, sets.SetID, sets.Year
FROM sets
WHERE sets.Year = '$search')";
}

$contents = mysql_query("$query");


print("<table border='border' cellpadding='6' cellspacing='3'>\n");
print("<tr>");

for($i = 0; $i < mysql_num_fields($contents); $i++)
{
                $fieldname = mysql_field_name($contents, $i);
                        print("<th>$fieldname</th>");
}
//if ($value_image == true){
 print("<th class='pictureColumn'>Image</th>");
//}
print("</tr>\n");

while($row = mysql_fetch_row($contents))
{
print("<tr>");
print("<td><a href='suppliers.php?setID=$row[1]'>$row[0]</a></td>");
for($i = 1;$i < mysql_num_fields($contents); $i++)
{
print("<td>$row[$i]</td>");
}

//if ($value_image == "Show"){
$img_dir = "http://webstaff.itn.liu.se/~stegu76/img.bricklink.com/";
$gif_url = $img_dir . 'S/' . $row[1] . '.gif';  //'$row[1]'
$jpg_url = $img_dir . 'S/' . $row[1] . '.jpg';

if(@fclose(@fopen($gif_url, "r"))){
//print("<td>" . "" . "</td>");

print("<td class='pictureColumn'><a href='$gif_url'> <img src='$gif_url' alt='gif-image' /></a></td>");

}
else if(@fclose(@fopen($jpg_url, "r"))){

print("<td class='pictureColumn'><a href='$jpg_url'><img src='$jpg_url' alt='jpg-image' /></a></td>");
}
else{
print("<td class='pictureColumn'>" . "bild saknas" . "</td>");
}


print("</tr>\n");
}
print("</table>\n");


mysql_close($connection);
}
?>
</div>
<footer>
</footer>

<script type="text/javascript" src="jquery-1.10.2.js"></script>
<script type="text/javascript" src="magic.js"></script>
</body>
</html>
