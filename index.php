<?php

if (!isset($_SESSION)){
    session_start();
}
if (isset($_SESSION)) {
    if (isset($_GET['action']) && $_GET['action'] == "New") {
        session_unset();
    }
}
?>
<form name="Blackjack">
    <input type="submit" value="hit" name = "action" >
    <input type="submit" value="pass" name = "action" >
    <input type="submit" value="newgame" name = "action" >
</form>

<?php
if (!isset($_SESSION['deck'])) {
    $_SESSION['handscore'] = 0;
    $_SESSION['housescore']= 0;
    $cards = ["Ace", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Jack", "Queen", "King"];
    $suits = ["Hearts", "Clubs", "Spades", "Diamonds"];
    $deck = array();
    for ($x = 0; $x < 52; $x++) {
        $y = floor($x / 4);
        $z = $x % 4;
        $deck[$x] = "$cards[$y] of $suits[$z]";
    }
    $values = [11, 11, 11, 11, 2, 2, 2, 2, 3, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5, 6, 6, 6, 6, 7, 7, 7, 7, 8, 8, 8, 8, 9, 9, 9, 9, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10];
    for ($x=0; $x<52; $x++){
        $_SESSION['deck'][$x]=["name" => $deck[$x], "value" => $values[$x]];
    }
    $_SESSION['hand']=array();
    $_SESSION['house']=array();
    shuffle($_SESSION['deck']);
    array_push($_SESSION['hand'], ($_SESSION['deck'][0]));
    array_push($_SESSION['house'], $_SESSION['deck'][1]);
    array_push($_SESSION['hand'], ($_SESSION['deck'][2]));
    array_push($_SESSION['house'], $_SESSION['deck'][3]);
    $_SESSION['handscore'] = $_SESSION['hand'][0]['value'] + $_SESSION['hand'][1]['value'];
    echo gameboard();
}
function hit(){
    array_push($_SESSION['hand'], ($_SESSION['deck'][2 + count($_SESSION['hand'])]));
    $_SESSION['handscore'] += $_SESSION['deck'][2 + count($_SESSION['hand'])]["value"];
    #if ($_SESSION['handscore'] > 21) {
     #   for ($x = 0; $x < count($_SESSION['hand']); $x++) {
      #      if (strpos($_SESSION['hand'][$x]["name"], "Ace") == true) {
       #         if ($_SESSION['hand'][$x]["value"] == 11) {
        #            $_SESSION['hand'][$x]["value"] = 1;
         #           $_SESSION['handscore'] = $_SESSION['handscore'] - 10;
          #      }
           # }
            if ($_SESSION['handscore'] >= 21) {
                return endgameboard();
            }
           # break;
        #}
   # }
    return gameboard();
}
function pass(){
    for ($x=0; $x<2; $x++){
        if ($_SESSION['handscore']<=16){
            array_push($_SESSION['house'], $_SESSION['deck'][1+count($_SESSION['hand'])]);
            unset($_SESSION['deck'][1+count($_SESSION['hand'])]);
            $_SESSION['handscore'] += $_SESSION['deck'][2+count($_SESSION['hand'])]['value'];
        }
        if ($_SESSION['handscore']>21){
            for ($x=0; $x< count($_SESSION['house']); $x++){
                if (strpos($_SESSION['house'][$x]["name"], "Ace") == true){
                    $_SESSION['house'][$x]{"value"}=1;
                }
                else{
                    echo endgameboard();
                    break;
                }
            }
        }
        if ($_SESSION['handscore']>16 && $_SESSION['handscore']<21){
            echo endgameboard();
            break;
        }
    }
}

function gameboard(){
    $hand = "";
    $values = 0;
    for ($x=0; $x< count($_SESSION['hand']); $x++){
        $hand .= $_SESSION["hand"][$x]["name"];
        $hand .= ", ";
        $values = $_SESSION['handscore'];
    }
    echo "You have the $hand. Your score is $values. ";
    $dealer = $_SESSION['house'][0]['value']+$_SESSION['house'][1]['value'];
    echo "The dealer has the {$_SESSION['house'][0]["name"]} and the {$_SESSION['house'][1]["name"]}. The house score is $dealer.";
}

function endgameboard(){
    if ($_SESSION['handscore']>21){
        $bust = $_SESSION['handscore'];
        return "You busted! You lose. $bust is greater than 21";
    }
    if ($_SESSION['housescore']>21){
        $bust= $_SESSION['housescore'];
        return "The dealer busted! You win! The dealer had $bust";
    }
    if ($_SESSION['housescore']>= $_SESSION['handscore']){
        $you=$_SESSION['handscore'];
        $them = $_SESSION['housescore'];
        return "You lose. You had $you, but the dealer had $them";
    }
    $you = $_SESSION['handscore'];
    $them = $_SESSION['housescore'];
    return "You win! You had $you and the dealer had $them";
}

switch ($_GET['action']){
    case "newgame":
        session_unset();
        break;
    case "hit":
        hit();
        break;
    case "pass":
        pass();
        break;
}


