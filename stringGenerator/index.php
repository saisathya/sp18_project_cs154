<?php

// 110111 = ab
// 0 = separator
// 11 = a
// 111 = b

// D1011010111011 =
// D = transition separator
// 0 = separator
// 1 = Current stage
// 11 = symbol to be read on tape (a)
// 1 = next stage number (2)
// 111 = Symbol to be written on tape (b)
// 11 = Which way to move pointer on tape

// F11
// F = Final stage separator
// 11 = stage number
// $XltY = "11101111D10110110";

$symbols = array('11', '111' );

$directions = array('11', '111');

$stages = array('1', '11', '111', '1111', '11111', '111111', '111111');

$stateDelimiter = 'D';
$finalStateDelimiter = 'F';
$separator = 0;

$testNumber = 20;
$symbolMax = 10;
$stageMax = 10;


$tests = generateTests();

print_r($tests);


$fh = fopen("/Applications/XAMPP/htdocs/tmTestGen/testfile.txt", 'w') or die("Failed to create file");

$text = '#### SHOULD PASS ####' . "\n";

$text .= <<<_END
110111D1011010111011D1011101011101D10101101011F11\n
110111D1011010111011D1011101011101D10101101011F11\n
_END;

$text .= '#### SHOULD FAIL ####' . "\n";

$text .= <<<_END
110111D1011010111011D1011101011101D10101101011F11\n
110111D1011010111011D1011101011101D10101101011F11\n
_END;

$text .= '#### GENERATED RANDOM TESTS ####' . "\n";
foreach ($tests as $test) {
    $text .=  $test . "\n";
}

fwrite($fh, $text)or die("Could not write to file");
fclose($fh);
echo "File 'testfile.txt' written successfully";


function generateTests()
{
    global $testNumber;

    $tests = array();

    for ($i=0; $i < $testNumber; $i++) {
        $tests[] = generateString().generateTM();
    }

    return $tests;
}

function generateString()
{
    global $symbols, $separator, $symbolMax;
    $testNumbers = mt_rand(1,$symbolMax);
    $symbol = '';
    $string = '';

    for ($i=0; $i < $testNumbers; $i++) {
        $symbol = (string)$symbols[array_rand($symbols)];
        if (!empty($string)) {
            $string .= $separator . $symbol;
        } else{
            $string .= $symbol;
        }
    }

    return $string;
}


function generateTM()
{
    global $finalStateDelimiter, $stageMax;
    $stringLength = mt_rand(1,$stageMax);

    $stagesString = '';

    for ($i=0; $i < $stringLength; $i++) {
        $stagesString .= generateStage();
    }

    $stagesString .= $finalStateDelimiter . randStageNumber();

    return $stagesString;
}


function generateStage()
{
    global $stateDelimiter, $separator;
    $encodedStage = ''. $stateDelimiter . randStageNumber() . $separator . randSymbol() . $separator . randStageNumber() . $separator . randSymbol() . $separator . randDirection();

    return $encodedStage;
}

function randSymbol()
{
    global $symbols;
    $symbol = (string) $symbols[array_rand($symbols)];

    return $symbol;
}

function randStageNumber()
{
    global $stages;
    $stage = (string) $stages[array_rand($stages)];

    return $stage;
}

function randDirection()
{
    global $directions;
    $direction = (string) $directions[array_rand($directions)];

    return $direction;
}

function convertToBinary($string)
{
    $binary = '';
    for ($i=0; $i < $string; $i++) {
        $binary = (string)$binary . '1';
    }

    return (string) $binary;
}

?>


