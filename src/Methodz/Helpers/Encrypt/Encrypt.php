<?php

namespace Methodz\Helpers\Encrypt;

use Methodz\Helpers\File\File;

class Encrypt
{

	public static function encode(string $data): string
	{
		$res = "";

		echo "β=" . ord('β') . "\n";

		echo "β=" . chr(167) . "\n";

		//Tableau a 3 dimension

		//zyx

		$tables = [];
		for ($z = 0; $z <= 9; $z++) {
			$column = [];
			for ($y = 0; $y <= 9; $y++) {
				$row = [];
				for ($x = 0; $x <= 9; $x++) {
					$row[$x] = null;
					if (intval("$z$y$x") < 256) {
						$row[$x] = chr(intval("$z$y$x"));
					}
				}
				$column[$y] = $row;
			}
			$tables[$z] = $column;
		}
		foreach ($tables as $z => $table) {
			echo "z => $z \n";
			foreach ($table as $y => $row) {
				$r = "$y => [";
				foreach ($row as $x => $cell) {
					$r .= "$x => " . ($cell ?? "null") . ", ";
				}
				$r = trim($r, ', ') . "]";
				echo "$r\n";
			}
			echo "\n";
			echo "\n";
		}

		//File::put(__DIR__, "test.txt", $res);
		//File::put(__DIR__, "test.json", json_encode($tables));

		echo "\n\n";
		return $res;
	}
}
