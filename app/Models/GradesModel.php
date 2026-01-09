<?php

namespace App\Models;

use CodeIgniter\Model;

class GradesModel extends Model
{
    protected $table = 'student_subject_college';
    protected $primaryKey = 'studentsubjectID';
    
    public function getStudentInfo($studentNo)
    {
        $db = db_connect();
        return $db->table('students_college')
            ->select('StudentNo, FullName, Course, Level')
            ->where('StudentNo', $studentNo)
            ->get()
            ->getRowArray();
    }
    
    public function getStudentGrades($studentNo, $semester)
    {
        $db = db_connect();
        return $db->table('student_subject_college as g')
            ->select('s.subjectCode, s.subjectDesc, g.Teacher, g.Prelim, g.Midterm, g.Finals, g.Grade, g.Equivalent, g.Remarks')
            ->join('subject_college as s', 's.subjectID = g.subjectID', 'left')
            ->where('g.studentnumber', $studentNo)
            ->where('g.sy', '2022-2023')
            ->where('g.semester', $semester)
            ->orderBy('s.subjectCode', 'ASC')
            ->get()
            ->getResultArray();
    }
}