<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holy Cross College - Grade Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-gray-100 min-h-screen p-4 md:p-6">
    
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white rounded-2xl shadow-xl p-6 md:p-8 mb-8">
            <div class="flex flex-col md:flex-row items-center">
                <div class="mb-6 md:mb-0 md:mr-8">
                    <div class="w-24 h-24 md:w-28 md:h-28 bg-white rounded-full flex items-center justify-center shadow-lg overflow-hidden">
                        <img src="<?= base_url('uploads/hcc logo.png') ?>" 
                             alt="HCC Logo" 
                             class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-2xl md:text-4xl font-bold mb-2">HOLY CROSS COLLEGE</h1>
                    <p class="text-blue-200 text-lg md:text-xl font-semibold mb-1">Student Grade Viewing Portal</p>
                    <p class="text-blue-300">School Year 2022-2023</p>
                </div>
            </div>
        </div>
        
        <!-- Search Form -->
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 mb-8">
            <form method="POST" action="">
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-3 text-lg">
                        <i class="fas fa-user-graduate mr-2 text-blue-600"></i>Student Number
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="student_number" 
                               value="<?= htmlspecialchars($student_number ?? '') ?>" 
                               class="w-full pl-12 pr-4 py-4 text-lg border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                               placeholder="Enter student number (e.g., 22-0013484)"
                               required>
                    </div>
                    <p class="text-gray-500 text-sm mt-2 ml-1">
                        <i class="fas fa-info-circle mr-1"></i> Example: 22-0013484
                    </p>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-lg">
                        <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>Select Semester
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <button type="submit" 
                                name="semester_btn" 
                                value="1st Semester"
                                class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-5 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center text-lg">
                            <i class="fas fa-book-open mr-3 text-xl"></i>
                            <span>1st Semester Grades</span>
                        </button>
                        <button type="submit" 
                                name="semester_btn" 
                                value="2nd Semester"
                                class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-5 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center text-lg">
                            <i class="fas fa-book-bookmark mr-3 text-xl"></i>
                            <span>2nd Semester Grades</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Error Message -->
        <?php if (isset($error)): ?>
            <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 text-red-700 p-6 rounded-xl shadow-md mb-8">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-bold text-lg">Error</h3>
                        <p class="mt-1"><?= htmlspecialchars($error) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Grades Display -->
        <?php if (isset($student) && is_array($student)): ?>
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                <!-- Student Info Header -->
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white p-6 md:p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div class="mb-4 md:mb-0">
                            <h2 class="text-2xl md:text-3xl font-bold mb-2"><?= htmlspecialchars($student['FullName']) ?></h2>
                            <div class="flex flex-wrap gap-3">
                               <span class="bg-blue-600 bg-opacity-80 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                    <i class="fas fa-book mr-1"></i> 
                                    <?= isset($course_name) ? htmlspecialchars($course_name) : 'Course #' . htmlspecialchars($student['Course']) ?>
                                </span>
                                <span class="bg-purple-600 bg-opacity-80 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                    <i class="fas fa-user-graduate mr-1"></i> <?= htmlspecialchars($student['Level']) ?>
                                </span>
                                <span class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                    <i class="fas fa-id-card mr-1"></i> <?= htmlspecialchars($student_number) ?>
                                </span>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-3 rounded-xl shadow-lg mt-4 md:mt-0">
                            <p class="font-bold text-lg text-center">
                                <i class="fas fa-calendar-check mr-2"></i>
                                <?= htmlspecialchars($selected_sem ?? '') ?> <?= htmlspecialchars($school_year ?? '2022-2023') ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Check if grades exist -->
                <?php if (isset($grades) && is_array($grades) && !empty($grades)): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Teacher</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Prelim</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Midterm</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Finals</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider bg-blue-50">Grade</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Equivalent</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($grades as $grade): ?>
                                <tr class="hover:bg-blue-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900"><?= htmlspecialchars($grade['subjectCode'] ?? '') ?></div>
                                        <div class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($grade['subjectDesc'] ?? '') ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-medium">
                                        <?php
                                        $teacher = '';
                                        if (isset($grade['teacher_name']) && !empty($grade['teacher_name'])) {
                                            $teacher = htmlspecialchars($grade['teacher_name']);
                                        } elseif (isset($grade['Teacher']) && !empty($grade['Teacher'])) {
                                            $teacher = 'Instructor ' . htmlspecialchars($grade['Teacher']);
                                        } elseif (isset($grade['teacher_id']) && !empty($grade['teacher_id'])) {
                                            $teacher = 'Prof. ' . htmlspecialchars($grade['teacher_id']);
                                        } else {
                                            $teacher = 'TBA';
                                        }
                                        echo $teacher;
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-lg font-medium <?= ($grade['Prelim'] ?? '') === 'TBT' ? 'text-gray-400 italic' : 'text-gray-900' ?>">
                                        <?= htmlspecialchars($grade['Prelim'] ?? '-') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-lg font-medium <?= ($grade['Midterm'] ?? '') === 'TBT' ? 'text-gray-400 italic' : 'text-gray-900' ?>">
                                        <?= htmlspecialchars($grade['Midterm'] ?? '-') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-lg font-medium <?= ($grade['Finals'] ?? '') === 'TBT' ? 'text-gray-400 italic' : 'text-gray-900' ?>">
                                        <?= htmlspecialchars($grade['Finals'] ?? '-') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-2xl font-bold text-blue-700 bg-blue-50">
                                        <?= htmlspecialchars($grade['Grade'] ?? '-') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-lg font-semibold text-gray-800">
                                        <?= htmlspecialchars($grade['Equivalent'] ?? '-') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php
                                        $remarks = strtoupper(trim($grade['Remarks'] ?? ''));
                                        if ($remarks === 'PASSED') {
                                            $class = 'bg-green-100 text-green-800';
                                            $icon = 'fas fa-check-circle';
                                        } elseif ($remarks === 'FAILED') {
                                            $class = 'bg-red-100 text-red-800';
                                            $icon = 'fas fa-times-circle';
                                        } elseif ($remarks === 'INC') {
                                            $class = 'bg-yellow-100 text-yellow-800';
                                            $icon = 'fas fa-exclamation-circle';
                                        } elseif ($remarks === 'DRP') {
                                            $class = 'bg-red-100 text-red-800';
                                            $icon = 'fas fa-ban';
                                        } elseif ($remarks === 'TBT' || empty($remarks)) {
                                            $class = 'bg-gray-100 text-gray-800';
                                            $icon = 'fas fa-clock';
                                            $remarks = 'PENDING';
                                        } else {
                                            $class = 'bg-blue-100 text-blue-800';
                                            $icon = 'fas fa-info-circle';
                                        }
                                        ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $class ?>">
                                            <i class="<?= $icon ?> mr-1"></i>
                                            <?= htmlspecialchars($remarks) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Summary Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-gray-700 font-medium">
                                <i class="fas fa-list-check mr-2"></i>
                                Total Subjects: <span class="font-bold text-blue-700"><?= count($grades) ?></span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-calendar-day mr-1"></i>
                                <?= htmlspecialchars($selected_sem ?? '') ?> - School Year 2022-2023
                            </div>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- No Grades Found -->
                    <div class="p-16 text-center">
                        <div class="inline-block p-6 bg-blue-50 rounded-2xl mb-6">
                            <i class="fas fa-search text-6xl text-blue-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">No Grades Found</h3>
                        <p class="text-gray-600 max-w-md mx-auto">
                            No grade records found for 
                            <span class="font-bold text-blue-700"><?= htmlspecialchars($selected_sem ?? 'this semester') ?></span> 
                            of School Year 2022-2023.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        <?php elseif (!empty($_POST)): ?>
            <!-- Form was submitted but student not found -->
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <div class="inline-block p-6 bg-red-50 rounded-2xl mb-6">
                    <i class="fas fa-user-slash text-6xl text-red-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Student Not Found</h3>
                <p class="text-gray-600">
                    No student found with number: <span class="font-bold"><?= htmlspecialchars($_POST['student_number'] ?? '') ?></span>
                </p>
            </div>
        <?php endif; ?>
        
        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm mt-12 pb-4">
            <p>Holy Cross College Student Portal • School Year 2022-2023 • Official Grade Viewing System</p>
            <p class="mt-2 text-xs">
                <i class="fas fa-info-circle mr-1"></i>
                For discrepancies in grades, please contact the Registrar's Office.
            </p>
        </div>
        
    </div>
    
    <script>
        // Simple hover effect
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.addEventListener('mouseenter', () => {
                    row.classList.add('bg-blue-50');
                });
                row.addEventListener('mouseleave', () => {
                    row.classList.remove('bg-blue-50');
                });
            });
        });
    </script>
</body>
</html>