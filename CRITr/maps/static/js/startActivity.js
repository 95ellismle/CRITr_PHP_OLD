function getActivityType() {
  var divs = document.getElementsByClassName("actChoice");
  for (var i=0; i<divs.length; i++) {
    if (divs[i].classList.contains("selected")) {
      return divs[i].id;
    }
  }
}

function selectActivity(currDiv) {
  if (currDiv.classList.contains("inactive")) {
    return;
  }

  var divs = document.getElementsByClassName("actChoice");
  for (var i=0; i<divs.length; i++) {
    divs[i].classList.remove("selected");
  }
  currDiv.classList.add("selected");
}

function startActivity() {
  var currActivity = getActivityType();

  switch (currActivity) {
    case "patrolAct":

      console.log("Starting Patrol!");
    default:

  }
}
