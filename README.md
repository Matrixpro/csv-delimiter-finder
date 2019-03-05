MatrixPro CSV Delimiter Finder
================================
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

Automatically detects the delimiter type in a CSV file. Compatible with large CSV datasets (tested on 4gb+ files).

Install via composer:

```
composer require matrixpro/csv-delimiter-finder
```

Usage
-----
Usage is simple: Simply pass a CSV file handle to the finder and use the findDelimiter() method. Will return FALSE if no valid delimiter is found.

```php
$handle = fopen('path/to/file.csv', "r");
$finder = new CsvDelimiterFinder($handle);
$delimiter = $finder->findDelimiter();
```

You may optionally override the default set of delimiters that it checks for by using the setDelimiters() method:

```php
$custom_delimiters = [',',';','|',"\t"];
$finder->setDelimiters($custom_delimiters);
```