// Will switch between the map view and the activities-map joint view
function showCards() {
    document.getElementById("overlayBtn").style.display = "none";
    document.getElementById("overlayCards").style.display = "flex";
}

// Will show remove the topBar for the user and go back to how the first screen was
function backToFullMap() {
  showCards();
  document.getElementById("reportIncidentBar").style.display = "none";
  document.getElementById("viewDiv").style.height = "100%";
}

// Will get the activities to display on the bottom panel
function getActivities() {
  activities = [
                {'title': 'Evening Patrol',
                 'date': 'Monday',
                 'time': '18:00',
                 'location': 'Fayke street, North',
                 'points': 150,
                 'numPeople': 3,
                 'spacesTaken': 1,
                 'details': 'This is a space for more details' +
                 ' on the activity. Maybe there have been suspicious people' +
                 ' in the area and the users need to be warned. That was just' +
                 ' an example but I\'m sure there\'ll be more useful details' +
                 ' This space is for that rather than this filler text.',
                 'imageLocation': 'img/patrolSmall.jpg',
               },

                {'title': 'Litter Picking',
                 'date': 'Wednesday',
                 'time': '15:30',
                 'location': 'Prettend Road, West',
                 'points': 50,
                 'numPeople': 2,
                 'spacesTaken': 0, 'details': 'This is a space for more details' +
                 ' on the activity. Maybe there have been suspicious people' +
                 ' in the area and the users need to be warned. That was just' +
                 ' an example but I\'m sure there\'ll be more useful details' +
                 ' This space is for that rather than this filler text.',
                 'imageLocation': 'img/litterSmall.jpg',
               },
               {'title': 'Litter Picking',
                'date': 'Wednesday',
                'time': '15:30',
                'location': 'Prettend Road, West',
                'points': 50,
                'numPeople': 2,
                'spacesTaken': 0, 'details': 'This is a space for more details' +
                ' on the activity. Maybe there have been suspicious people' +
                ' in the area and the users need to be warned. That was just' +
                ' an example but I\'m sure there\'ll be more useful details' +
                ' This space is for that rather than this filler text.',
                'imageLocation': 'img/litterSmall.jpg',
              },
              {'title': 'Litter Picking',
               'date': 'Wednesday',
               'time': '15:30',
               'location': 'Prettend Road, West',
               'points': 50,
               'numPeople': 2,
               'spacesTaken': 0, 'details': 'This is a space for more details' +
               ' on the activity. Maybe there have been suspicious people' +
               ' in the area and the users need to be warned. That was just' +
               ' an example but I\'m sure there\'ll be more useful details' +
               ' This space is for that rather than this filler text.',
               'imageLocation': 'img/litterSmall.jpg',
             },
               ];
   return activities;
}

// Will create the overlay cards
function createCardOverlay(dict, i) {
  var parentDiv = document.createElement("div");
  parentDiv.id = "card_" + i.toString();
  parentDiv.className = "col-11 col-sm-7 col-md-5 col-lg-3";

  var cardDiv = document.createElement("div");
  cardDiv.className = "container card card-block";
  var cardTop = document.createElement("div");
  cardTop.className = "cardTop";

  // Create Title
  var cardLeft = document.createElement("div");
  cardLeft.className = "colLeft";
  var title = document.createElement("h5");
  title.innerHTML = dict['title'];
  cardLeft.appendChild(title);
  cardTop.appendChild(cardLeft);

  // Create num people
  var cardRight = document.createElement("div");
  cardRight.className = "colRight";
  var people = document.createElement("h5");
  for (var i=0; i<dict['spacesTaken']; i++) {
      icon = document.createElement("i");
      icon.className = "material-icons";
      icon.innerHTML = "person";
      icon.style.fontSize = "17px";
      people.appendChild(icon);
  }
  for (var i=0; i<(dict['numPeople'] - dict['spacesTaken']); i++) {
      icon = document.createElement("i");
      icon.className = "material-icons";
      icon.innerHTML = "person_outline";
      icon.style.fontSize = "17px";
      people.appendChild(icon);
  }
  cardRight.appendChild(people);
  cardTop.appendChild(cardRight);
  cardDiv.appendChild(cardTop);

  // Add the address
  var cardMiddle = document.createElement("div");
  cardMiddle.className = "cardMiddle";
  var cardLeft = document.createElement("div");
  cardLeft.className = "colLeft";
  var spanAddress = document.createElement("span");
  spanAddress.className = "cardWhere";
  spanAddress.innerHTML = dict['location'];
  cardLeft.appendChild(spanAddress);
  cardMiddle.appendChild(cardLeft);
  // Add the time
  // var cardLeft = document.createElement("div");
  // cardLeft.className = "colLeft";
  var span = document.createElement("span");
  span.className = "cardWhere";
  span.innerHTML = dict['date'] + " " + dict['time']
  cardLeft.appendChild(span);
  cardMiddle.appendChild(cardLeft);

  // Add the picture
  var cardRight = document.createElement("div");
  cardRight.className = "colRight";
  var img = document.createElement("img");
  img.src = dict['imageLocation'];
  img.className = "evtImg";
  cardRight.appendChild(img);
  cardMiddle.appendChild(cardRight);
  cardDiv.appendChild(cardMiddle);


  // Add the commit button
  var cardBottom = document.createElement("div");
  var btn = document.createElement("button");
  btn.type = "button";
  btn.className = "btn-sm btn-primary";
  btn.innerHTML = "Commit";
  cardBottom.appendChild(btn);

  var span = document.createElement("span");
  span.className = "cardPts";
  span.innerHTML = "+ " + dict['points'].toString() + " pts";
  cardBottom.appendChild(span);

  cardDiv.appendChild(cardBottom);


  parentDiv.appendChild(cardDiv);
  return parentDiv;
}







// Will create the card div to add to the row at the bottom
function createCard(dict, i) {
    var cardDiv = document.createElement("div");
    cardDiv.id = "card_" + i.toString();
    cardDiv.className = "cardDiv row";

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

    // Add the time
    var timeStr = document.createElement("p");
    timeStr.className = "cardTime";
    timeStr.innerHTML = dict['time'];
    colDiv3_1.appendChild(timeStr);

    // Add the first column (second row)
    colDiv1_2 = document.createElement("div");
    colDiv1_2.className = "col-xs-6";

    // Add the location
    var locStr = document.createElement("p");
    locStr.className = "cardLoc";
    locStr.innerHTML = dict['location'];
    colDiv1_2.appendChild(locStr);

    // Add the second column (second row)
    colDiv2_2 = document.createElement("div");
    colDiv2_2.className = "col-xs-3";

    // Add the location
    var ptsStr = document.createElement("p");
    ptsStr.className = "cardPts";
    ptsStr.innerHTML = dict['points'] + " pt";
    colDiv2_2.appendChild(ptsStr);

    // Add the third column (second row)
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
