<?php

session_start();

if (isset($_GET['action']) && $_GET['action'] == "new") {
        session_unset();
}
?>
<form name="Blackjack">
    <input type="submit" value="hit" name = "action" >
    <input type="submit" value="pass" name = "action" >
    <input type="submit" value="new" name = "action" >
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
    $_SESSION['hand']=[];
    $_SESSION['house']=[];
    shuffle($_SESSION['deck']);
    $_SESSION['hand'][]=array_pop($_SESSION['deck']);
    $_SESSION['hand'][]=array_pop($_SESSION['deck']);
    $_SESSION['house'][]=array_pop($_SESSION['deck']);
    $_SESSION['house'][]=array_pop($_SESSION['deck']);
    $_SESSION['handscore'] = $_SESSION['hand'][0]['value'] + $_SESSION['hand'][1]['value'];
    echo gameboard();
}
function hit(){
    $_SESSION['hand'][]=array_pop($_SESSION['deck']);
    $_SESSION['handscore'] += $_SESSION['hand'][count($_SESSION['hand'])-1]['value'];
    if ($_SESSION['handscore'] >= 21) {
        echo endgameboard();
    }else{
        echo gameboard();
    }
}
function pass(){
    $_SESSION['housescore']= $_SESSION['house'][0]['value'] + $_SESSION['house'][1]['value'];
    for ($x=0; $x<9; $x++){
        if ($_SESSION['housescore']<=16){
            $_SESSION['house'][]= array_pop($_SESSION['deck']);
            $_SESSION['housescore'] += $_SESSION['house'][count($_SESSION['house'])-1]['value'];
        }
        else{
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
    case "hit":
        hit();
        break;
    case "pass":
        pass();
        break;
}


