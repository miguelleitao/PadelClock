<!DOCTYPE html>
<?php
    function GamePoints($slot) {
		if ( $slot==0 ) echo " <td id='ss0' class='gp_space' style='-webkit-user-select:none;user-select:none;' 
                     unselectable='on'
                     onselectstart='return false;' 
                     onmousedown='return false;'> </td>\n";
        echo "                <td 
                     colspan=2
                     class='gp_slot' 
                     id='gp$slot' 
                     onclick='cbClickSlotGP($slot);'
                     ondblclick='cbDblClickSlotGP($slot);'
                     style='-webkit-user-select:none;user-select:none;' 
                     unselectable='on'
                     onselectstart='return false;' 
                     onmousedown='return false;'>\n";
        echo "         --\n";
        echo "                </td>\n";
		if ( $slot==1 ) echo " <td id='ss1' colspan=2 class='gp_space' style='-webkit-user-select:none;user-select:none;' 
                     unselectable='on'
                     onselectstart='return false;' 
                     onmousedown='return false;'> </td>\n";
    }
    function SetGames($slot) {
		if ( $slot==0 ) echo " <td colspan=2 class='sg_space'></td>\n";
        echo "                <td 
                     class='sg_slot' 
                     id='sg$slot' 
                     onclick='incSlotSG($slot);'
                     style='-webkit-user-select:none;user-select:none;' 
                     unselectable='on'
                     onselectstart='return false;' 
                     onmousedown='return false;'>\n";
        echo "         --\n";
        echo "                </td>\n";
		if ( $slot==1 ) echo " <td colspan=2 class='sg_space'></td>\n";
    }
?>
<html>
    <head>
        <title>PadelClock</title>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="manifest" href="manifest.json">
    </head>
    <style>
        table { width: 100%; }
		.gp_slot { 
			font-size: 400pt;
			text-align: center;
			font-family: arial;
			width: 40%;
			border: 1px solid #888;
			padding: 0;
		}
		.sg_slot { 
			font-size: 40pt;
			text-align: center;
			font-family: arial;
			width: 10%;
			border: 1px solid #888;
		}
		.gp_space {
			width: 7%;
			border: 1px solid #888;
			font-size: 200pt;
			text-align: center;
			font-family: "Noto Sans", sans-serif;
			padding-bottom: 110px;
		}
		.sp_space {
			width: 7%;
			border: 1px solid #888;
		}
    </style>
    <body>
		<!-- definição do símbolo -->
		<svg width="0" height="0" style="position:absolute">
		  <symbol id="triangulo-direita" viewBox="0 0 100 100">
			<polygon points="10,10 10,90 85,50" fill="black"/>
		  </symbol>
		  <symbol id="triangulo-esquerda" viewBox="0 0 100 100">
			<polygon points="90,10 90,90 15,50" fill="black"/>
		  </symbol>
		</svg>
        <table>
			<tr>
	            <?php 
                    SetGames(0);
                    SetGames(1);
                ?>
			</tr>
            <tr>
	            <?php 
                    GamePoints(0);
                    GamePoints(1);
                ?>
            </tr>
        </table>
    </body>
    <script>
        var gamePoints = new Array(2);
        var setGames = new Array(2);
        var serveSide = 0;
        var nDeuce = 0;
        var nBreakPoints = 0;
        var nMatchPoints = 0;
        const serveSideSymbol = [
			"<svg width='100' height='100'> <use href='#triangulo-direita'></use> </svg>",
			"<svg width='100' height='100'> <use href='#triangulo-esquerda'></use> </svg>",
		];
        const gamePointsStr = [0, 15, 30, 40, "V"];
        function resetGame() {
            gamePoints[0] = 0;
            gamePoints[1] = 0;
            nDeuce = 0;
            nBreakPoints = 0;
        }
        function resetSet() {
            setGames[0] = 0;
            setGames[1] = 0;
        }
        function showServeSideSlot(slot) {
			let slotId = "ss" + slot;
			let ele = document.getElementById(slotId);
            if ( ! ele ) {
				console.log("Element " + slotId + " not found.");
				return;
			}
            if ( serveSide == slot ) {
				ele.innerHTML = serveSideSymbol[slot];
			}
            else {
                ele.innerHTML = "";
			}
		}
		function showServeSides() {
			showServeSideSlot(0);
			showServeSideSlot(1);
		}
        function showGamePointsSlot(slot, points=gamePoints[slot]) {
            let slotId = "gp" + slot;
			//console.log("Looking for element " + slotId);
            let ele = document.getElementById(slotId);
            if ( ! ele ) console.log("Element " + slotId + " not found.");
            ele.innerHTML = gamePointsStr[points];
        }
        function showSetGamesSlot(slot, games=setGames[slot]) {
            let slotId = "sg" + slot;
			//console.log("Looking for element " + slotId);
            let ele = document.getElementById(slotId);
            if ( ! ele ) console.log("Element " + slotId + " not found.");
            ele.innerHTML = games;
        }
        function showGamePoints() {
            showGamePointsSlot(0);
            showGamePointsSlot(1);
        }
        function showSetGames() {
            showSetGamesSlot(0);
            showSetGamesSlot(1);
        }
        function incSlotGP(slot) {
            gamePoints[slot] += 1;
            //if ( gamePoints[slot]>4 ) resetGame();
            if ( gamePoints[slot]>=4 && Math.abs(gamePoints[0]-gamePoints[1])>1 ) {
				// Game Win
                resetGame();
				showGamePoints();
				incSlotSG(slot);
				return;
			}
			if ( gamePoints[0]==gamePoints[1] && gamePoints[0]>3 ) {
				// deuce: 40-40
                gamePoints[0] = 3;
                gamePoints[1] = 3;
                nDeuce += 1;
                showGamePoints();
                return;
            }
            if ( gamePoints[1-serveSide] >= 3 ) {
				// BreakPoint
				nBreakPoints += 1;
			}
            showGamePointsSlot(slot);
        } 
        function incSlotSG(slot) {
			console.log("incSlotSG(" + slot + ")");
            setGames[slot] += 1;
            serveSide = 1 - serveSide;
            showServeSides();
			console.log("incSlotSG(" + slot + ") = " + setGames[slot]);
            //if ( gamePoints[slot]>4 ) resetGame();
            if ( setGames[slot]>=6 && Math.abs(setGames[0]-setGames[1])>1 ) {
                resetSet();
				showSetGames();
				return;
			}
            showSetGamesSlot(slot);
			console.log("incSlotSG(" + slot + ") Done");
        }
        var timerGP;
        var firingGP = false;
        function cbClickSlotGP(slot) {
			// Detect the 2nd single click event, so we can stop it
			if (firingGP) return;

			firingGP = true;
			timerGP = setTimeout(function() {
			   if ( firingGP ) incSlotGP(slot); 
			   firingGP = false;
			}, 350);
		}
        function cbDblClickSlotGP(slot) {
			firingGP = false;
			console.log('cbDblClickSlotGP(' + slot + ')');
			incSlotGP(1-slot);
			return false;
		}
		serveSide = 0;
		showServeSides();
        resetSet();
        showSetGames();
        resetGame();
        showGamePoints();
    </script>
</html>

