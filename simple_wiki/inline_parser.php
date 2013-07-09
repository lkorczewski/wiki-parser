<?php

namespace Wiki_Parser;

require_once __DIR__ . '/../inline_parser.php';

//============================================================================
// Simple Wiki inline parser
//============================================================================

class Simple_Wiki_Inline_Parser implements Inline_Parser {
	
	//------------------------------------------
	// setting markers
	//------------------------------------------
	
	function set_italic_delimiters(
		$left_delimiter,
		$right_delimiter
	){
		$this->add_simple_markup(
			$left_delimiter,
			$right_delimiter,
			'i'
		);
	}
	
	function set_bold_delimiters(
		$left_delimiter,
		$right_delimiter
	){
		$this->add_simple_markup(
			$left_delimiter,
			$right_delimiter,
			'b'
		);
	}
	
	function set_undeline_delimiters(
		$left_delimiter,
		$right_delimiter
	){
		$this->add_simple_markup(
			$left_delimiter,
			$right_delimiter,
			'u'
		);
	}
	
	function set_strikethrough_delimiters(
		$left_delimiter,
		$right_delimiter
	){
		$this->add_simple_markup(
			$left_delimiter,
			$right_delimiter,
			's');
	}
	
	function set_link_delimiters(
		$left_delimiter,
		$middle_delimiter,
		$right_delimiter
	){
		$quoted_left_delimiter    = $this->regex_quote($left_delimiter);
		$quoted_middle_delimiter  = $this->regex_quote($middle_delimiter);
		$quoted_right_delimiter   = $this->regex_quote($right_delimiter);
		$internal_link            = '/p=';
		
		//--------------------------------
		// external labelled links
		//--------------------------------
		$this->search[] =
			'/' .
			$quoted_left_delimiter .
			'(https?.+?)' .
			$quoted_middle_delimiter .
			'(.+?)' .
			$quoted_right_delimiter .
			'/';
		$this->replace[]  = '<a href="$2">$1</a>';
		
		//--------------------------------
		// external unlabelled links
		//--------------------------------
		$this->search[] =
			'/' .
			$quoted_left_delimiter .
			'(https?.+?)' .
			$quoted_right_delimiter .
			'/';
		$this->replace[] = '<a href="$2">$1</a>';
		
		//--------------------------------
		// internal labelled links
		//--------------------------------
		$this->search[] =
			'/' .
			$quoted_left_delimiter .
			'(.+?)' .
			$quoted_middle_delimiter .
			'(.+?)' .
			$quoted_right_delimiter .
			'/';
		$this->replace[] = '<a href="' . $internal_link .'$2">$1</a>';
		
		//--------------------------------
		// internal unlabelled links
		//--------------------------------
		$this->search[]   =
			'/' .
			$quoted_left_delimiter .
			'(https?.+?)' .
			$quoted_right_delimiter .
			'/';
		$this->replace[] = '<a href="' . $internal_link .'$1">$1</a>';
		
	}
	
	//------------------------------------------
	// parsing wiki syntax into HTML code
	//------------------------------------------
	
	function parse($input){
		
		$output = preg_replace($this->search, $this->replace, $input);
		
		return $output;
	}
	
	//------------------------------------------

	private function add_simple_markup(
		$left_delimiter,
		$right_delimiter,
		$XML_tag
	){
		$quoted_left_delimiter   = $this->regex_quote($left_delimiter);
		$quoted_right_delimiter  = $this->regex_quote($right_delimiter);
		
		$this->search[] =
			'/' .
			$quoted_left_delimiter .
			'(.+?)' .
			$quoted_right_delimiter .
			'/';
		$this->replace[]  = '<' . $XML_tag . '>$1</' . $XML_tag . '>';
	}
	
	//------------------------------------------
	
	private function regex_quote($expression){
		return preg_quote($expression, '/');
	}
	
}

?>
