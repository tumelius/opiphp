<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tietovisa";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset('utf8');
$sql = "
select kysymykset.id, kysymys, vastaukset.vastausteksti
from kysymykset
left join vastaukset
on vastaukset.kysymysId=kysymykset.id
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $lastId = 0;
    while($row = $result->fetch_assoc()) {
        if($lastId <> $row["id"]) {
          $lastId = $row["id"];
          $kysymykset[$lastId] = array( 'id' => $row["id"],
                                  'kysymys' => $row["kysymys"],
                                  'vastaukset' => array());
        }
        $kysymykset[$lastId]['vastaukset'][] = array('vastaus' => $row["vastausteksti"]);
    }
} else {
    echo "0 results";
}

echo "<table><tr><th>Id</th><th>Kysymys</th><th>Vastaukset</th></tr>";

foreach ($kysymykset as $kysymys){
  echo "<tr><td>{$kysymys['id']}</td><td>{$kysymys['kysymys']}</td><td>";
  foreach($kysymys['vastaukset'] as $vastaus) {
    echo $vastaus['vastaus'] . "<br>";
  }
  echo "</td></tr>";
}
echo "</table>";


$conn->close();
