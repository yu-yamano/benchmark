#!/usr/local/opt/php70/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: usr0301564
 * Date: 2017/09/20
 * Time: 9:10
 */
ini_set('memory_limit', '2048M');
$time_start = microtime(true);
$readFile = '/Users/usr0301564/gmo-am/access_log_ad/access_log.20170915_09_ad01';
$file = 'test.csv';
if ($argv[1]) {
    $file = "test{$argv[1]}.csv";
}
$csvFile = "/Users/usr0301564/gmo-am/php/{$file}";
createCsv($readFile, $csvFile);//}
$time = (microtime(true) - $time_start);
echo "{$time} 秒" . "\n";

function createCsv($readFile, $csvFile)
{
    $accesslog = fopen($readFile, 'r');
    $csv = fopen($csvFile, "w");
    if (!$csv) {
        echo 'file open error';
        exit;
    }

    $num = 0;
    while (!feof($accesslog)) {
        $line = str_getcsv(fgets($accesslog), ' ');

        $first = $line[0];
        $last = $line[count($line) - 1];
        $line[0] = $last;
        $line[count($line) - 1] = $first;

        // output csv
        fputcsv($csv, $line);
        $num++;
    }
    fclose($csv);
    fclose($accesslog);
}