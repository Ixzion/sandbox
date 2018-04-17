<?php

// This checks a sentence for recursive words and returns them
function returnPalindromes ($sSentence, $aPalindromes = array()) {
    
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
        returnPalindromes($sSentence, $aPalindromes);
    }
    
    
}


$string = "Are your palindromes on the level or is your kayak due to implode at noon, good madam?";

returnPalindromes($string);


?>