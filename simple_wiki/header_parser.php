<?php

namespace Wiki_Parser;

require_once __DIR__ . '/../block_parser.php';
require_once __DIR__ . '/../inline_parser.php';

//============================================================================
// generic header parser
//============================================================================

class Simple_Wiki_Header_Parser implements Block_Parser {
	
	private $quoted_marker;
	private $level;
	private $inline_parser = false;
	
	//--------------------------------------------------
	// setting inline parser
	//--------------------------------------------------
	// if not set,
	//  no additional parsing will take place
	//--------------------------------------------------
	
	function set_inline_parser($inline_parser){
		$this->inline_parser = $inline_parser;
	}
	
	//--------------------------------------------------
	// setting marker
	//--------------------------------------------------
	
	function set_marker($marker){
		$this->quoted_marker = preg_quote($marker);
	}
	
	//--------------------------------------------------
	// setting heading levele
	//--------------------------------------------------
	
	function set_level($level){
		$this->level = $level;
	}
	
	//--------------------------------------------------
	// testing if block meets defining conditions
	//--------------------------------------------------
	// beginning with delimiter,
	//  then single space,
	//  then single line of text
	//--------------------------------------------------
	
	function test($input){
		
		$regex = '/^' . $this->quoted_marker . ' .+$/'; 
		$is_matched = preg_match($regex, $input);
		
		return $is_matched;
	}
	
	//--------------------------------------------------
	// parsing block
	//--------------------------------------------------
	
	function parse($input){
		
		$regex = '/^' . $this->quoted_marker . ' (.+)$/';
		$header = preg_replace($regex, '$1', $input);
		
		$header = $this->inline_parser
			? $this->inline_parser->parse($header)
			: $header;
		
		$output =
			'<h' . $this->level . '>' .
			$header .
			'</h' . $this->level . '>' .
			"\n";
		
		return $output;
	}
	
}

?>
