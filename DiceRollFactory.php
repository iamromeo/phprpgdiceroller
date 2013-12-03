<?php
include('Dice.php');
/*
* 
* Dice Roller
* Input string examples: 1d20, 1d6+2, 2d4, 1d20+d8
* Output: 
* obj Dice
* 
 */

class DiceRollFactory
{

    public function build( $specification)
    {
        return new Dice( $specification );
    }

}