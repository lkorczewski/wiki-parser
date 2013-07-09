<?php

namespace Wiki_Parser;

//============================================================================
// interface of block parser
//============================================================================

interface Block_Parser {
	
	function test($input);
	function parse($input);	
	
}

?>
