<?php

class Buscador {

	private $indelpenalty = -1;
	private $misspenalty = -2;
	private $matchreward = 5;
	
	function busca($str,$strlist) {
		
		$minexpectedval = strlen($str)*$this->matchreward /2;
		$matches = array();
		foreach ($strlist as $nextStr) {
			$val = $this->matchingscore($str,$nextStr);
			if($val >= $minexpectedval) {
				if(!isset($matches[$val]))
					$matches[$val] = array($nextStr);
				else
					$matches[$val][count($matches[$val])] = $nextStr;
			}
		}
		
		$success = krsort($matches);
		if(!$success){
		    echo "matching sorting failed!";
		}
		//erase any blank spaces (hopefully)
		$matches = array_values($matches);
		return $matches;
	}

	function matchingscore($str1, $str2) {
		
		$str1 = strtolower($str1);
		$str2 = strtolower($str2);
		
		$M[][] = array();
		
		for ($i=0; $i<= strlen($str1);$i++) {
			$M[$i][0] = $i*$this->indelpenalty;
		}
		for ($i=0; $i<= strlen($str2);$i++) {
			$M[0][$i] = $i*$this->indelpenalty;
		}

		for ($i=1; $i<= strlen($str1);$i++) {
			for ($j=1; $j<= strlen($str2);$j++) {
				$scr = ($str1[$i-1] == $str2[$j-1]) ? $this->matchreward : $this->misspenalty;
				$M[$i][$j] = max($M[$i-1][$j-1]+$scr, $M[$i][$j-1]+$this->indelpenalty, $M[$i-1][$j]+$this->indelpenalty);
			}
		}
		return $M[strlen($str1)][strlen($str2)];
	}
}