<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class SimpleGrades extends BaseController
{
    public function index()
    {
        echo "<h1>Holy Cross College - Simple Grade Viewer</h1>";
        
        if ($_POST) {
            $studentNo = $_POST['student_number'];
            $semesterBtn = $_POST['semester_btn'];
            $semester = ($semesterBtn === '1st Semester') ? '1ST SEM' : '2ND SEM';
            
            echo "<h2>Looking for: $studentNo - $semester</h2>";
            
            $db = \Config\Database::connect();
            
            // 1. Check student
            $student = $db->query("
                SELECT StudentNo, FullName, Course, Level 
                FROM students_college 
                WHERE StudentNo = '$studentNo'
            ")->getRowArray();
            
            if (!$student) {
                echo "<p style='color: red;'>Student not found!</p>";
            } else {
                echo "<h3>Student: {$student['FullName']} ({$student['Course']} - {$student['Level']})</h3>";
                
                // 2. Check grades
                $grades = $db->query("
                    SELECT s.subjectCode, s.subjectDesc, g.Teacher, 
                           g.Prelim, g.Midterm, g.Finals, g.Grade, 
                           g.Equivalent, g.Remarks
                    FROM student_subject_college g
                    LEFT JOIN subject_college s ON s.subjectID = g.subjectID
                    WHERE g.studentnumber = '$studentNo' 
                    AND g.sy = '2022-2023' 
                    AND g.semester = '$semester'
                    ORDER BY s.subjectCode
                ")->getResultArray();
                
                if (empty($grades)) {
                    echo "<p style='color: orange;'>No grades found for $semester 2022-2023</p>";
                    
                    // Show what semesters ARE available
                    $available = $db->query("
                        SELECT DISTINCT semester, sy 
                        FROM student_subject_college 
                        WHERE studentnumber = '$studentNo'
                    ")->getResultArray();
                    
                    if ($available) {
                        echo "<p>Available semesters:</p>";
                        echo "<ul>";
                        foreach ($available as $avail) {
                            echo "<li>{$avail['semester']} - {$avail['sy']}</li>";
                        }
                        echo "</ul>";
                    }
                } else {
                    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
                    echo "<tr style='background: #4CAF50; color: white;'>
                            <th>Subject</th><th>Teacher</th><th>Prelim</th><th>Midterm</th>
                            <th>Finals</th><th>Grade</th><th>Equiv</th><th>Remarks</th>
                          </tr>";
                    
                    foreach ($grades as $grade) {
                        $remarks = strtoupper($grade['Remarks']);
                        $color = match($remarks) {
                            'PASSED' => 'green',
                            'FAILED' => 'red',
                            'INC' => 'orange',
                            default => 'gray'
                        };
                        
                        echo "<tr>";
                        echo "<td><b>{$grade['subjectCode']}</b><br>{$grade['subjectDesc']}</td>";
                        echo "<td>" . ($grade['Teacher'] == '0' ? 'TBA' : $grade['Teacher']) . "</td>";
                        echo "<td>{$grade['Prelim']}</td>";
                        echo "<td>{$grade['Midterm']}</td>";
                        echo "<td>{$grade['Finals']}</td>";
                        echo "<td style='font-weight: bold; color: blue;'>{$grade['Grade']}</td>";
                        echo "<td>{$grade['Equivalent']}</td>";
                        echo "<td style='color: $color; font-weight: bold;'>$remarks</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "<p>Total subjects: " . count($grades) . "</p>";
                }
            }
            
            echo "<hr>";
        }
        
        // Show form
        echo '<form method="POST" style="background: #f0f0f0; padding: 20px;">
                <h3>Enter Student Number:</h3>
                <input type="text" name="student_number" value="22-0013484" required>
                <br><br>
                <button type="submit" name="semester_btn" value="1st Semester">1st Semester</button>
                <button type="submit" name="semester_btn" value="2nd Semester">2nd Semester</button>
              </form>';
    }
}