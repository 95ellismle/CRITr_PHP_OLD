<!DOCTYPE html>

<html lang="en-GB">

<head>
  <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>CRITr</title>

  <!-- My styling-->
  <link rel="stylesheet" type="text/css" href="style.css">

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <!-- Material Design Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <!-- Nice Date Picker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/themes/dark.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.js"></script>



  <!-- My Scripts -->
  <script src="form.js"></script>
</head>




<body>
  <?php
	// define variables and set to empty values
	$allOK = true;
	$incidentErr = $dateErr = $timeErr = $latErr = $lonErr = $detailsErr = $photoErr = "";
	$incidentC = $dateC = $timeC = $latC = $lonC = $detailsC = $photoC = "Tap To Select";
	$incident = $date = $time = $lat = $lon = $details = $photo = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	  // Incident Select
	  if (empty($_POST["incident"])) {
		$incidentErr = "* Please select the incident";
		$allOK = false;
	  } else {
		$incidentErr = validateIncident($_POST['incident']);
		if ($incidentErr == "") {
			$incident = test_input($_POST['incident']);
			$incidentC = $incident;
		} else {
			$allOK = false;
		}
	  }
	 
		
	  // Date Select
	  if (empty($_POST["date"])) {
		$dateErr = "* Please select the date";
		$allOK = false;
	  } else {
		$dateErr = validateDate($_POST['date']);
		if ($dateErr == "") {
			$date = test_input($_POST["date"]);
			$dateC = $date;
		} else {
			$allOK = false;
		}
	  }
	
	  // Time Select
	  if (empty($_POST["time"])) {
		$timeErr = "* Please select the time";
	  } else {
		$timeErr = validateTime($_POST['time']);
		if ($timeErr == "") {
			$time = test_input($_POST["time"]);
			$timeC = $time;
		} else {
			$allOK = false;
		}
	  }
	  $lat = test_input($_POST["lat"]);
	  $lon = test_input($_POST["lon"]);
	  $x = test_input($_POST["x"]);
	  $y = test_input($_POST["y"]);

	  
	  if (!empty($_POST['details'])) { 
	  	  $details = test_input($_POST["details"]);
		  $detailsC = $details;
	  }
	
	  // Photo Upload
	  $photoCheck = new checkPhoto("fileToUpload"); 
	  if ($photoCheck->uploadOk === 0) {
	  	$photoErr = $photoCheck->err;
		$allOK = false;
	  } elseif ($photoCheck->uploadOk === 1) {
		$photoC = $photoCheck->name;
		if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $photoCheck->saveFName)) {
            //echo $_FILES["fileToUpload"]["tmp_name"];
			echo $photoCheck->saveFName;
        	echo "Sorry, there was an error uploading your file. <br><br>";
			$allOk = false;
    	}
	  }
	} else {
		$allOK = false;
	}
	if ($allOK) {
		$servername = "localhost:3306";
    	$username = "dbBot1";
    	$password = "kZ66R!E5Cl^eh";
		$dbName = "admin_";
	    try {
       		$conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
       		// set the PDO error mode to exception
       		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$dateTime = $conn->quote($date." ".$time);
			console.log($conn->prepare($incident));
			$sqlQuery = "INSERT INTO `reportData` (`incidentType`, `timestamp`, `lattitude`, `longitude`, `details`, `photoPath`, `x`, `y`)
			VALUES (".$conn->quote(incident).",
			STR_TO_DATE(".$dateTime.",'%Y/%m/%d %H:%i'),
			".$conn->quote($lat).",
			".$conn->quote($lon).",
			".$conn->quote($details).",
			".$conn->quote($photoCheck->saveFName).",
			".$conn->quote($x).",
			".$conn->quote($y).");";
			
			console.log($sqlQuery);
    		$conn->exec($sqlQuery);
			header('Location: successfulIncident.html'); 
			
		}
    	catch(PDOException $e)
       	{
       		echo "SQL Connection failed: " . $e->getMessage();
       	}
		$conn = null;
	}
	
	
	// A class that validates the photo upload 
	class checkPhoto {
		public $target_dir = "uploads/";
		public $target_file;
		public $uploadOk = 1;
		public $imageFileType;
		public $err = "";
		public $name;
		
		function __construct($inputID) {
			$this->target_file = $this->target_dir . basename($_FILES[$inputID]["name"]);
			$this->imageFileType = strtolower(pathinfo($this->target_file,PATHINFO_EXTENSION));
			$this->name = $_FILES[$inputID]["name"];

			if (!file_exists($this->target_dir)) {
 			   mkdir($this->target_dir, 0777, true);
			}
			
			if (!empty($this->name)) {
				$this->checkRealImage($inputID);
				if ($this->err == "") {
					$this->checkFileSize($inputID);
					if ($this->err == "") {
						$this->checkFileExtension($inputID);
					}
				}
			} else {
				$this->uploadOk = 2;
			}
			
			if ($this->uploadOk === 1) {
				$this->saveFName = $this->getFileName();
			}
		}
		
		function checkRealImage($inputID) {
			// Check if image file is a actual image or fake image
			if(isset($_POST["submit"])) {
				$check = getimagesize($_FILES[$inputID]["tmp_name"]);
				if($check === false) {
					$this->err = "File is not an image.";
					$this->uploadOk = 0;
				}
			}
		}
		
		function checkFileSize($inputID) {
			// Check file size
			if ($_FILES[$inputID]["size"] > 1500000) {
				$this->err = "Sorry, your file is too large.";
				$uploadOk = 0;
			}
		}
		
		function checkFileExtension() {
			if($this->imageFileType != "jpg" &&
			   $this->imageFileType != "png" &&
			   $this->imageFileType != "jpeg" &&
			   $this->imageFileType != "gif" ) {
				
				$this->err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				$uploadOk = 0;
			}
		}
		
		function getFileName() {
			$k = 0;
			while(!$result){
				if(!file_exists($this->target_dir."file_".$k.".".$this->imageFileType))
					$result = $this->target_dir."file_".$k.".".$this->imageFileType;
				$k++;
			}
			return $result;
		}
	}
	
	function validateIncident($value) {
		$incidentErr = "";
		$validVals = array("Littering", "Loitering", "Graffiti",
						   "Speeding", "Parking");
		if (! in_array($value, $validVals)) {
			$incidentErr = "* Please select valid entry from: ";
			for ($i=0; $i<count($validVals); $i++) {
				$incidentErr = $incidentErr.$validVals[$i].", ";
			}
		}
		return $incidentErr;
	}
	
	function validateTime($time, $format = "H:i") {
		$err = "";
		
		$defaultErr = "* Please select a time";
		$timeList = explode(":", $time);
		
		if (sizeof($timeList) != 2) {
			return $defaultErr;
		}
		
		if (preg_match("/^\d\d$/", $timeList[0]) == 0) {
			$err = $defaultErr;
		}
		if (preg_match("/^\d\d$/", $timeList[1]) == 0) {
			$err = $defaultErr;
		}

		if ($timeList[0] < 25 and $timeList[0] > -1) {
			if ($timeList[1] > 60 or $timeList[1] < 0) {
				$err = $defaultErr;
			}
		} else {
			$err = $defaultErr;
		}
		echo $err;
		return $err;
	}
	
	function validateDate($date, $format = 'Y/m/d')
	{
		$err = "";
		$d = DateTime::createFromFormat($format, $date);
		if ( $d && $d->format($format) === $date) {
			if ((time() - strtotime($date)) < 0) {
				echo "Too Late";
				$err = "* Please choose a date in the past!";
			}
		} else {
			$err = "* Please select a valid date with the date picker";
		}
		return $err;
	}
	
	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
  ?>
	
  <!-- Reporting incident navbar -->
  <div id="reportIncidentBar" style="display: block">
    <div class="titleReportTxt"
         style="float: left; width: 15%; display: inline-block">
      <button class="backBtn"
              onclick="backToMap()">
        <i class="material-icons">arrow_back</i>
      </button>
    </div>
    <div style="width: 75%; display: inline-block; height:100%; vertical-align: middle">
      <p  class="titleReportTxt">
        &nbsp; Report Incident
      </p>
      <p>
        &nbsp; (Fill out the form below)
      </p>
    </div>
  </div>

  <!-- Create the form -->
  <div class="form container">
    <div class="formRow col-12"
         onclick="openIncidentSelect()">
      <p class="formTitle">
        Incident Type
      </p>
      <p class="formSubTitle" id="incidentToChange">
		  Tap to Select <span class="formErr"><?php echo $incidentErr;?></span>
      <p>
    </div>
    <div class="formRow col-12">
        <input type="text" class="hiddenInput" id="datePicker" onchange="setDate()">
        <p class="formTitle">Date Identified</p>
        <p class="formSubTitle" id="dateToChange">Tap to Select <span class="formErr"><?php echo $dateErr;?></span>
		<p>
    </div>
    <div class="formRow col-12">
      <input type="text" class="hiddenInput" id="timePicker" onchange="setTime()">
      <p class="formTitle">Time Identified</p>
      <p class="formSubTitle" id="timeToChange">Tap to Select <span class="formErr"><?php echo $timeErr;?></span>
		<p>
    </div>
    <div class="formRow col-12"
         onclick="openDetails()"
		 onchange="getExtraDetails()">
      <p class="formTitle">Extra Details</p>
      <p class="formSubTitle" id="detailsToChange">Tap to Select<p>
    </div>
	  
	<!-- The hidden form that gets populated with the entered values via js -->
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"
		  style="float: right; width: 100%;"> 
        <select id="incidentForm" style="display: none" name="incident">
		  <option value="">default</option>
          <option value="Littering">0</option>
          <option value="Loitering">1</option>
          <option value="Graffiti">2</option>
          <option value="Speeding">3</option>
          <option value="Parking">4</option>
        </select>

        <input type="number" id="latForm" step=0.000001 style="display: none" name="lat">
        <input type="number" id="lonForm" step=0.000001 style="display: none" name="lon">
        <input type="number" id="xForm" step=0.000001 style="display: none" name="x">
        <input type="number" id="yForm" step=0.000001 style="display: none" name="y">
        <input type="text" id="dateForm" style="display: none" name="date">
        <input type="text" id="timeForm" style="display: none" name="time">
		<input type="text" id="detailsForm" style="display: none" name="details">
		
		<!-- The photo (visible) row -->
		<div class="formRow col-12" onclick="">
		  <!--<input type="file" id="photoUser" class="hiddenInput"
				 accept="image/*" onchange="setPhoto()" name="photoUser"/>		-->
		  <input type="file" name="fileToUpload" id="fileToUpload" class="hiddenInput"
				 onchange="setPhoto()">
		  <p class="formTitle">Add Photo </p>
		  <p class="formSubTitle" id="photoToChange">Tap to Select<span class="formErr"><?php echo $photoErr;?></span>
			</p><p>
		</div>
		<button type="submit" name="submit" value="Submit"
		    	class="btn btn-success"
				style="display: block; float: right; cursor:pointer;">Submit</button>
    </form>
  </div>


  <!-- The incident type overlay -->
  <div id="grayOverlay" onclick="closeOverlay()">
  </div>

  <!-- The incident overlay -->
  <div class="overlayBox" id="overlayIncident">
    <div class="container">
      <h2>&nbsp; Incident Type</h2>
      <div class="formBoxes"
           onclick="selectIncident('Littering')">
        <p class="formTitle">
          <i class="material-icons">delete_outline</i> Littering
        </p>
      </div>
      <div class="formBoxes"
           onclick="selectIncident('Loitering')">
        <p class="formTitle">
          <i class="material-icons">group</i> Loitering
        </p>
      </div>
      <div class="formBoxes"
           onclick="selectIncident('Graffiti')">
        <p class="formTitle">
          <i class="material-icons">photo</i> Graffiti
        </p>
      </div>
      <div class="formBoxes"
           onclick="selectIncident('Speeding')">
        <p class="formTitle">
          <i class="material-icons">directions_car</i> Speeding
        </p>
      </div>
      <div class="formBoxes"
           onclick="selectIncident('Parking')">
        <p class="formTitle">
          <i class="material-icons">local_parking</i> Parking
        </p>
      </div>
    </div>
  </div>

  <!-- The extra details part -->
  <div id="overlayDetails" class="overlayBox">
      <div style="height: 15%">
        <h2>&nbsp; Extra Details</h2>
      </div>
      <div style="height: 85%">
        <textarea class="detailsTxtBox"
                  id="detailsUser"
                  placeholder="Enter any extra details you think may be useful in here"></textarea>
     </div>
  </div>




  <script>
    var coords = JSON.parse(window.localStorage.getItem("coords"));
    document.getElementById("latForm").value = coords['lat'];
    document.getElementById("lonForm").value = coords['lon'];
    document.getElementById("xForm").value = coords['x'];
    document.getElementById("yForm").value = coords['y'];
  </script>
  <script>	  
    var dateDiv = document.getElementById("datePicker");
	var d = new Date();
    dateDiv.flatpickr({
      enableTime: false,
      dateFormat: "Y/m/d",
	  maxDate: d,
    });
	  
    var timDiv = document.getElementById("timePicker");
	const h = d.getHours();
	const m = d.getMinutes();
    timDiv.flatpickr({
		enableTime: true,
		time_24hr: true,
		noCalendar: true,
		time_24hr: true,
		dateFormat: "H:i",
		enableSeconds: false,
		minuteIncrement: 15,
		defaultHour: h,
		defaultMinute: m,
	});
	  
	const changers = ['incidentToChange', 'dateToChange', 'timeToChange', 'detailsToChange', 
					  'photoToChange'];
	const formSet = ['incidentForm', 'dateForm', 'timeForm', 'detailsForm', 'fileToUpload'];
	const changeTo = ["<?php echo $incidentC; ?>", "<?php echo $dateC; ?>",
					  "<?php echo $timeC; ?>", "<?php echo $detailsC; ?>",
					  "<?php echo $photoC; ?>"];
	const changeTo2 = ["<?php echo $incident; ?>", "<?php echo $date; ?>",
					   "<?php echo $time; ?>", "<?php echo $details; ?>",
					   "<?php echo $photo; ?>"];
	const changeToErr = ["<?php echo $incidentErr; ?>", "<?php echo $dateErr; ?>",
					  	 "<?php echo $timeErr; ?>", "<?php echo $detailsErr; ?>",
						 "<?php echo $photoErr; ?>"];
	
	for (var i=0; i<changers.length; i++) {
		document.getElementById(changers[i]).innerHTML = changeTo[i] + '<span class="formErr">'+changeToErr[i]+'</span>';
		document.getElementById(formSet[i]).value = changeTo2[i];
	}
  </script>
</body>
</html>
