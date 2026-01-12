<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Grades extends BaseController
{
    public function index()
    {
        $data = [];
        
        // Check if form was submitted
        if (!empty($_POST)) {
            $studentNo = trim($_POST['student_number'] ?? '');
            $semesterBtn = $_POST['semester_btn'] ?? '';
            
            // Map button to database value
            $semester = ($semesterBtn === '1st Semester') ? '1ST SEM' : '2ND SEM';
            
            // Direct database connection
            $db = \Config\Database::connect();
            
            // 1. Get student info WITH COURSE NAME from courses table
            $studentQuery = $db->query("
                SELECT 
                    sc.StudentNo, 
                    sc.FullName, 
                    sc.Course as course_id,
                    sc.Level,
                    COALESCE(c.CourseDesc, CONCAT('Course #', sc.Course)) as course_name,
                    COALESCE(c.CourseCode, sc.Course) as course_code
                FROM students_college sc
                LEFT JOIN courses c ON c.CourseID = sc.Course
                WHERE sc.StudentNo = '" . $db->escapeString($studentNo) . "'
            ");
            
            $student = $studentQuery->getRowArray();
            
            if ($student) {
                $data['student'] = $student;
                $data['student_number'] = $studentNo;
                $data['selected_sem'] = $semesterBtn;
                $data['school_year'] = '2022-2023';
                $data['course_name'] = $student['course_name'] ?? 'Course #' . ($student['course_id'] ?? '');
                $data['course_number'] = $student['course_code'] ?? $student['course_id'] ?? '';
                
                // 2. Get grades with teacher name
                try {
                    $gradesQuery = $db->query("
                        SELECT 
                            s.subjectCode,
                            s.subjectDesc,
                            g.Teacher as teacher_id,
                            COALESCE(u.fullname, CONCAT('Prof. ', g.Teacher)) as teacher_name,
                            g.Prelim,
                            g.Midterm,
                            g.Finals,
                            g.Grade,
                            g.Equivalent,
                            g.Remarks
                        FROM student_subject_college g
                        LEFT JOIN subject_college s ON s.subjectID = g.subjectID
                        LEFT JOIN users u ON u.userID = g.Teacher
                        WHERE g.studentnumber = '" . $db->escapeString($studentNo) . "'
                        AND g.sy = '2022-2023'
                        AND g.semester = '" . $db->escapeString($semester) . "'
                        ORDER BY s.subjectCode ASC
                    ");
                } catch (\Exception $e) {
                    $gradesQuery = $db->query("
                        SELECT 
                            s.subjectCode,
                            s.subjectDesc,
                            g.Teacher as teacher_id,
                            CONCAT('Instructor ', g.Teacher) as teacher_name,
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
                }
                
                $grades = $gradesQuery->getResultArray();
                $data['grades'] = $grades;
                
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
        $data['course_name'] = $data['course_name'] ?? '';
        $data['course_number'] = $data['course_number'] ?? '';
        
        return view('grades_view', $data);
    }
    
    // ADD THIS NEW METHOD FOR FINDING STUDENT BY NAME
    public function findStudentByName()
    {
        // Only allow AJAX requests
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
        }
        
        $lastName = trim($this->request->getPost('last_name') ?? '');
        $firstName = trim($this->request->getPost('first_name') ?? '');
        
        if (empty($lastName) || empty($firstName)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please enter both last name and first name.'
            ]);
        }
        
        $db = \Config\Database::connect();
        
        // Search for students by name (adjust based on your database structure)
        // This assumes FullName is in "LASTNAME, FIRSTNAME" format
        // If names are stored separately, adjust the query accordingly
        
        try {
            // Option 1: If names are stored separately in the database
            // Assuming you have LastName and FirstName columns:
            $query = $db->query("
                SELECT 
                    sc.StudentNo, 
                    sc.FullName,
                    sc.Course as course_id,
                    sc.Level,
                    COALESCE(c.CourseDesc, CONCAT('Course #', sc.Course)) as course_name,
                    COALESCE(c.CourseCode, sc.Course) as course_code
                FROM students_college sc
                LEFT JOIN courses c ON c.CourseID = sc.Course
                WHERE 
                    (sc.LastName LIKE '%" . $db->escapeLikeString($lastName) . "%' 
                     OR sc.FullName LIKE '%" . $db->escapeLikeString($lastName) . "%')
                    AND (sc.FirstName LIKE '%" . $db->escapeLikeString($firstName) . "%' 
                         OR sc.FullName LIKE '%" . $db->escapeLikeString($firstName) . "%')
                ORDER BY sc.FullName ASC
                LIMIT 20
            ");
            
            // Option 2: If only FullName is available (commented out)
            /*
            $query = $db->query("
                SELECT 
                    sc.StudentNo, 
                    sc.FullName,
                    sc.Course as course_id,
                    sc.Level,
                    COALESCE(c.CourseDesc, CONCAT('Course #', sc.Course)) as course_name,
                    COALESCE(c.CourseCode, sc.Course) as course_code
                FROM students_college sc
                LEFT JOIN courses c ON c.CourseID = sc.Course
                WHERE 
                    sc.FullName LIKE '%" . $db->escapeLikeString($lastName) . "%'
                    AND sc.FullName LIKE '%" . $db->escapeLikeString($firstName) . "%'
                ORDER BY sc.FullName ASC
                LIMIT 20
            ");
            */
            
            $students = $query->getResultArray();
            
            if (empty($students)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No students found with that name.'
                ]);
            }
            
            // Format the response
            $formattedStudents = array_map(function($student) {
                return [
                    'student_id' => $student['StudentNo'],
                    'full_name' => $student['FullName'],
                    'course' => $student['course_code'] ?? $student['course_id'] ?? '',
                    'course_name' => $student['course_name'] ?? '',
                    'level' => $student['Level']
                ];
            }, $students);
            
            return $this->response->setJSON([
                'success' => true,
                'students' => $formattedStudents
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error finding student by name: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while searching. Please try again.'
            ]);
        }
    }
}