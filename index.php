<!DOCTYPE html>
<html>
<head>
<title>IdeaVault</title>
<meta content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=no" name="viewport">
<?php
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Fatal Error");
?>
<link rel="stylesheet" type="text/css" href="style.css">

<script>

//erases the default field hint text on click
//disables itself after the first click
function clearField(fieldId){
document.getElementById(fieldId).value = "";
document.getElementById(fieldId).innerHTML = ""; 
document.getElementById(fieldId).setAttribute('OnClick',''); 
}

//checks whether the user has changed the default text and length > 20 char
function validateForm(){
var textArea = document.getElementById('ideaTextArea').value;
var nameField = document.getElementById('nameField').value;

if(textArea.length<20){
document.getElementById('tagline').innerHTML = '<span style="color:red;">Minimum idea length is 20 characters. Length: ' + textArea.length + '</span>';
}
else if(nameField == 'Enter your name here' || textArea == 'Type your idea here'){
document.getElementById('tagline').innerHTML = '<span style="color:red;">Invalid entry. NameField:'+nameField+'</span>';
}
else{
document.getElementById('tagline').innerHTML = 'Okay.';
console.log("Submitted");
document.getElementById("ideaForm").submit();
}
}
//end validate form function

</script>
</head>
<body>

<div id="banner">
<a href="../">Back to other projects</a>
</div><!-- end banner -->

<div id="container">
<h1 style="margin-bottom:5px;">IdeaVault™</h1>

<?php

//Count the number of rows in ideas table

$query = "SELECT COUNT(idea) AS idea_count FROM ideas";
$result = $conn->query($query);
if(!$result) die ("Fatal Error");
$rows = $result->num_rows;
for ($j = 0; $j < $rows; ++$j)
{
$result->data_seek($j);
echo '<h5 id="tagline">';
echo htmlspecialchars($result->fetch_assoc()['idea_count']) . ' ideas so far!';
echo '</h5>';
}
?>

<img src="pig.png" width="50px">
<p>Store your great ideas and get a random idea for inspiration! Share your great ideas with others!</p>

<h2>Make a deposit</h2>
<form id="ideaForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<textarea id="ideaTextArea" rows="4" cols="50" name="idea" onClick="clearField('ideaTextArea')">Type your idea here</textarea><br>
Name: <input type="text" id="nameField" name="name" onClick="clearField('nameField')" value="Enter your name here">
<br>
<input type="button" onClick="validateForm()" value="Deposit">
</form>
<br>
<h2>Make a withdrawal</h2>
<div style="text-align:left;">
<?php 

function get_post($conn, $var)
{
return $conn->real_escape_string($_POST[$var]);
}

if(!empty($_POST['idea']) && !empty($_POST['name'])){
$idea = get_post($conn, 'idea');
$name = get_post($conn, 'name');
$query = "INSERT INTO ideas (idea, name) VALUES (" ."'". $idea ."'".", ". "'".$name."'". ")";
$result = $conn->query($query);
if(!$result) echo "INSERT failed<br><br>";
}


$query = "SELECT idea,name FROM ideas ORDER BY RAND() LIMIT 1";
$result = $conn->query($query);
if(!$result) die ("Fatal Error");
$rows = $result->num_rows;
for ($j = 0; $j < $rows; ++$j)
{
$result->data_seek($j);
echo '"' . htmlspecialchars($result->fetch_assoc()['idea']) . '"' . '<br>';
$result->data_seek($j);
echo '<div style="text-align:right;">-' . htmlspecialchars($result->fetch_assoc()['name']) .'</div>';
}
$result->close();
$conn->close();
?>
</div>
<br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<input type="submit" name="refresh" value="Refresh">
</form>
</div>

</body>
</html>