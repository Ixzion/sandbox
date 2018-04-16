<?php

function recursiveCheck ($sSentence, $aPalindromes = array()) {
    
    $aOGString = explode(" ", $sSentence);
    
    // Reverse the word to compare
    $sReverse = strrev($aOGString[0]);
    
    if ($sReverse == $aOGString[0]) {
        
        if (strlen($aOGString[0]) > 2) {
            $aPalindromes[$aOGString[0]] = $aOGString[0];
        }
        
    }
    
    unset($aOGString[0]);
    
    if (empty($aOGString)) {
        
        echo implode("<br>", $aPalindromes);
    }
    else {
        // Remove the first element, then implode.
        $sSentence = implode(" ", $aOGString);
        recursiveCheck($sSentence, $aPalindromes);
    }
    
    
}


$string = "How many level aa of palindromes did we see in aaaaaa this poop chute aa?";

recursiveCheck($string);


?>