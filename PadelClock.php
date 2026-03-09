<?php
    function GamePoints($slot) {
        echo "                <td id='gp$slot'>\n";

        echo "                </td>\n";
    }
?>
<html>
    <head>
        <title>PadelClock</title>
    </head>
    <body>
        <table>
            <tr>
	        <?php 
                    GamePoints(0);
                    GamePoints(1);
                ?>
            </tr>
        </table>
    </body>
    <script>
        function setGamePointsSlot(slot, points) {
	    let ele = document.getElementById(slot);
	    if ( ! ele ) console.log("Element " + ele + " not found.");
	    ele.innerHtml = points;
        }
	setGamePointsSlot('g0', 15);
        setGamePointsSlot('g1', 30);
</html>

