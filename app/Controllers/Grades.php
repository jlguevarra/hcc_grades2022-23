<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GradesModel;

class Grades extends BaseController
{
    public function index()
    {
        $data = [];
        $request = service('request');

        if ($request->getMethod() === 'post') {
            // Trim whitespace to prevent lookup failures
            $inputID = trim($request->getPost('student_number'));
            $semesterBtn = $request->getPost('semester_btn'); 
            
            // Debug: Log the received values
            // log_message('info', "Student No: {$inputID}, Semester Btn: {$semesterBtn}");
            
            // Map button value to the exact string stored in the 'semester' column
            $semKey = '';
            if ($semesterBtn === '1st Semester') {
                $semKey = '1ST SEM';
            } elseif ($semesterBtn === '2nd Semester') {
                $semKey = '2ND SEM';
            } else {
                $semKey = $semesterBtn; // fallback
            }

            $model = new GradesModel();
            
            // First, check if student exists
            $studentInfo = $model->getStudentInfo($inputID);

            if ($studentInfo) {
                $data['student_name'] = $studentInfo['FullName'];
                $data['student_course'] = $studentInfo['Course'] . ' - ' . $studentInfo['Level'];
                
                // Debug: Log student info
                // log_message('info', "Student found: {$studentInfo['FullName']}, Course: {$studentInfo['Course']}");
                
                // Fetch grades using trimmed ID and mapped Semester key
                $results = $model->getStudentGrades($inputID, $semKey);
                
                // Debug: Log results count
                // log_message('info', "Found " . count($results) . " grade records for semester: {$semKey}");
                
                // Logic to handle cases where teacher is not in the users table
                foreach ($results as &$row) {
                    if (empty($row['teacher_name'])) {
                        $row['teacher_name'] = ($row['teacher_raw'] == '0' || empty($row['teacher_raw'])) ? 'TBA' : $row['teacher_raw']; 
                    }
                }
                $data['results'] = $results;

            } else {
                $data['error'] = "Student Number '$inputID' not found in the student records.";
                $data['results'] = [];
            }

            $data['selected_sem'] = $semesterBtn;
            $data['student_number'] = $inputID;
        }

        return view('grades/portal', $data);
    }
}