function backToMap() {
  window.location.href = "index.html";
}

function openIncidentSelect() {
  document.getElementById("grayOverlay").style.display = "block";
  document.getElementById("overlayIncident").style.display = "block";
}

function closeOverlay() {
  document.getElementById("grayOverlay").style.display = "none";
  document.getElementById("overlayDetails").style.display = "none";
  document.getElementById("overlayIncident").style.display = "none";

  getExtraDetails();
}

function openPhotoOverlay() {
  document.getElementById("grayOverlay").style.display = "block";
  document.getElementById("overlayPhoto").style.display = "block";
}

function setDate() {
   const date = document.getElementById("datePicker").value;
   document.getElementById("dateToChange").innerHTML = date;
   var form = document.getElementById("dateForm");
   form.value = date
}

function setTime() {
   const time = document.getElementById("timePicker").value;
   document.getElementById("timeToChange").innerHTML = time;
   var form = document.getElementById("timeForm");
   form.value = time
}

function selectIncident(incident) {
  document.getElementById('incidentToChange').innerHTML = incident;

  // Save it in the form
  var form = document.getElementById("incidentForm");
  form.value = incident;

  closeOverlay();
}

function getExtraDetails() {
  var user = document.getElementById("detailsUser");
  var form = document.getElementById("detailsForm");
  form.value = user.value;
}

function openDetails() {
  document.getElementById("grayOverlay").style.display = "block";
  document.getElementById("overlayDetails").style.display = "block";
}
