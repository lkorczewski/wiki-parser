<?php

namespace Wiki_Parser;

//============================================================================
// abstraction of wiki parser
// needs to be implemented with particular syntax rules as:
//   - $block_parsers -- array of implementations of Block_Parsers
//============================================================================

abstract class Wiki_Parser {
	
	protected $block_parsers;
	//protected $inline_parser;
	
	//----------------------------------------
	// parsing wiki-syntax
	//----------------------------------------
	
	function parse($input){
		$normalized_input = $this->normalize($input);
		$blocks = $this->split($normalized_input);
		$output = $this->parse_blocks($blocks);
		return $output;
	}
	
	//----------------------------------------
	// normalizing
	//----------------------------------------
	
	protected function normalize($input){
		$output = $input;
		return $output;
	}
	
	//----------------------------------------
	// splitting into blocks
	//----------------------------------------
	
	protected function split($input){
		$blocks = explode("\n\n", $input);
		return $blocks;
	}
	
	//---------------------------------------
	// parser itself
	//---------------------------------------
	
	protected function parse_blocks($blocks){
		$output = '';
		foreach($blocks as $block){
			$block_output = '';
			foreach($this->block_parsers as $block_parser){
				if($block_parser->test($block)){
					$block_output = $block_parser->parse($block);
					break;
				}
			}
			$output .= $block_output;
		}
		return $output;
	}
	
}

?>
