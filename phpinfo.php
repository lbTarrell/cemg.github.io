<head>
  	<title>德善Testing</title>
    <meta charset="utf-8">
    <link rel='manifest' href='manifest.json'>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	

	</head>




<br>



<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-4">
					<h2 class="heading-section">德善 Retention Report Testing</h2>
				</div>
            </div>
            <div id="wrap">
	<div class="container">
		<div class="row">
			<div class="span3 hidden-phone"></div>
			<div class="span6" id="form-login">
				<form class="form-horizontal well" method="post" name="upload_excel" enctype="multipart/form-data">
					<fieldset>
						<legend>Import CSV/Excel file</legend>
						<div class="control-group">
							
							<div class="controls">
								<input type="file" name="file" id="file" class="input-large">
							</div>


                            </div>
 <br>
						<div class="control-group">
							<div class="controls">
                            <input type="submit" name="submit" value="Submit"/>
							</div>
						</div>
                    </fieldset>

<br>
     
                </form>
                
                <?php session_start();
                $regValue=1;
                $regValue1=1;
                $regValue2=1;
                $_SESSION['start']=$regValue;
                $_SESSION['endx']=$regValue1;
                $_SESSION['endy']=$regValue2;?>
<form action="retention.php" method="get">
         <label for="start">開始時間:</label>

<input type="month" id="start" name="start"
       min="2018-03" value="2020-07">


<label for="start">End month(X):</label>

<input type="month" id="endx" name="endx"
       min="2018-03" value="2020-10">
       <label for="start">End month(Y):</label>

<input type="month" id="endy" name="endy"
       min="2018-03" value="2020-10">
       <br>
       <label for="start">醫師:</label>

   <select id="ec" name="ec">
  <option value="None">None</option>
  <option value="All 醫師">All 醫師</option>




</select>
       <br>
       <br>
<input type='submit' name='hehe' value='Process'/>
</form>
			</div>
			<div class="span3 hidden-phone"></div>
		</div>
 
	
	</div>
 
	</div>
 
			



<?php
    
require_once 'vendor/autoload.php';
require_once 'db3.php';
  
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
  
if (isset($_POST['submit'])) {
 
    $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
     
    if(isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {
     
        $arr_file = explode('.', $_FILES['file']['name']);
        $extension = end($arr_file);
     
        if('csv' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
 
        $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
        // echo $spreadsheet->getSheetCount();
        $db->query('drop table if exists NewClient_Table;');
        $db->query('CREATE TABLE NewClient_Table (
            `Joint Date` VARCHAR(255),
            `Branch (MK/CWB/TST)` VARCHAR(255),
            `Client` VARCHAR(255),
            `Member number` VARCHAR(255))');
        for ($ii=0; $ii < $spreadsheet->getSheetCount(); $ii++) {
        $sheetData = $spreadsheet->getSheet($ii)->toArray();
 
        if (!empty($sheetData)) {
            for ($i=1; $i<count($sheetData); $i++) {
     
                $col2 = $sheetData[$i][1];

                $col4 = $sheetData[$i][3];
                $col5 = $sheetData[$i][4];
  
                $col7 = $sheetData[$i][6];
                
                if (!empty($col2)){
                
                $db->query("INSERT INTO NewClient_Table(`Joint Date`,
              `Branch (MK/CWB/TST)`,
                `Client`,
                `Member number`
                ) VALUES('$col2','$col4','$col5','$col7')");}
            }
    
        }
       
    }
    $db->query("ALTER TABLE NewClient_Table ADD COLUMN `New Joint Date` varchar(50)");

    $db->query("UPDATE 
    NewClient_Table
  SET `New Joint Date` = case when `Joint Date` like '20__-%' then str_to_date(`Joint Date`,'%Y-%m-%d') 
  else str_to_date(`Joint Date`,'%m/%d/%Y') end;");
    echo "Success Upload";
    }
}
?>







<script>
  if ('serviceWorker' in navigator) {
  // Register a service worker hosted at the root of the
  // site using the default scope.
  navigator.serviceWorker.register('service-worker.js').then(function(registration) {
    console.log('Service worker registration succeeded:', registration);
  }, /*catch*/ function(error) {
    console.log('Service worker registration failed:', error);
  });
} else {
  console.log('Service workers are not supported.');
}
    </script>