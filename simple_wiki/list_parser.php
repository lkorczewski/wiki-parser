<?php

namespace Wiki_Parser;

require_once __DIR__ . '/../block_parser.php';

//============================================================================
// generic list parser
//============================================================================

class Simple_Wiki_List_Parser implements Block_Parser {
	
	private $inline_parser;
	
	private $unordered_list_marker;
	private $ordered_list_marker;
	
	private $quoted_unordered_list_marker;
	private $quoted_ordered_list_marker;
	
	//--------------------------------------------------
	// setting inline parser
	//--------------------------------------------------
	
	function set_inline_parser($inline_parser){
		$this->inline_parser;
	}
	
	//--------------------------------------------------
	// setting markers
	//--------------------------------------------------
	
	function set_unordered_list_marker($unordered_list_marker){
		$this->unordered_list_marker         = $unordered_list_marker;
		$this->quoted_unordered_list_marker  = preg_quote($unordered_list_marker);
	}
	
	function set_ordered_list_marker($ordered_list_marker){
		$this->ordered_list_marker         = $unordered_list_marker;
		$this->quoted_ordered_list_marker  = preg_quote($unordered_list_marker);
	}
	
	//--------------------------------------------------
	// testing if bl
	//--------------------------------------------------
	
	function test($block_markup){
		
		$marker_regex = 
			$this->quoted_unordered_list_marker .
			'|' .
			$this->quoted_ordered_list_marker;
		
		$regex =
			'/^(' .
			$marker_regex .
			') .+(\n\1(' .
			$marker_regex .
			')* .+)$/';
		$is_matched = preg_match($regex, $block_markup);
		
		echo "<<$regex>>";
		
		return $is_matched;
	}
	
	//--------------------------------------------------
	// parsing
	//--------------------------------------------------
	
	function parse($input){
		$output =
			'<list>' . "\n"
			$input.
			'</list>' . "\n";
		
		return $output;
	}
	
	
	
}

?>
