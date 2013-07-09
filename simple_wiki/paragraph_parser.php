<?php

namespace Wiki_Parser;

require_once __DIR__ . '/../block_parser.php';
require_once __DIR__ . '/../inline_parser.php';

//============================================================================
// generic paragraph parser
//============================================================================

class Simple_Wiki_Paragraph_Parser implements Block_Parser {
	
	private $inline_parser;
	
	//---------------------------------------------------------
	// setting inline parser
	//---------------------------------------------------------
	
	function set_inline_parser(Inline_Parser $inline_parser){
		
		$this->inline_parser = $inline_parser;
		
	}
	
	//---------------------------------------------------------
	// testing if block meets defining conditions
	//---------------------------------------------------------
	// designed as default block: always true
	//---------------------------------------------------------
	
	function test($input){
		
		return true;
	}
	
	//---------------------------------------------------------
	// parsing block
	//---------------------------------------------------------
	
	function parse($input){
		
		$content = $this->inline_parser
			? $this->inline_parser->parse($input)
			: $input;
		
		$output =
			'<p>' .
			$content .
			'</p>' .
			"\n";
		
		return $output;
	}
	
}

?>
