<?php
	$caption = file_get_contents("caption.txt");
	$caption = str_replace("\n", "<br>", $caption);
	$caption = str_replace("<br><br>", "<br>&nbsp;<br>", $caption);
	$top = "<div class='card'><table class='card'>\n
	<tr class='top'><td>M<hr>B</td><td>A<hr>I</td><td>R<hr>N</td><td>S<hr>G</td><td>H<hr>O</td></tr>\n";
	$bottom = "<tr><td colspan=5 align=center>5 in a row in any direction wins! </td></tr>\n </table>
	<p class='caption' id='caption1'>$caption
	</p>
	</div>\n";
	
	$bottompdf = "<tr><td colspan=5 align=center>5 in a row in any direction wins! </td></tr>\n </table>
	<p class='captionpdf'>$caption
	</p>
	</div>\n";
	
	$number_of_cards = 1;

	$columns = array(
		range(1,15),
		range(16,30),
		range(31,45),
		range(46,60),
		range(61,75)
	);

	$bingo_cards = array();
	$card_hashes = array();
	$i = 0;
	
	/* GENERATE THE CARDS */
	while($i < 1000) {
		$bingo_card = array();

		for($j=0; $j<5; $j++) {
			$random_values = $columns[$j];
			shuffle($random_values);
			$bingo_card = array_merge($bingo_card, array_slice($random_values, 0, 5));
		}
		$bingo_card[12] = "FREE";

		// generate a unique hash for this card and compare it to the ones we already have
		$card_hash = md5(json_encode($bingo_card)); // or whatever hashing algorithm is preferred

		if(!in_array($card_hash, $card_hashes)) {
			$bingo_cards[] = $bingo_card;
			$card_hashes[] = $card_hash;
			$i += 1;
		}

		if($i > 10000) break; // safety exit
	}

	shuffle($bingo_cards);
	$bingo_cards = array_slice($bingo_cards, 0, $number_of_cards);
	$print = "";
	$preview = "";
	$pdf = "";
	foreach($bingo_cards as $card) {
		$middle = "";
		for($k=0; $k<(sizeof($card)/5); $k++) {
			$id = ($k == 2) ? "class='free'" : "";
			$middle .= "<tr>\n";
			$middle .= "<td onClick='clickCell(this);'>" . $card[$k] . "</td>\n";
			$middle .= "<td onClick='clickCell(this);'>" . $card[$k+5] . "</td>\n";
			$middle .= "<td onClick='clickCell(this);' $id>" . $card[$k+10] . "</td>\n";
			$middle .= "<td onClick='clickCell(this);' >" . $card[$k+15] . "</td>\n";
			$middle .= "<td onClick='clickCell(this);'>" . $card[$k+20] . "</td>\n";
			$middle .= "</tr>\n";
		}
		$print .= $top . $middle . $bottom;
		$preview .= $top . $middle . $bottom;
		$pdf .= $top . $middle . $bottompdf;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Bingo Cards</title>
	<link type="text/css" rel="stylesheet" href="bingo.css?s=<?php print time(); ?>">
	<script type='text/javascript' src='card.js'></script>
	<script type='text/javascript' src="html2pdf.bundle.min.js"></script>
</head>
<script>
function centerprint() {
	window.print();
}

function save() {
	var opt = { filename: 'bingoocard.pdf', enableLinks: false, margin: 20 };
	var element = document.getElementById('playpagespdf');
	html2pdf(element, opt);
}
</script>
<body>
<div class="cardbutton">
<button class="button" onclick="centerprint()">Print your card</button>
&nbsp;
<button class="button" onclick="location.reload()">Get new card</button>
&nbsp;
<button class="button" onclick="save()">Save card as PDF</button>
</div>
</body>
<div>
<ul id="playpages">
	<li>
	<?php print $print; ?>
	</li>
</ul>
</div>
<div style="display: none;">
<ul id="playpagespdf">
	<li>
	<?php print $pdf; ?>
	</li>
</ul>
</div>
</html>
