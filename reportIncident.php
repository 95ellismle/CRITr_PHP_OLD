<html>

<head>
  <meta content="text/html" charset="utf-8" />
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
    $servername = "localhost:3306";
    $username = "dbBot1";
    $password = "kZ66R!E5Cl^eh";
	$dbNmae = "admin_";

    try {
       $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
       // set the PDO error mode to exception
       $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       }
    catch(PDOException $e)
       {
       echo "Connection failed: " . $e->getMessage();
       }
	
	// define variables and set to empty values
	$incidentErr = $dateErr = $timeErr = $latErr = $lonErr = $detailsErr = $photoErr = "";
	$incident = $date = $time = $lat = $lon = $details = $photo = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	  if (empty($_POST["incident"])) {
		$incidentErr = "* Please select the incident";
	  } else {
		  echo test_input($_POST["incident"]);
		$incident = test_input($_POST["incident"]);
	  }
	  //if (emtpy($_POST["incidentForm"])) {
	  //	 ;
	  //} else {
	  //	 $incident = test_input($_POST["incidentForm"]);
	  //}
	  $date = test_input($_POST["dateForm"]);
	  $time = test_input($_POST["timeForm"]);
	  $lat = test_input($_POST["latForm"]);
	  $lon = test_input($_POST["lonForm"]);
	  $details = test_input($_POST["detailsForm"]);
	  $photo = test_input($_POST["fileForm"]);
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
    <div style="width: 75%; display: inline-block; height:100%; vertical-align: 'middle'">
      <p  class="titleReportTxt">
        &nbsp; Report Incident
      </p>
      <p>
        &nbsp; (Fill out the form below)
      </p>
    </div>
  </div>

  <!-- Create the form -->
  <div class="container form">
    <div class="formRow col-12" style="border: 0;"
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
        <p class="formSubTitle" id="dateToChange">Tap to Select<p>
    </div>
    <div class="formRow col-12">
      <input type="text" class="hiddenInput" id="timePicker" onchange="setTime()">
      <p class="formTitle">Time Identified</p>
      <p class="formSubTitle" id="timeToChange">Tap to Select<p>
    </div>
    <div class="formRow col-12"
         onclick="openDetails()">
      <p class="formTitle">Extra Details</p>
      <p class="formSubTitle" id="detailsToChange">Tap to Select<p>
    </div>
    <div class="formRow col-12" onclick="openPhotoOverlay()">
      <p class="formTitle">Attach Photo</p>
      <p class="formSubTitle" id="photoToChange">Tap to Select<p>
    </div>
	<form method="post" action=""
		  style="float: right;"> 
        <select id="incidentForm" style="display: none" name="incident">
		  <option value=""></option>
          <option value="Littering">0</option>
          <option value="Loitering">1</option>
          <option value="Graffiti">2</option>
          <option value="Speeding">3</option>
          <option value="Parking">4</option>
        </select>

        <textarea id="detailsForm" style="display: none"></textarea>

        <input type="number" id="latForm" step=0.0001 style="display: none">
        <input type="number" id="lonForm" step=0.0001 style="display: none">
        <input type="date" id="dateForm" style="display: none">
        <input type="time" id="timeForm" style="display: none">
        <input type="file" id="fileForm" style="display: none">
		<div class="col-12 buttonRow" style="display: block; float: right;">
      		<button type="submit" name="submit" value="Submit"
					class="btn btn-success"
					style="display: block;">Submit</button>
    	</div>
    </form>
  </div>


  <!-- The incident type overlay -->
  <div id="grayOverlay" onclick="closeOverlay()">
  </div>
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

  <!-- The extra details part -->
  <div id="overlayPhoto" class="overlayBoxSmall">
    <button class="formBoxes takePhoto" style="height: 50%;"
         onclick="">
      <p class="formTitle" style="height: 50%;">
        <i class="material-icons">camera_alt</i> Take Photo
      </p>
    </button>
    <input type="file" class="hiddenInput takePhoto" style="height: 50%; top: 50%;"
         onclick="">
    <div class="formBoxes" style="height: 50%;">
      <p class="formTitle">
        <i class="material-icons">collections</i> Upload from device
      </p>
    </div>
  </div>


  <script>
    var coords = JSON.parse(window.localStorage.getItem("coords"));
    document.getElementById("latForm").value = coords['lat'];
    document.getElementById("lonForm").value = coords['lon'];
  </script>
  <script>
    var dateDiv = document.getElementById("datePicker");
    dateDiv.flatpickr({
      enableTime: false,
      dateFormat: "Y/m/d"
    })
    var timDiv = document.getElementById("timePicker");
    timDiv.flatpickr({
    enableTime: true,
    noCalendar: true,
    time_24hr: true,
    dateFormat: "H:i",
});

  </script>
</body>
</html>
