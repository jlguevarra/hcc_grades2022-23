<?php

namespace App\Models;

use CodeIgniter\Model;

class GradesModel extends Model
{
    protected $table = 'student_subject_college';
    protected $primaryKey = 'studentsubjectID';
    protected $allowedFields = [];
    protected $returnType = 'array';

    /**
     * 1. Find Student Info (Name, Course) from students_college table
     */
    public function getStudentInfo($studentNo)
    {
        $db = \Config\Database::connect();
        return $db->table('students_college')
                  ->select('admissionID, StudentNo, FullName, Course, Level')
                  ->where('StudentNo', trim($studentNo))
                  ->get()
                  ->getRowArray();
    }

    /**
     * 2. Get Grades joined with Subject and Teacher info
     */
    public function getStudentGrades($studentNumber, $semester)
    {
        $builder = $this->db->table('student_subject_college as grades');
        
        // JOIN: Match subjectID to get subjectCode and subjectDesc
        $builder->join('subject_college as subjects', 'subjects.subjectID = grades.subjectID', 'left');
        
        // JOIN: Match Teacher ID to users table to get the Full Name
        // Note: Make sure you have a 'users' table with 'userID' and 'fullname' columns
        $builder->join('users as teacher_user', 'teacher_user.userID = grades.Teacher', 'left');

        $builder->select('
            subjects.subjectCode as subject_code,
            subjects.subjectDesc as subject_description,
            grades.Teacher as teacher_raw,
            teacher_user.fullname as teacher_name,
            grades.Prelim as prelim,
            grades.Midterm as midterm,
            grades.Finals as finals,
            grades.Grade as semestral,
            grades.Equivalent as equivalent,
            grades.Remarks as remarks
        ');

        // Filter by the provided student number
        $builder->where('grades.studentnumber', $studentNumber);
        
        // Hardcoded for SY 2022-2023 as requested
        $builder->where('grades.sy', '2022-2023');
        $builder->where('grades.semester', $semester);
        
        // Order by subject code for better presentation
        $builder->orderBy('subjects.subjectCode', 'ASC');

        $query = $builder->get();
        
        // For debugging: Uncomment to see the generated SQL
        // log_message('info', 'SQL Query: ' . $this->db->getLastQuery());
        
        return $query->getResultArray();
    }
}