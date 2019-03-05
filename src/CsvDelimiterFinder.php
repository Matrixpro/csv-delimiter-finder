<?php

namespace MatrixPro\CsvDelimiterFinder;

use MatrixPro\CsvDelimiterFinder\Exceptions\FileNotFoundException;
use MatrixPro\CsvDelimiterFinder\Exceptions\InvalidContentException;
use MatrixPro\CsvDelimiterFinder\Exceptions\InvalidArgsException;

/**
 * Class for csv delimiter finder.
 */

class CsvDelimiterFinder {
	
	private $delimiters;
	private $handle;
	private $accuracy;
	private $sample_row_count;
	
	/**
	 * Sets up some defaults
	 *
	 * @param      Resource  $handle  The CSV file handle
	 */
	public function __construct($handle)
	{
		$this->handle = $handle;
		$this->setDelimiters([',',';','|',"\t"]);
		$this->setSampleRowCount(20);
	}
	
	/**
	 * Sets the sample row count.
	 *
	 * @param      Integer $num    The number of rows to sample
	 */
	public function setSampleRowCount($num=0)
	{
		$this->sample_row_count = $num;
	}
	
	/**
	 * Finds the delimiter
	 *
	 * @return     String ( returns the delimiter found or FALSE )
	 */
	public function findDelimiter()
	{
		$this->setAccuracyArray();
		
		foreach ($this->delimiters as $key => $delimiter) {
			
			$current_row = 0;
			$columns_found = 0;
			
		   while ((($data = fgetcsv($this->handle, 0, $delimiter)) !== false) && ($current_row < $this->sample_row_count)) {
				
				if (!$columns_found) {
					// set initial count of columns
					$columns_found = count($data);
				} elseif ($columns_found != count($data) || $columns_found <= 1) {
					// Column count should be consistant, if not - break and try next delimiter
					$this->accuracy[$key]['accuracy'] = 0;
					break; 
				}
				
				$this->accuracy[$key]['accuracy'] += count($data);
				
				$current_row++;
			}
		}
		
		array_multisort(array_column($this->accuracy, 'accuracy'), SORT_DESC, $this->accuracy);
		
		return ($this->accuracy[0]['accuracy'] > 1) ? $this->accuracy[0]['delimiter'] : FALSE;
	}
	
	/**
	 * Sets the accuracy array.
	 */
	private function setAccuracyArray()
	{
		foreach ($this->delimiters as $key => $delimiter) {
			$this->accuracy[$key] = [
				'delimiter' => $delimiter,
				'accuracy' => 0
			];
		}
	}
	
	/**
	 * Sets the delimiters.
	 *
	 * @param      Array	 $vals   The delimiter values to set
	 *
	 * @throws     \MatrixPro\CsvDelimiterFinder\Exceptions\InvalidArgsException  if $vals is not an array
	 */
	public function setDelimiters($vals)
	{
		if (!is_array($vals)) {
			throw new \InvalidArgsException('Delimiters must be an array!');
		}
		
		$this->delimiters = $vals;
	}
	
}