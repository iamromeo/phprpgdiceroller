PHP Role Playing games Dice roller
================

A Dice roller class written in PHP that allows rolls of multiple and modified dice like 1d20+10, d20+d6-2, 4d6+3 etc. 

The library consists of a simple factory class which generates dice each dice is represented by a string which defines its specification. 

For example:
1d20 
d20+3
d20+s6+2
2d6+1d10-2
etc. 

The library separates the invidual dice and rolls a random number from 1 to the number defined after "d" e.g. 1d20 (1-20) and then multiplies it with the number preceeding the "d". 

At this point you can add or subtrack dice of modifiers but you can only multiply by the dice modifier not by a modifier. 
2d6+7 OK
2d6*2 NOT OK

Please note that static numerical modified should always be at the end of the dice statement. 
1d4+1d6+8 OK
1d4+8+1d6 NOT OK

Usage
================

$dr = new DiceRollFactory();

$dice = $dr->build('1d4+1d6+8'); // On construction the dice get its first random numbers

echo $dice(); // Echoing dice will give you an integer of the result stored (using __toString() )

$dice->roll(); // will re-roll based on the specification given. Returns Integer of the total result
$dice->roll('string'); // Will re-roll and return a string describing the result (human reaaable including individual dice and total)

$dice->getTotal(); // Same as echo $dice();

$dice->getResults(); // Returns array of individual dice results

$dice->getDice(); // Returns array of dice and their properties (multiplier, sign, sides)



