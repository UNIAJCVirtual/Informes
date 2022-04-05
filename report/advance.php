<?php
function headerDetail($items)
{
	$result = "<table border='1' cellspacing='1' cellpadding='0'>
				<tr  class='tr'>";


	if ($items->num_rows > 0) {
		foreach ($items as $recordItems) {
			$result .= "<td> Nota :" . $recordItems["name"] . "</td>
				<td>Retroalimentaci&oacute;n :" .  $recordItems["name"] . "</td>";
		}
	} else {
		$result .= "<td>Sin actividades</td>";
	}
	return $result . "</tr>";
}

function advanceReport($category, $program, $semester, $type_report)
{

	include("../database/reportRequest.php");
	$result = "
			<table border='1' cellspacing='1' cellpadding='0'>
			<tr  class='tr'>
				<td>ID USUARIO</td>
				<td>NOMBRE</td>
				<td>EMAIL</td>
				<td>ID CURSO</td>
				<td>CURSO</td>
				<td>ID CAT</td>
				<td>PROGRAMA</td>
				<td>SEMESTRE</td>
				<td>Detalle</td>
				<td>% de CUMPLIMIENTO</td>
			</tr>";
	echo $result;
	$teachesResult = Teachers(implode(",", $program));

	if ($teachesResult->num_rows > 0) {
		$color_rows = 1;

		foreach ($teachesResult as $value) {
			$cumple = 0;
			$noCumple = 0;
			$semester = NameCategory($value['cat']);
			$program = Program($semester["parent"]);
			$semester1 = $semester["name"];
			$coursesResult = Courses($value['courseid'], $value['userid'], $type_report);
			foreach ($coursesResult as $valueC) {
				if ($color_rows == 0) {
					$class_row = "td1";
					$color_rows = 1;
				} else {
					$class_row = "td2";
					$color_rows = 0;
				}

				$mails[] = $value["mdl_user_email"];
				$course[] = $valueC["mdl_course_fullname"];
				$fila = "
						<tr class='" . $class_row . "'>
							<td>" . $value["userid"] . "</td>
							<td>" . $value["mdl_user_firstname"] . " " . $value["mdl_user_lastname"] . "</td>
							<td>" . $value["mdl_user_email"] . "</td>
							<td>" . $valueC["id"] . "</td>
							<td>" . $valueC["mdl_course_fullname"] . "</td>
							<td>" . $valueC["category"] . "</td>
							<td>$program</td>
							<td>$semester1</td>";
					$recordItem = ItemCourse($value['courseid'], strtoupper($type_report));
					$details = headerDetail($recordItem) . "<tr>";
			

					foreach ($recordItem as $item) {
						if ($item["itemmodule"] == "forum") {

							//score
							$score = ScoreItem($item["id"]);
							($score == "CUMPLE") ? $cumple++ : $noCumple++;
							$details .= "<td>" . $score . "</td>";
							// $details.="<td title='".$forum["name"]."'>".$score."</td>";
							//feedback
							$resultFeedback = FeedbackForum1($valueC['id'], $item["iteminstance"]);
							if ($resultFeedback->num_rows > 0) {
								$feed1 = $resultFeedback->fetch_assoc();
								$resultFeedback = FeedbackForum2($feed1['id'], $value["userid"]);
								$feed = $resultFeedback->fetch_assoc();
								if (count(explode(" ", $feed["message"])) > 2) {
									$details .= "<td>CUMPLE</td>";
									$cumple++;
								} else {
									$details .= "<td>NO CUMPLE</td>";
									$noCumple++;
								}
							} else {
								$details .= "<td>NO CUMPLE</td>";
								$noCumple++;
							}
						} elseif ($item["itemmodule"] == "assign") {
							//score
							$score = ScoreItem($item["id"]);
							($score == "CUMPLE") ? $cumple++ : $noCumple++;
							// $details.="<td title='".$act["name"]."'>".$score."</td>";
							$details .= "<td>" . $score . "</td>";
							//feedback
							$resultFeedback = FeedbackActivity($item["iteminstance"]);
							($resultFeedback == "CUMPLE") ? $cumple++ : $noCumple++;
							$details .= "<td>" . $resultFeedback . "</td>";
						} elseif ($item["itemmodule"] == "quiz") {
							//score
							//sin programar
							$score = $item["id"];
							$details .= "<td>CUMPLE</td>";
							$cumple++;
							$details .= "<td>NO APLICA</td>";
						}
					}
					$details .= "</tr></table>";
					$total = (($cumple + $noCumple) == 0) ? "Sin actividades" : ($cumple + $noCumple);
					$per = ($total == "Sin actividades") ? "Sin actividades" : round(((100 / $total) * $cumple), 2);
					$percentage[] = $per;
					echo $fila . "<td>" . $details . "</td><td>" . $per . "%</td></tr>";
					$result .= $fila . "<td>" . $details . "</td><td>" . $per . "%</td></tr>";
				
			}
		}
		echo "</table>";
		$result .= "</table>";
	}

	$additional = array('mails' => $mails, 'courses' => $course, 'percentage' => $percentage);
	$result = array("table" => $result, "additional" => $additional);
	return $result;
}
