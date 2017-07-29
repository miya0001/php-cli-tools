<?php

class testsMultibyte extends PHPUnit_Framework_TestCase {

	function test_mb_split() {
		$str = '1あいうえおか2きくｶけこ';
		$result = $this->mb_str_to_padded_array( $str, 10 );


		$this->assertSame( '1あいうえ ', $result[0] );
		$this->assertSame( 'おか2きくｶ', $result[1] );
		$this->assertSame( 'けこ      ', $result[2] );
	}

	function test_mb_split_02() {
		$str = '12345678901234567890123';
		$result = $this->mb_str_to_padded_array( $str, 10 );


		$this->assertSame( '1234567890', $result[0] );
		$this->assertSame( '1234567890', $result[1] );
		$this->assertSame( '123       ', $result[2] );
	}


	function mb_str_to_padded_array( $string, $width, $encode = 'UTF-8' ) {
		// Splitting the string with each char.
		$strlen = mb_strlen( $string );
		while ($strlen) {
			$array[] = mb_substr( $string, 0, 1, $encode );
			$string = mb_substr( $string, 1, $strlen, $encode );
			$strlen = mb_strlen( $string, $encode );
		}

		// Splitting the string with `$width` that is used for table row.
		$test = $text = '';
		$split = array();
		for ( $i = 0; $i < count( $array ); $i++ ) {
			$test .= $array[ $i ];
			if ( $width < mb_strwidth( $test, $encode ) ) {
				$split[] = $text;
				$test = $array[ $i ];
				$text = $array[ $i ];
			} else {
				$text .= $array[ $i ];
			}
		}

		$split[] = $text; // The last row will be added.

		// `str_pad()` is not working for multibyte so I developped a new function.
		$arr = array();
		foreach( $split as $str ) {
			$arr[] = $this->mb_str_pad( $str, $width, $encode );
		}

		return $arr;
	}

	function mb_str_pad( $string, $width, $encode = 'UTF-8' ) {
		while( $width > mb_strwidth( $string, $encode ) ) {
			$string .= ' ';
		}

		return $string;
	}
}
