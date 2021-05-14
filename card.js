// Change background image when cell is selected (X) /de-selected (blank)

function clickCell (td) {
	var ximage = 'url(x-symbol.png)';
	var xnone = 'none';
	var cellbg = td.style.backgroundImage;
	var newImage = (cellbg == '' || cellbg == 'none') ? ximage : xnone;
	var selected = (cellbg == '' || cellbg == 'none') ? true : false;
	td.style.backgroundImage = newImage;
	td.selected = selected;
 	calculateBingo();
}

function calculateBingo () {
	var winners = [
		[ 5, 6, 7, 8, 9 ],
		[ 10, 11, 12, 13, 14 ],
		[ 15, 16, 17, 18, 19 ],
		[ 20, 21, 22, 23, 24 ],
		[ 25, 26, 27, 28, 29 ],
		[ 5, 10, 15, 20, 25 ],
		[ 6, 11, 16, 21, 26 ],
		[ 7, 12, 17, 22, 27 ],
		[ 8, 13, 18, 23, 28 ],
		[ 9, 14, 19, 24, 29 ],
		[ 5, 11, 17, 23, 29 ],
		[ 9, 13, 17, 21, 25 ],
	];
	var possibleWinners = winners.length;

	var winnercolor = 'red';
	var nonwinnercolor = 'black';
	var top = document.getElementsByClassName("top");
	var winnerImage = 'url(x-symbol-red.png)';
	var nonwinnerImage = 'url(x-symbol.png)';

	var allTds = document.querySelectorAll('td');
	allTds[17].selected = true;  // FREE space

	// Compare winners array to selected array for matches

	for (var i = 0; i < possibleWinners; i++) {
		var numberSelected = 0;
		for(var j = 0; j < 5; j++) {
			if (allTds[winners[i][j]].selected == true) {
				numberSelected++;
			}
		}

		// If all 5 winner cells have been selected, change header & cells to red

		if (numberSelected == 5) {

			for(var j = 0; j < 5; j++) {
				allTds[winners[i][j]].style.backgroundImage = winnerImage;
			}

			top[0].style.color = winnercolor;
			var msg = new SpeechSynthesisUtterance('Bingo');
			window.speechSynthesis.speak(msg);
			break;
		} else {
			for(var j = 0; j < 5; j++) {
				allTds[winners[i][j]].style.backgroundImage = (allTds[winners[i][j]].selected == true) ? nonwinnerImage : 'none';
			}
			top[0].style.color = nonwinnercolor;
		}
	}
}
