
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: center;
  padding: 8px;
}



<?php
 ignore_user_abort(false);
// Initialize the session.
// If you are using session_name("something"), don't forget it now!
session_start();

// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
?>




tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
  <head>
  
  	<title>德善Testing</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	

	</head>
  <div class="row justify-content-center">
				<div class="col-md-6 text-center mb-4">
          <br><br>
					<h1 class="heading-section">德善 Retention Report Testing</h1>
				</div>
            </div>

              <?php 
              session_start();
              $regValue = $_GET['start'];
              $regValue1 = $_GET['endx'];
              $regValue2 = $_GET['endy'];
              $ec = $_GET['ec'];
              echo "<h4>開始時間 : ","$regValue",'</h4>';
              echo "<h4>End month(X) : ","$regValue1",'</h4>';
              echo "<h4>End month(Y) : ","$regValue2",'</h4>';
              echo "<h4>醫師 : ","$ec",'</h4>';

      
              $date1 = $regValue.'-01';
              $date2 = $regValue1.'-01';

              $ts1 = strtotime($date1);
              $ts2 = strtotime($date2);

              $year1 = date('Y', $ts1);
              $year2 = date('Y', $ts2);

              $month1 = date('m', $ts1);
              $month2 = date('m', $ts2);

              $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
              echo $diff;
              
  
              ?>
<br>
<form class="form-horizontal well" method="post" action="phpinfo.php" >

<input type="submit" name="submit" value="Go Back"/>

</form>

<button id="btnExport" onclick="exportTableToCSV('retention.csv')">Export to excel</button>
<br><br>

<?php

$con = mysqli_connect("localhost","root","","susan");


  $query1 = "call retention_report_updated('$regValue','$regValue1','$regValue2','$ec')";


// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

if(isset($_GET['hehe']))
{
    $result=mysqli_query($con, $query1);





    if(mysqli_num_rows($result)!=0)
    {       
      $test1 = mysqli_fetch_fields($result);
      $length=count($test1);


     if($ec!='None'){
      echo "<br><h3> Retention Rate - By Doctor (",$regValue," to ",$regValue2,") – Online + Offline</h3>";

 
        $query2 = "call retention_report_updated('$regValue','$regValue1','$regValue2','Any')";
        $con = mysqli_connect("localhost","root","","susan");
        $result1=mysqli_query($con, $query2);
        $test1 = mysqli_fetch_fields($result1);
        $length=count($test1);

        echo "<table id='table' border='1'>";
        echo "<tr>";
        echo "<th></th>";
        for ($iii=0; $iii < $length; $iii++) {
          
        
          echo "<th>";
          echo $test1[$iii]->name;
           echo "</th>";
  
        }
     
         echo "</tr>";
        
         while($test = mysqli_fetch_array($result1))
         {         
          
          echo "<tr>";
          echo "<td>";
       
          echo "</td>";
           for ($i=0; $i < $length; $i++) {


        
             echo "<td>";
             echo $test[$i];
             echo "</td>";
         
     
           }  echo "</tr>";
        
         } 
         echo "</table>";
       
 


      }
      else{

      echo "<br><h3> Retention Rate (",$regValue," to ",$regValue2,") – Total Lead</h3>";

     echo "<table id='table' border='1'>";
     echo "<tr>";
      for ($i=0; $i < $length; $i++) {
        echo "<th>";
        echo $test1[$i]->name;
         echo "</th>";

      }
     

      echo "</tr>";
        while($test = mysqli_fetch_array($result))
        {               
          echo "<tr>";
          for ($i=0; $i < $length; $i++) {
            echo "<td>";
            echo $test[$i];
            echo "</td>";
        
    
          }
          echo "</tr>";
      
        }
        echo "</table>";

 
     

    }
  }
}

?>
<br>

					</div>
				</div>
			</div>
		</div>



<br><br>
<script>  
  //user-defined function to download CSV file  
  function downloadCSV(csv, filename) {  
      var csvFile;  
      var downloadLink;  
       
      //define the file type to text/csv  
      csvFile = new Blob(["\ufeff"+csv], {type: 'text/csv;charset=utf-18;'});  
      downloadLink = document.createElement("a");  
      downloadLink.download = filename;  
      downloadLink.href = window.URL.createObjectURL(csvFile);  
      downloadLink.style.display = "none";  
    
      document.body.appendChild(downloadLink);  
      downloadLink.click();  
  }  
    
  //user-defined function to export the data to CSV file format  
  function exportTableToCSV(filename) {  
     //declare a JavaScript variable of array type  
     var csv = [];  
     var rows = document.querySelectorAll("table tr");  
     
     //merge the whole data in tabular form   
     for(var i=0; i<rows.length; i++) {  
      var row = [], cols = rows[i].querySelectorAll("td, th");  
      for( var j=0; j<cols.length; j++)  
         row.push(cols[j].innerText);  
      csv.push(row.join(","));  
     }   
     //call the function to download the CSV file  
     downloadCSV(csv.join("\n"), filename);  
  }  
  </script>  
