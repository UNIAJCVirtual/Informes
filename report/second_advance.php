<?php
	function headerDetail($forums, $activities, $meetings){
		$result = "<table border='1' cellspacing='1' cellpadding='0'>
				<tr  class='tr'>";
		if ($forums > 0) {
			for ($i=1; $i <= $forums; $i++) { 
				$result.="<td>Nota  foro$i</td>
				<td>Retroalimentaci&oacute;n  foro$i</td>";
			}
		}else{
			$result.="<td>FOROS</td>";
		}
		if ($activities > 0) {
			for ($i=1; $i <= $activities; $i++) { 
				$result.="<td>Nota  Act$i</td>
				<td>Retroalimentaci&oacute;n  Act$i</td>";
			}
		}else{
			$result.="<td>ACTIVIDADES</td>";
		}
		if ($meetings > 0) {
			for ($i=1; $i <= $meetings; $i++) { 
				$result.="<td>Encuentro Presencial$i</td>";
			}
		}else{
			$result.="<td>ENCUENTROS</td>";
		}
		return $result."</tr>";
	}
	function secondAdvanceReport($category,$program,$semester){
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
		$teachesResult = Teachers(implode(",",$program));
		if($teachesResult->num_rows>0){
			$color_rows=1;
			foreach($teachesResult as $value){	
				$cumple = 0;
				$no_cumple = 0;
				$semester = NameCategory($value['cat']);
				$program = Program($semester["parent"]);
				$semester1 = $semester["name"];
				$coursesResult = Courses($value['courseid'],$value['userid'],"segundo avance");
				foreach($coursesResult as $valueC){				
					if ($color_rows == 0) {
						$class_row = "td1";
						$color_rows = 1;
					}else{
						$class_row = "td2";
						$color_rows = 0;
					}
					$fila = "
						<tr class='".$class_row."'>
							<td>".$value["userid"]."</td>
							<td>".$value["mdl_user_firstname"]." ".$value["mdl_user_lastname"]."</td>
							<td>".$value["mdl_user_email"]."</td>
							<td>".$valueC["id"]."</td>
							<td>".$valueC["mdl_course_fullname"]."</td>
							<td>".$valueC["category"]."</td>
							<td>$program</td>
							<td>$semester1</td>";
					$recordForums = ItemCourse($value['courseid'],"PRIMER AVANCE","foro");
					$recordActivities = ItemCourse($value['courseid'],"PRIMER AVANCE","taller");
					$recordMeetings = ItemCourse($value['courseid'],"PRIMER AVANCE","encuentro");
					$details = headerDetail( $recordForums->num_rows, $recordActivities->num_rows, $recordMeetings->num_rows )."<tr>";
					if ($recordForums->num_rows > 0) {
						foreach($recordForums as $forum){
							//score
							$score = ScoreItem($forum["id"]);
							($score == "CUMPLE")?$cumple++:$no_cumple++;
							$details.="<td>".$score."</td>";
							// $details.="<td title='".$forum["name"]."'>".$score."</td>";
							//feedback
							$resultFeedback = FeedbackForum1($valueC['id'], $forum["iteminstance"]);
							if ($resultFeedback->num_rows>0) {
								$feed1= $resultFeedback->fetch_assoc(); 								
								$resultFeedback =FeedbackForum2($feed1['id'], $value["userid"]);
								$feed= $resultFeedback->fetch_assoc();
								if (count(explode(" ",$feed["message"]))>2 ) {
									$details.="<td>CUMPLE</td>";	
									$cumple++;
								}else{
									$details.="<td>NO CUMPLE</td>";
									$no_cumple++;
								}
							}else{
								$details.="<td>NO CUMPLE</td>";
								$no_cumple++;
							}
						}
					}else{
						$details.="<td>SIN FOROS</td>";
					}
					if ($recordActivities->num_rows > 0) {
						foreach($recordActivities as $act){
							//score
							$score = ScoreItem($act["id"]);
							($score == "CUMPLE")?$cumple++:$no_cumple++;
							// $details.="<td title='".$act["name"]."'>".$score."</td>";
							$details.="<td>".$score."</td>";
							//feedback
							$resultFeedback = FeedbackActivity($act["iteminstance"]);
							($resultFeedback == "CUMPLE")?$cumple++:$no_cumple++;
							$details.="<td>".$resultFeedback."</td>";
						}
					}else{
						$details.="<td>SIN ACTIVIDADES</td>";
					}
					if ($recordMeetings->num_rows > 0) {
						foreach($recordMeetings as $meeting){
							$score = ScoreItem($meeting["id"]);
							($score == "CUMPLE")?$cumple++:$no_cumple++;
							// $details.="<td title='".$meeting["name"]."'>".$score."</td>";
							$details.="<td>".$score."</td>";
						}
					}else{
						$details.="<td>SIN ENCUENTROS</td>";
					}
					$details.="</tr></table>";
					$total = (($cumple+$no_cumple) == 0)?"Sin datos":($cumple+$no_cumple);
					$porcentaje = ($total == "Sin datos")?"Sin datos":round(((100/$total)*$cumple),2);
					echo $fila."<td>".$details."</td><td>".$porcentaje."%</td></tr>";
					$result .= $fila."<td>".$details."</td><td>".$porcentaje."%</td></tr>";;
				}
			}
			echo "</table>";
			$result.= "</table>";
		}
		return $result;
	}
?>