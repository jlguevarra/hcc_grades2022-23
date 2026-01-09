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
            
            // Map button value to the exact string stored in the 'semester' column
            $semKey = ($semesterBtn === '1st Semester') ? '1ST SEM' : '2ND SEM';

            $model = new GradesModel();
            $studentInfo = $model->getStudentInfo($inputID);

            if ($studentInfo) {
                $data['student_name'] = $studentInfo['FullName'];
                $data['student_course'] = $studentInfo['Course'] . ' - ' . $studentInfo['Level'];
                
                // Fetch grades using trimmed ID and mapped Semester key
                $results = $model->getStudentGrades($inputID, $semKey);
                
                // Logic to handle cases where teacher is not in the users table
                foreach ($results as &$row) {
                    if (empty($row['teacher_name'])) {
                        $row['teacher_name'] = ($row['teacher_raw'] == '0') ? 'TBA' : $row['teacher_raw']; 
                    }
                }
                $data['results'] = $results;

            } else {
                $data['error'] = "Student Number '$inputID' not found.";
                $data['results'] = [];
            }

            $data['selected_sem'] = $semesterBtn;
            $data['student_number'] = $inputID;
        }

        return view('grades/portal', $data);
    }
}