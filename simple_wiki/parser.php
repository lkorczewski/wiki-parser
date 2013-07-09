<?php

namespace Wiki_Parser;

require_once __DIR__ . '/../wiki_parser.php';

require_once __DIR__ . '/inline_parser.php';

require_once __DIR__ . '/header_parser.php';
require_once __DIR__ . '/table_parser.php';
//require_once __DIR__ . '/list_parser.php';
require_once __DIR__ . '/paragraph_parser.php';

//============================================================================
// Simple Wiki parser, an implementation of wiki parser
//============================================================================

class Simple_Wiki_Parser extends Wiki_Parser {
	
	function __construct(){
		
		$inline_parser = new Simple_Wiki_Inline_Parser();
		$inline_parser->set_italic_delimiters('//', '//');
		$inline_parser->set_bold_delimiters('**', '**');
		$inline_parser->set_undeline_delimiters('__', '__');
		$inline_parser->set_strikethrough_delimiters('--', '--');
		$inline_parser->set_link_delimiters('[[', '|', ']]');
		
		$header_1_parser = new Simple_Wiki_Header_Parser();
		$header_1_parser->set_marker('!');
		$header_1_parser->set_level(1);
		$header_1_parser->set_inline_parser($inline_parser);
		
		$header_2_parser = new Simple_Wiki_Header_Parser();
		$header_2_parser->set_marker('!!');
		$header_2_parser->set_level(2);
		$header_2_parser->set_inline_parser($inline_parser);
		
		$header_3_parser = new Simple_Wiki_Header_Parser();
		$header_3_parser->set_marker('!!!');
		$header_3_parser->set_level(3);
		$header_3_parser->set_inline_parser($inline_parser);
		
		$header_4_parser = new Simple_Wiki_Header_Parser();
		$header_4_parser->set_marker('!!!!');
		$header_4_parser->set_level(4);
		$header_4_parser->set_inline_parser($inline_parser);
		
		$table_parser = new Simple_Wiki_Table_Parser();
		$table_parser->set_left_row_delimiter('|');
		$table_parser->set_right_row_delimiter('|');
		$table_parser->set_field_separator('|');
		
		/*
		$list_parser = new Simple_Wiki_List_Parser();
		$list_parser->set_unordered_list_marker('*');
		$list_parser->set_ordered_list_marker('#');
		*/
		
		$paragraph_parser = new Simple_Wiki_Paragraph_Parser();
		$paragraph_parser->set_inline_parser($inline_parser);
		
		$this->block_parsers = array(
			$header_1_parser,
			$header_2_parser,
			$header_3_parser,
			$header_4_parser,
			$table_parser,
			//$list_parser,
			$paragraph_parser,
		);
		
	}
	
}

?>
