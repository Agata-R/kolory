<?php


class Color{
	private $hexColor;
	private $hslColor;
	private $error;
	/*
	private const COLORMODELS = {
		'hex', 'hsl', 'rgb'
	}
	*/
	function __construct($color = null){

		$color = $this->getRandomColor();		
		$hslColor = $this->hexToHsl($color);
		$this->hexColor = $color;
		$this->hslColor = $hslColor;
		$this->error = '';
	}	
	public function getHexColor(){
		return '#'.$this->hexColor;
	}
	public function getHslColor(){
		return $this->hslColor;
	}
	public function setHexColor($newColor){
		$newColor = trim($newColor);
		$newColor = preg_replace('/\s+/', '', $newColor);
		$newColor = preg_replace('/#/', '', $newColor);
		$this->hexColor = $newColor;
	}
	public function getRandomColor(){
		$r = rand (0,255);
		$g = rand (0,255);
		$b = rand (0,255);		
		$rgbRandomColor = "rgb($r,$g,$b)";
		$r = dechex($r);
		$g = dechex($g);
		$b = dechex($b);
		$r = strlen($r) == 2 ? $r : '0'.$r;
		$g = strlen($g) == 2 ? $g : '0'.$g;
		$b = strlen($b) == 2 ? $b : '0'.$b;
		$randomColor = $r . $g . $b ;
		return $randomColor;		
	}
	public function setRandomColor(){
		$this->hexColor = $this->getRandomColor();
	}
	public function validateColorFormat($color){
		
	}
	public function checkColorModel($color){
		$colorModel = '';
		/*regex dla spr czy liczba jest MNIEJSZA NIŻ wskazana np. 255
		liczba może być: jednocyfrowa | dwucyfrowa | trzycyfrowa ---> \d|\d\d|\d\d\d
		ale ostatnia trzycyfrowa liczba musi być mniejsza niż 255
		czyli może być dowolną liczbą pow 100 do 199 | pow 200 do 249 | pow 250 do 255 ---> 1\d\d|2[0-4]\d|25[0-5]*/
		$rgbPattern = '/rgb\(((\d|\d\d|1\d\d|2[0-4]\d|25[0-5]),){2}(\d|\d\d|1\d\d|2[0-4]\d|25[0-5])\)$/';
		$hslPattern = '/hsl\((\d|\d\d|[1-2]\d\d|3[0-5]\d|360),(\d|\d\d|100)%,(\d|\d\d|100)%\)$/';
		// kod koloru może, ale nie musi zaczynać się od #
		// brak walidacji dla skrótów $fff przez konwersję???
		$hexPattern = '/^#?([A-Fa-f\d]{6})$/';
		
		if(preg_match($rgbPattern,$color)){
			$colorModel = 'rgb';
		}else if (preg_match($hslPattern,$color)){
			$colorModel = 'hsl';
		}else if(preg_match($hexPattern,$color)){
			$colorModel = 'hex';
		} else{
			$this->error = 'Not defined color model.';
			return false;
		}		
		return $colorModel;
	}
	
	public function hexToHsl($hexColor){
		//var_dump($hexColor);
		if($this->checkColorModel($hexColor) === 'hex' ){
			$rgb_array = str_split($hexColor,2);
			$rgb = array(
			'r' => round( hexdec($rgb_array['0']) / 255, 2),
			'g' => round( hexdec($rgb_array['1']) / 255, 2),
			'b' => round( hexdec($rgb_array['2']) / 255, 2)
			);			
			// Luminace
			$max = max($rgb);
			$min = min($rgb);
			$l = round(($min + $max)/2, 2) * 100; 			
			// Saturation			
			if($l<=50){
				$s = round((($max-$min)/($max+$min)),2) * 100 ;//var_dump($max);
			}else{
				$s = round((($max-$min)/(2-$max-$min)),2) * 100;
			}			
			// Hue
			$max_key = array_search($max,$rgb);
			switch($max_key){
				case 'r':
					$h = ($rgb['g'] - $rgb['b'])/($max-$min);
					break;
				case 'g':
					$h = 2 + ($rgb['b'] - $rgb['r'])/($max-$min);
					break;
				case 'b':
					$h = 4 + ($rgb['r'] - $rgb['g'])/($max-$min);
					break;
			}
			$h = round( $h * 60 );
			if($h <0 ){
				$h = $h + 360;
			}
			$hsl = 'hsl(' . $h . ',' . $s . '%,' . $l . '%)' ;
			return $hsl;		
			
		}
		else{
			$this->error .= 'Can\'t conver hex color to rgb.';
			return $hexColor;
		}
	}
	
	public function getHslNumbers(){
		$color = $this->hslColor;
		$notImportant = array('hsl(','%',')');
		$color = str_replace($notImportant, '', $color);
		$colorNumbers = explode(',',$color);
		$hslNumbers = array(
			'h' => $colorNumbers['0'],
			's' => $colorNumbers['1'],
			'l' => $colorNumbers['2']
			);	
		return $hslNumbers;
	}
	public function hslCode($h,$s,$l){
		$hsl = 'hsl(' . $h . ',' . $s . '%,' . $l . '%)' ;
		return $hsl;
	}
	public function getTones($numberOfTones = 6,$range = 60){
		
		$hslNumbers = $this->getHslNumbers();
		$h = $hslNumbers['h'];
		$s = $hslNumbers['s'];
		$l = $hslNumbers['l'];
		$delta = $range / $numberOfTones;
		$range = $range / 2;
		$newL = $l - $range;
		
		$printTones = [];
		$listOfTones = [];
		for($i=0;$i<$numberOfTones;$i++){
			$newColor = $this->hslCode($h,$s,$newL);
			array_push($listOfTones,$newColor);
			array_push($printTones, '<div class="ton" data-color="' . $newColor . '" style="background-color:' . $newColor . '"></div>');
			$newL = $newL + $delta;
		}
		shuffle($printTones);
		
		return $tones = array( 'print' => implode($printTones), 'order' => $listOfTones );
	}
}
















?>