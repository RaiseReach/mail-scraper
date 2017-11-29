<?php
require_once("simple_html_dom.php"); 
require_once("safemysql.php"); 

$db = new SafeMySQL();
$jj = 0;
// for ($j=0; $j < 1000; $j++) {
// 	$count = $j*10;
// 	// // echo 'COUNT - '.$j."\n";
	
// 	$html = file_get_html("https://www.google.com.ua/search?q=%22%40tele2.se%22+%7C+%22%40telia.com%22+%7C+%22%40live.se%22+%7C+%22%40live.com%22+%7C+%22%40hotmail.se%22+%7C+%22%40hotmail.com%22+%7C+%22%40gmail.com%22+%7C+%22%40yahoo.se%22+%7C+%22%40spray.se%22+filetype:csv&num=100&dcr=0&ei=hib5WZT-NsKL6ATUn63YAg&start=".$count."&sa=N&filter=0&biw=1360&bih=659");
// 	// echo "https://www.google.com.ua/search?q=%22%40gmail.com%22+|+%22%40yahoo.com%22+|+%22%40hotmail.com%22+filetype:csv&dcr=0&ei=XSXkWcL6Mc6ja-CpjpgD&start=".$count."&sa=N&biw=1360&bih=659"."\n";
// 	foreach($html->find('h3.r a') as $element) {
// 		$a = explode('&', $element->href);
// 		$i = 0;
// 		while ($i < count($a)) {
// 			$b = explode('=', $a[$i]);
// 			if (htmlspecialchars(urldecode($b[0])) == '/url?q') {
// 				$jj++;
// 				echo $jj.' - '.htmlspecialchars(urldecode($b[1]))."\n";
// 				copy(htmlspecialchars(urldecode($b[1])), $jj.'.csv');

// 			}
// 			$i++;
// 		}
// 	}
// 	// unset($html);
// 	// sleep(2);



// 	// $handle = fopen($j.'.csv', "r");
// 	// if ($handle) {

// 	// 	$rows = 0;
// 	// 	$emails = 0;
// 	// 	while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
// 	// 		$rows++;
// 	// 		if(is_array($data)) {
// 	// 			foreach ($data as $key => $value) {
// 	// 				$pattern = "/^([A-Z|a-z|0-9](\.|_){0,1})+[A-Z|a-z|0-9]\@([A-Z|a-z|0-9])+((\.){0,1}[A-Z|a-z|0-9]){2}\.[a-z]{2,3}$/";
// 	// 				preg_match($pattern, $value, $matches);
// 	// 				if (!empty($matches)) {
// 	// 					$emails++;
// 	// 					// echo '    '.$value.'<br>';
// 	// 					$db->query("INSERT INTO emails SET email=?s",$value);
// 	// 				}
// 	// 			}
// 	// 		}
// 	// 		if ($rows > 30000 and $emails < 100) {
// 	// 			break;
// 	// 		}
// 	// 	}
// 	// 	echo $rows."\n";
// 	// } else {
// 	// 	echo "0\n";
// 	// }

// 	// fclose($handle);

// // 	$handle = fopen('1.csv', "rb");
// // 	$contents = fread($handle, filesize('1.csv'));
// // 	$pattern = "/^([A-Z|a-z|0-9](\.|_){0,1})+[A-Z|a-z|0-9]\@([A-Z|a-z|0-9])+((\.){0,1}[A-Z|a-z|0-9]){2}\.[a-z]{2,3}$/";
// // 	preg_match($pattern, $value, $matches);
// // 	print_r($matches);
// // 	fclose($handle);
// }
















$all_emails = 0;
for ($j=1; $j < 2130; $j++) {
	// $j = 36;
	// $handle = fopen($j.'.csv', "rb");
	// $contents = fread($handle, filesize($j.'.csv'));

	// $pattern = '/[a-z\d]+([\.\_]?[a-z\d]+)+@[a-z\d]+(\.[a-z]+)+/i';
	// preg_match_all($pattern, $contents, $matches);
	// foreach ($matches['0'] as $email) {
	// 	$db->query("INSERT INTO emails SET email=?s",$email);
	// }
	// fclose($handle);
	$flag = 0;
	$handle = fopen($j.'.csv', "r");
	if ($handle) {

		echo $j.' - ';
		$rows = 0;
		$emails = 0;
		while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
			$rows++;
			if(is_array($data)) {
				foreach ($data as $key => $value) {
					$pattern = "/[a-z\d]+([\.\_]?[a-z\d]+)+@[a-z\d]+(\.[a-z]+)+/";
					preg_match($pattern, $value, $matches);
					if (!empty($matches)) {
						$emails++;
						// echo '    '.$value.'<br>';

						try {
							$db->query("INSERT INTO emails SET email=?s",$matches['0']);
						} catch (Exception $e) {}
					}
				}
			}
			if ($rows > 30000 and $emails < 100) {
				$flag = 1;
				break;
			}
		}
		if ($flag) {
			fclose($handle);
			$handle = fopen($j.'.csv', "r");

			while (($data = fgetcsv($handle, 100000, ";")) !== FALSE) {
				$rows++;
				if(is_array($data)) {
					foreach ($data as $key => $value) {
						$pattern = "/[a-z\d]+([\.\_]?[a-z\d]+)+@[a-z\d]+(\.[a-z]+)+/";
						preg_match($pattern, $value, $matches);
						if (!empty($matches)) {
							$emails++;
							// echo '    '.$value.'<br>';
							try {
								$db->query("INSERT INTO emails SET email=?s",$matches['0']);
							} catch (Exception $e) {}
						}
					}
				}
				if ($rows > 30000 and $emails < 100) {
					break;
				}
			}

		}
		echo $rows."\n";
	} else {
		echo "0\n";
	}
	$all_emails = $all_emails + $emails;
	fclose($handle);
}


?>