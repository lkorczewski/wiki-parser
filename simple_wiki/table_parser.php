<?php

namespace Wiki_Parser;

require_once __DIR__ . '/../block_parser.php';

//============================================================================
// generic table parser
//============================================================================

class Simple_Wiki_Table_Parser implements Block_Parser {
	
	private $inline_parser = false;
	
	private $left_row_delimiter;
	private $right_row_delimiter;
	private $field_separator;
	
	private $quoted_left_row_delimiter;
	private $quoted_right_row_delimiter;
	private $quoted_field_separator;
	
	//--------------------------------------------------
	// setting inline parser
	//--------------------------------------------------
	
	function set_inline_parser($inline_parser){
		$this->inline_parser = $inline_parser;
	}
	
	//--------------------------------------------------
	// setting markers
	//--------------------------------------------------
	
	function set_left_row_delimiter($left_row_delimiter){
		$this->left_row_delimiter          = $left_row_delimiter;
		$this->quoted_left_row_delimiter   = preg_quote($left_row_delimiter);
	}
	
	function set_right_row_delimiter($right_row_delimiter){
		$this->right_row_delimiter         = $right_row_delimiter;
		$this->quoted_right_row_delimiter  = preg_quote($right_row_delimiter);
	}
	
	function set_field_separator($field_separator){
		$this->field_separator = $field_separator;
	}
	
	//--------------------------------------------------
	// testing if block meets defining conditions
	//--------------------------------------------------
	// one or more lines,
	//  beginninng with left separator and space,
	//  ending with space and right separator
	//--------------------------------------------------
	
	function test($input){
		$row_regex =
			$this->quoted_left_row_delimiter .
			' .+ ' .
			$this->quoted_right_row_delimiter;
		$table_regex =
			'/^' .
			$row_regex .
			'(\n' .
			$row_regex .
			')*$/';
		
		if(preg_match($table_regex, $input)){
			return true;
		}
	}
	
	//--------------------------------------------------
	// parsing block
	//--------------------------------------------------
	
	function parse($input){
		$normalized_input = $this->normalize($input);
		$output = $this->parse_table($normalized_input);
		
		return $output;
	}
	
	//--------------------------------------------------
	// normalizing table
	//--------------------------------------------------
	
	private function normalize($input){
		
		$search = array(
			'/^' . $this->quoted_left_row_delimiter . ' +/',
			'/ +' . $this->quoted_field_separator . ' +/',
			'/ +' . $this->quoted_right_row_delimiter . '$/',
		);
		
		$replace = array(
			$this->left_row_delimiter . ' ',
			' ' . $this->field_separator . ' ',
			' ' . $this->right_row_delimiter,
		);
		
		$output = preg_replace($search, $replace, $input);
		
		return $output;
	}
	
	//---------------------------
	// parsing table markup
	//---------------------------
	
	private function parse_table($table){
		$output = '';
		
		$output .= '<table>' . "\n";
		
		$regex =
			'/^' .
			$this->quoted_left_row_delimiter .
			' (.*) ' .
			$this->quoted_right_row_delimiter .
			'$/s';
		$table = preg_replace($regex, '$1', $table);
		
		$row_separator =
			' ' .
			$this->right_row_delimiter .
			"\n" .
			$this->left_row_delimiter .
			' ';
		$rows = explode($row_separator, $table);
		
		foreach($rows as $row){
			$output .= $this->parse_row($row);
		}
		
		$output .= '</table>' . "\n";
		
		return $output;
	}
	
	//--------------------------------------------------
	// parsing row markup
	//--------------------------------------------------
	
	private function parse_row($row_input){
		$output = '';
		
		$output .= '<tr>';
		
		$field_separator = ' ' . $this->field_separator . ' ';
		$fields = explode($field_separator, $row_input);
		
		foreach($fields as $field){
			$output .= $this->parse_field($field);
		}
		
		$output .= '</tr>' . "\n";
		
		return $output;
	}
	
	//--------------------------------------------------
	// parsing field markup
	//--------------------------------------------------
	
	private function parse_field($field_input){
		
		$field = $this->inline_parser
			? $this->inline_parser->parse($field_input)
			: $field_input;
		
		$output = '<td>' . $field . '</td>';
		
		return $output;
	}
	
}

?>
