<?php
session_start();
	$caption_text = file_get_contents("caption.txt");
	$caption_text = str_replace("\n", "<br>", $caption_text);
	$caption_text = str_replace("<br><br>", "<br>&nbsp;<br>", $caption_text);
	$printbuffer = "<div class='topbuffer'><p></div>";
	$top = "<div class='card'><table class='card'>\n
	<tr class='top'><td>M<hr>B</td><td>A<hr>I</td><td>R<hr>N</td><td>S<hr>G</td><td>H<hr>O</td></tr>\n";
	$bottomprint = "<tr><td colspan=5 align=center>5 in a row in any direction wins!</td></tr>\n </table></div>\n";
	$bottom = "</table></div>\n";
	$caption = "<p class='caption2'><br>$caption_text
	</p>";
	$printcaption = "<p class='caption2'><br>$caption_text
	</p>";
	
	$number_of_cards = 5; // the amount of unique cards to generate. don't make too many!

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
	$bingo_cards = array_slice($bingo_cards, 0, 5);
	/* OUTPUT as web page to be printed */
	$print = "";
	$preview = "";
	foreach($bingo_cards as $card) {
		$middle = "";
		for($k=0; $k<(sizeof($card)/5); $k++) {
			$id = ($k == 2) ? "class='free'" : "";
			$middle .= "<tr>\n";
			$middle .=  "<td>" . $card[$k] . "</td>\n";
			$middle .= "<td>" . $card[$k+5] . "</td>\n";
			$middle .= "<td $id>" . $card[$k+10] . "</td>\n";
			$middle .= "<td>" . $card[$k+15] . "</td>\n";
			$middle .= "<td>" . $card[$k+20] . "</td>\n";
			$middle .= "</tr>\n";
		}
		$print .= $printbuffer . $top . $middle . $bottomprint;
		$preview .= $top . $middle . $bottom;
	}
	$time = time();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Bingo Cards</title>
	<link type="text/css" rel="stylesheet" href="bingo.css?s=<?php print time(); ?>">
	<script type='text/javascript' src="html2pdf.bundle.min.js"></script>
<script>
function centerprint() {
	// Print the cards one sheet per card

	window.print();
}

function save() {
	// Get the saveable version of the cards and save it as a PDF

	var opt = { filename: 'bingoocards.pdf', enableLinks: false, margin: 20, pagebreak: { mode: 'css', after: '.card' } };
	var element = document.getElementById('playpages');
	html2pdf(element, opt);
}

</script>
</head>
<body>
<div class="cardbutton">
<button class="button" onclick="centerprint()">Print your cards</button>
&nbsp;
<button class="button" onclick="location.reload()">Get new cards</button>
&nbsp;
<button class="button" onclick="save()">Save cards as PDF</button>
</div>
<!-- The on-screen cards -->
<ul id="preview">
	<li>
	<?php print $preview; ?>
	</li>
	<?php print $caption; ?>
</ul>
<!-- The printable cards -->
<ul id="pages">
	<li>
	<?php print $print; ?>
	</li>
	<?php print $printcaption; ?>
</ul>
<!-- The saveable cards -->
<div style="display: none;">
<ul id="playpages">
	<li>
	<?php print $print; ?>
	</li>
	<?php print $printcaption; ?>
</ul>
</div>
</body>
</html>
