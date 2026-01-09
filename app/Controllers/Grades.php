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
            $inputID = $request->getPost('student_number');
            $semesterBtn = $request->getPost('semester_btn'); 
            
            // Map button text to Database value (Assuming '1ST SEM' based on previous data)
            $semKey = ($semesterBtn === '1st Semester') ? '1ST SEM' : '2ND SEM';

            $model = new GradesModel();

            // 1. Find the student in 'students_college'
            $studentInfo = $model->getStudentInfo($inputID);

            if ($studentInfo) {
                $data['student_name'] = $studentInfo['FullName'];
                $data['student_course'] = $studentInfo['Course'] . ' - ' . $studentInfo['Level'];
                
                // 2. Use 'admissionID' to get grades
                $results = $model->getStudentGrades($inputID, $semKey);
                
                // Clean up Teacher Name (Use the joined name if found, otherwise use the raw ID/Name)
                foreach ($results as &$row) {
                    if (empty($row['teacher_name'])) {
                        $row['teacher_name'] = $row['teacher_raw']; 
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