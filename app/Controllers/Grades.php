<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Grades extends BaseController
{
    public function index()
    {
        // DEBUG: Force display errors
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $data = [];
        
        // Check if form was submitted
        if (!empty($_POST)) {
            $studentNo = trim($_POST['student_number'] ?? '');
            $semesterBtn = $_POST['semester_btn'] ?? '';
            
            // Map button to database value
            $semester = ($semesterBtn === '1st Semester') ? '1ST SEM' : '2ND SEM';
            
            // Direct database connection - SIMPLE AND GUARANTEED
            $db = \Config\Database::connect();
            
            // 1. Get student info - SIMPLE QUERY
            $studentQuery = $db->query("
                SELECT StudentNo, FullName, Course, Level 
                FROM students_college 
                WHERE StudentNo = '" . $db->escapeString($studentNo) . "'
            ");
            
            $student = $studentQuery->getRowArray();
            
            if ($student) {
                $data['student'] = $student;
                $data['student_number'] = $studentNo;
                $data['selected_sem'] = $semesterBtn;
                $data['school_year'] = '2022-2023';
                
                // 2. Get grades - SIMPLE QUERY THAT WE KNOW WORKS
                $gradesQuery = $db->query("
                    SELECT 
                        s.subjectCode,
                        s.subjectDesc,
                        g.Teacher,
                        g.Prelim,
                        g.Midterm,
                        g.Finals,
                        g.Grade,
                        g.Equivalent,
                        g.Remarks
                    FROM student_subject_college g
                    LEFT JOIN subject_college s ON s.subjectID = g.subjectID
                    WHERE g.studentnumber = '" . $db->escapeString($studentNo) . "'
                    AND g.sy = '2022-2023'
                    AND g.semester = '" . $db->escapeString($semester) . "'
                    ORDER BY s.subjectCode ASC
                ");
                
                $grades = $gradesQuery->getResultArray();
                $data['grades'] = $grades;
                
                // DEBUG: Force show data
                echo "<!-- DEBUG: Student found, Grades count: " . count($grades) . " -->";
                
            } else {
                $data['error'] = "Student number '$studentNo' not found.";
            }
            
            $data['form_submitted'] = true;
        }
        
        // ALWAYS pass these to view
        $data['form_submitted'] = $data['form_submitted'] ?? false;
        $data['student'] = $data['student'] ?? null;
        $data['grades'] = $data['grades'] ?? [];
        $data['student_number'] = $data['student_number'] ?? '';
        $data['selected_sem'] = $data['selected_sem'] ?? '';
        
        return view('grades_view', $data);
    }
}