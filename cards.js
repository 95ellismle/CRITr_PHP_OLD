// Will switch between the map view and the activities-map joint view
function show2Rows() {
    var cardRow = document.getElementById("cardRow");
    var mapView = document.getElementById("viewDiv");

    if (cardRow.style.height == "30%") {
        cardRow.style.height= "4%";
        mapView.style.height = "97%";
    }
    else {
        cardRow.style.height= "30%";
        mapView.style.height = "70%";
    }
}

// Will get the activities to display on the bottom panel
function getActivities() {
  activities = [
                {'name': 'Evening Patrol', 'date': 'Monday', 'time': '18:00',
                 'location': 'Fayke street, North', 'points': 150, 'numPeople': 3,
                 'spacesTaken': 1, 'details': 'This is a space for more details' +
                 ' on the activity. Maybe there have been suspicious people' +
                 ' in the area and the users need to be warned. That was just' +
                 ' an example but I\'m sure there\'ll be more useful details' +
                 ' This space is for that rather than this filler text.'},

                {'name': 'Litter Picking', 'date': 'Wednesday', 'time': '15:30',
                 'location': 'Prettend Road, West', 'points': 50, 'numPeople': 2,
                 'spacesTaken': 0, 'details': 'This is a space for more details' +
                 ' on the activity. Maybe there have been suspicious people' +
                 ' in the area and the users need to be warned. That was just' +
                 ' an example but I\'m sure there\'ll be more useful details' +
                 ' This space is for that rather than this filler text.'},
               ];
   return activities;
}

// Will create the card div to add to the row at the bottom
function createCard(dict, i) {
    var cardDiv = document.createElement("div");
    cardDiv.id = "card_" + i.toString();
    cardDiv.className = "cardDiv row w3-card";

    // Add the row break
    // var rowBreak = document.createElement("hr");

    // First column (half size)
    var colDiv1_1 = document.createElement("div");
    colDiv1_1.className = "col-xs-6";

    // The title of the activity
    var title = document.createElement("p");
    title.innerHTML = dict['name'];
    title.className = "cardTitle";
    colDiv1_1.appendChild(title);

    // Second column (quarter)
    var colDiv2_1 = document.createElement("div");
    colDiv2_1.className = "col-xs-3";

    // Add the date
    var dateStr = document.createElement("p");
    dateStr.className = "cardDate";
    dateStr.innerHTML = dict['date'].slice(0, 3);
    colDiv2_1.appendChild(dateStr);

    // Third column (quarter)
    var colDiv3_1 = document.createElement("div");
    colDiv3_1.className = "col-xs-3";

    // Create the time
    var timeStr = document.createElement("p");
    timeStr.className = "cardTime";
    timeStr.innerHTML = dict['time'];
    colDiv3_1.appendChild(timeStr);

    // Create the first column (second row)
    colDiv1_2 = document.createElement("div");
    colDiv1_2.className = "col-xs-6";

    // Create the location
    var locStr = document.createElement("p");
    locStr.className = "cardLoc";
    locStr.innerHTML = dict['location'];
    colDiv1_2.appendChild(locStr);

    // Create the second column (second row)
    colDiv2_2 = document.createElement("div");
    colDiv2_2.className = "col-xs-3";

    // Create the location
    var ptsStr = document.createElement("p");
    ptsStr.className = "cardPts";
    ptsStr.innerHTML = dict['points'] + " pt";
    colDiv2_2.appendChild(ptsStr);

    // Create the third column (second row)
    colDiv3_2 = document.createElement("span");
    colDiv3_2.className = "col-xs-3";
    colDiv3_2.style.display = "inline-block";

    for (var i=0; i<dict['spacesTaken']; i++){
        icon = document.createElement("i");
        icon.className = "material-icons";
        icon.innerHTML = "person";
        icon.style.fontSize = "17px";
        colDiv3_2.appendChild(icon);
    }
    for (var i=0; i<(dict['numPeople'] - dict['spacesTaken']); i++) {
        icon = document.createElement("i");
        icon.className = "material-icons";
        icon.innerHTML = "person_outline";
        icon.style.fontSize = "17px";
        colDiv3_2.appendChild(icon);
    }

    row1 = document.createElement("div");
    row2 = document.createElement("div");
    row1.className = "row col-xs-12";
    row1.appendChild(colDiv1_1);
    row1.appendChild(colDiv2_1);
    row1.appendChild(colDiv3_1);
    row2.className = "row col-xs-12";
    row2.appendChild(colDiv1_2);
    row2.appendChild(colDiv2_2);
    row2.appendChild(colDiv3_2);

    // cardDiv.appendChild(rowBreak);
    cardDiv.appendChild(row1);
    cardDiv.appendChild(row2);

    return cardDiv;
}
