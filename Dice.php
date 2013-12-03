<?php

/*
* 
* Dice Roller
* Input string examples: 1d20, 1d6+2, 2d4, 1d20+d8, 3d6-d4+2
* Output: 
* int Total, 
* str Dicerolls 
*
* Usage:
* $dice = new Dice('1d20+3');
* $dice->roll();
*
* Known issues/restrictions:
* - The modifier must always be at the end of the string. 
* - Currently it can only add or subdtrack dice and/or modifiers (no multiplication)
* 
 */

class Dice
{

    public $specification;
    private $dice;
    private $total;
    private $results;

    public function __construct( $specification ) 
    {
        $this->specification = $specification;
        $this->prepare();
        // First seeding 
        $this->roll('total');
    }

    private function prepare() 
    {
        $dice = preg_split( "/[+]|[-]/", $this->specification );

        $parts = preg_split('/([+]|[-])/', $this->specification, -1, PREG_SPLIT_DELIM_CAPTURE);

        $parts[-1] ='';
        $sentences = array();
        for ($i=0, $n=count($parts); $i<$n; $i+=2) {
            if ( $i == count($parts) ) {
                $sentences[] = $parts[$i-1].$parts[$i];
            }
            else {
                $sentences[] = $parts[$i-1].$parts[$i];
            }
        }

        $output = array();
        $i = 0;

        foreach ( $dice as $die ) {
            $output[$i]['string'] = $sentences[$i]; 
            $types  = explode('d', $die); // In case that more than one d in one string
            if (strpos($die, 'd') !== false) {
                // Find the type of dice
                $output[ $i ]['type']  = array_pop( $types ); // Take only the last
                // Find the multiplier of dice
                if ( reset( $types ) ) {
                    $multiplier = reset( $types );
                } else {
                    $multiplier = 1;
                }
                $output[ $i ]['multiplier']  = $multiplier; // Take only the last
                // Find sign
                if ( substr( $sentences[$i], 0,1) == '+' || substr( $sentences[$i], 0,1) == '-'  ) {
                    $sign = substr( $sentences[$i], 0,1);
                }
                else {
                    $sign = "+";
                }

                $output[ $i ]['sign'] = $sign;      
            } else {
                $output[ $i ]['type'] = 'modifier';
                $output[ $i ]['multiplier'] = 1;
                $output[ $i ]['value'] = $die;
                // Find sign
                $length = strlen($die) + 1;
                $sign = substr($this->specification, -$length, 1 );
                $output[ $i ]['sign'] = $sign;
            }
            $i++;
        }       

        // var_dump( $output );
        $this->dice = $output;
    }

    /**
     * Rolls the prepared dice set
     * Returns: depending on options
     * Options
     * total: returns the total score of the roll as an integer
     * dice: array of individual rolls
     * string: return literal of rolls
     * 
     */
    public function roll( $options = 'total' ) {
        $results = array();
        $total = 0;
        foreach ( $this->dice as $die ) {
            if ( $die['type'] != 'modifier' ) {
                $result = mt_rand(1, $die['type'] ) * $die['multiplier'];
            } else {
                $result = $die['value'];
            }
            if ( $die['sign'] == '-' ) {
                $result = -$result;
            }
            $results[ $die['string'] ] = $result;
            $total = $total + $result;
        }

        $this->results = $results;
        $this->total = $total;

        switch ( $options ) {
            case 'total':
                return $this->total;
                break;
            case 'dice':
                return $this->results;
                break;
            case 'string':
                $string = 'Rolled ';
                foreach ( $this->results as $key => $value ) {
                    $string .= $key .'('.$value.') ';
                }
                $string .= ' Total: ' . $this->total; 
                return $string;
                break;
        }

    }

    public function getTotal() {
        return $this->total;
    }

    public function getResults() {
        return $this->results;
    }

    public function getDice() {
        return $this->dice;
    }

    public function __toString() {
        return (string)$this->total;
    }

}