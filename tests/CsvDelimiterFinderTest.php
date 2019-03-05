<?php

use MatrixPro\CsvDelimiterFinder\CsvDelimiterFinder;
use PHPUnit\Framework\TestCase;

class CsvDelimiterFinderTest extends TestCase
{
	public function test_it_can_detect_delimiters()
	{
		// Test file 1 should return a comma:
		
		$file = dirname(__FILE__).'/CsvTestFile1.csv';
		
		$this->assertFileExists($file);
		
		$handle = fopen($file, "r");
		$finder = new CsvDelimiterFinder($handle);
		$delimiter = $finder->findDelimiter();
		
		$this->assertSame(',', $delimiter);
		
		// Test file 2 should return a semicolon:
		
		$file = dirname(__FILE__).'/CsvTestFile2.csv';
		
		$this->assertFileExists($file);
		
		$handle = fopen($file, "r");
		$finder = new CsvDelimiterFinder($handle);
		$delimiter = $finder->findDelimiter();
		
		$this->assertSame(';', $delimiter);
	}
}