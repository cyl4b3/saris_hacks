<?php
require_once('../Connections/sessioncontrol.php');
# include the header
include('lecturerMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Examination';
	$szTitle = 'Examination';
	$szSubSection = '';
	//$additionalStyleSheet = './general.css';
	include("lecturerheader.php");
	
	$query_AcademicYear = "SELECT AYear FROM academicyear  ORDER BY AYear DESC LIMIT 0, 4";
$AcademicYear = mysql_query($query_AcademicYear, $zalongwa) or die(mysql_error());

$sqlayear = "SELECT AYear, Semister_status FROM academicyear WHERE Status = 1";
$resultayear=mysql_query($sqlayear);
while ($line = mysql_fetch_array($resultayear, MYSQL_ASSOC)) 
					{
						$ayear = $line["AYear"];
						$semester = $line["Semister_status"];
					}
	
	if(isset($_POST["exam"]))
	{
		//die(here);
		$sqlayear = "SELECT AYear, Semister_status FROM academicyear WHERE Status = 1";
$resultayear=mysql_query($sqlayear);
while ($line = mysql_fetch_array($resultayear, MYSQL_ASSOC)) 
					{
						$ayear = $line["AYear"];
						$semester = $line["Semister_status"];
					}
		$program = $_POST['programme'];
		$year = $_POST['cohot'];
		
		if ($program == '1011')
		{
                  $sql = "SELECT student.Id,
				   student.Name,
				   student.RegNo,
				   student.Sex
				FROM student
				WHERE 
  					 (
						(student.EntryYear='$year') AND 
						((student.ProgrammeofStudy = '1011') || (student.ProgrammeofStudy = '1012')  || (student.ProgrammeofStudy = '1013') || (student.ProgrammeofStudy = '1014') || (student.ProgrammeofStudy = '1015')) 
						AND
						(student.ProgrammeofStudy <> '10103')
   					 ) ORDER BY student.`ParentOccupation` , student.`Address` , student.`village` , student.`DBirth`";
		
		}
		else
		{
		   
		   $sql = "SELECT student.Id,
				   student.Name,
				   student.RegNo,
				   student.Sex
				FROM student
				WHERE 
  					 (
						(student.EntryYear='$year') AND 
						(student.ProgrammeofStudy = '$program') AND
						(student.ProgrammeofStudy <> '10103')
   					 ) ORDER BY student.`ParentOccupation` , student.`Address` , student.`village` , student.`DBirth`";
		}
		
					
 			$resultb=mysql_query($sql);
			$sql3b = "SELECT ExamNumber FROM Exam_Numbers WHERE AYear = '$ayear' and Semester = '$semester' and YearEntry = '$year'";
			$result2b=mysql_query($sql3b);
			$checkexamnumbers = mysql_num_rows($result2b);
			//die($checkexamnumbers);
			if($checkexamnumbers == 0)
			{
				$num1 = 10;
				$num2 = 200;
				$number = rand($num1,$num2);
					while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
					{
						$name = $line["Name"];
						$regno = $line["RegNo"];
						$gender = $line["Sex"];
						
						
						$examnumber = 'KCN'.$year.'/'.$number;
						$sql3 = "SELECT ExamNumber FROM Exam_Numbers WHERE ExamNumber = '$examnumber'";
						$result2=mysql_query($sql3);
						$rows2 = mysql_num_rows($result2);
						
						
							$sql2 = "INSERT INTO Exam_Numbers(AYear, Semester, YearEntry, RegNo, ExamNumber) VALUES('$ayear', '$semester', '$year', '$regno', '$examnumber')";
							mysql_query($sql2);
							
							
							$number ++;
							
						}
						echo "NUMBERS GENERATED PLEASE CLICK VIEW";
						
						
						
					
					
			}
			else
			{
				
				echo "EXAM NUMBERS ALREADY GENERATED";	
				
				
			}
							
						//die($sql);
		
		
		
	}
	else if(isset($_POST['examview']))
	{
		
		
		$program = $_POST['programme'];
		$year = $_POST['cohot'];
		 echo '<meta http-equiv = "refresh" content ="0; 
				 url =excel.php?program='.$program.'&year='.$year.'">';
		
		
		
	}
	
	
	
?>
<br> 
<table>
<form action="examnumber.php" method="post">
<tr><td>Programme:</td>
<td colspan="4" ><select name="programme" id="select3">
		  <option value="0">--------------------------------</option>
                    <option value="1001">Bachelor of Science in Nursing and Midwifery</option>
                      <option value="1007">Bachelor of Science in Nursing (Post Basic)</option>
			<option value="1005">Bachelor of Science in Nursing (Post Basic) yr1</option>
			<option value="1011">Bachelor of Science Direct entry(Generic) Yr 1</option>
<!---<option value="1012">Bachelor of Science in Child Health Nursing(Generic) Yr 1</option>
			<option value="1013">Bachelor of Science in Community Health Nursing(Generic) Yr 1</option>
                       <option value="1014">Bachelor of Science in Mental Health Nursing(Generic) Yr 1</option>
			<option value="1015">Bachelor of Science in Midwifery(Generic) Yr 1</option> -->
                        <option value="1003">University Certificate in Midwifery</option>
</select></td>
</tr>
<td nowrap><div align="right">Year: </div></td>
          <td colspan="4" ><select name="cohot" id="select2">
		  <option value="0">--------------------------------</option>
            <?php
       $showyear = 0;
do {  
if($showyear <> 0)
{

?>
            <option value="<?php echo $row_AcademicYear['AYear']?>"><?php
			
			
			
			
			 echo "Year ".$showyear; 
			
			  
			 ?></option>
            <?php
}
$showyear +=1;
} while ($row_AcademicYear = mysql_fetch_assoc($AcademicYear));
  $rows = mysql_num_rows($AcademicYear);
  if($rows > 0) {
      mysql_data_seek($AcademicYear, 0);
	  $row_AcademicYear = mysql_fetch_assoc($AcademicYear);
  }
?>
          </select></td>
     <tr><td><input type="submit" name="exam" value="Generate"></td><td><input type="submit" name="examview" value="View Exam Numbers"></td></tr>
</form>
</table>
<?php

	# include the footer
	include("../footer/footer.php");
?>