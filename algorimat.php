<?php
echo "\n\n";

//nomor 1
function reverseAlphabetsWithNumber($string) {
    
    $alphabets = '';
    $number = '';

    for ($i = 0; $i < strlen($string); $i++) {
        if (ctype_alpha($string[$i])) {
            $alphabets .= $string[$i];
        } else {
            $number .= $string[$i];
        }
    }

    $reversedAlphabets = strrev($alphabets);
    return $reversedAlphabets . $number;
}

$input = "NEGIE1";
$result = reverseAlphabetsWithNumber($input);
echo $result;  // Output: EIGEN1
echo "\n\n";




//nomor 2
function longest($sentence) {

    $words = explode(' ', $sentence);
    $longestWord = '';
    $maxLength = 0;

    foreach ($words as $word) {
        if (strlen($word) > $maxLength) {
            $longestWord = $word;
            $maxLength = strlen($word);
        }
    }

    return $longestWord . ': ' . $maxLength . ' character' . ($maxLength > 1 ? 's' : '');
}

$sentence = "Saya sangat senang mengerjakan soal algoritma";
$result = longest($sentence);
echo $result ;
echo "\n\n";



//nomor 3
function countOccurrences($input, $query) {
    $output = [];

    foreach ($query as $q) {
     
        $count = 0;
        foreach ($input as $i) {
            if ($i === $q) {
                $count++;
            }
        }
      
        $output[] = $count;
    }

    return $output;
}

$input = ['xc', 'dz', 'bbb', 'dz'];
$query = ['bbb', 'ac', 'dz'];
$result = countOccurrences($input, $query);
print_r($result);
echo "\n\n";


//nomor 4
function diagonalDifference($matrix) {
    $n = count($matrix);
    $primaryDiagonalSum = 0;
    $secondaryDiagonalSum = 0;

    for ($i = 0; $i < $n; $i++) {
        $primaryDiagonalSum += $matrix[$i][$i];
        $secondaryDiagonalSum += $matrix[$i][$n - $i - 1];
    }

    return abs($primaryDiagonalSum - $secondaryDiagonalSum);
}

$matrix = [
    [1, 2, 0],
    [4, 5, 6],
    [7, 8, 9]
];

$result = diagonalDifference($matrix);
echo $result;  // Output: 3
echo "\n\n";