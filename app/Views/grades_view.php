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
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: white;
            border-radius: 1rem;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .search-results {
            max-height: 300px;
            overflow-y: auto;
        }
        .custom-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 2000;
            min-width: 300px;
            max-width: 400px;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-gray-100 min-h-screen p-4 md:p-6">
    
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white rounded-2xl shadow-xl p-6 md:p-8 mb-8">
            <div class="flex flex-col md:flex-row items-center">
                <div class="mb-6 md:mb-0 md:mr-8">
                    <div class="w-28 h-28 md:w-32 md:h-32 bg-white rounded-full flex items-center justify-center shadow-lg overflow-hidden p-2">
                        <img src="<?= base_url('uploads/hcc logo.png') ?>" 
                             alt="HCC Logo" 
                             class="w-full h-full object-contain p-1">
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
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-gray-700 font-bold text-lg">
                            <i class="fas fa-user-graduate mr-2 text-blue-600"></i>Student Number
                        </label>
                        <button type="button" 
                                onclick="openFindStudentModal()"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center transition-colors">
                            <i class="fas fa-question-circle mr-1"></i>
                            Can't find your student ID?
                        </button>
                    </div>
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
        
        <!-- Find Student Modal -->
        <div id="findStudentModal" class="modal-overlay">
            <div class="modal-content p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-search mr-2 text-blue-600"></i>
                        Find Your Student ID
                    </h3>
                    <button onclick="closeFindStudentModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="mb-6">
                    <p class="text-gray-600 mb-4">
                        Enter your last name and first name to find your student ID.
                    </p>
                    
                    <form id="findStudentForm" class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-user mr-2 text-blue-600"></i>
                                Last Name
                            </label>
                            <input type="text" 
                                   id="lastName" 
                                   name="last_name"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                                   placeholder="Enter your last name"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-user mr-2 text-blue-600"></i>
                                First Name
                            </label>
                            <input type="text" 
                                   id="firstName" 
                                   name="first_name"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                                   placeholder="Enter your first name"
                                   required>
                        </div>
                        
                        <button type="button" 
                                onclick="searchStudentByName()"
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-search mr-2"></i>
                            Search for Student ID
                        </button>
                    </form>
                </div>
                
                <!-- Search Results -->
                <div id="searchResults" class="search-results hidden">
                    <h4 class="font-bold text-gray-700 mb-3">
                        <i class="fas fa-list mr-2"></i>
                        Search Results
                    </h4>
                    <div id="resultsList" class="space-y-3">
                        <!-- Results will be populated here -->
                    </div>
                </div>
                
                <!-- Loading State -->
                <div id="loadingIndicator" class="hidden text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                    <p class="text-gray-600 mt-4">Searching for student records...</p>
                </div>
                
                <!-- No Results Message -->
                <div id="noResultsMessage" class="hidden text-center py-8">
                    <div class="inline-block p-4 bg-gray-100 rounded-full mb-4">
                        <i class="fas fa-user-slash text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-700 font-medium">No student found with that name.</p>
                    <p class="text-gray-500 text-sm mt-2">Please check your spelling or contact the Registrar's Office.</p>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-gray-500 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        If you still can't find your student ID, please visit the Registrar's Office for assistance.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Custom Alert Container -->
        <div id="alertContainer"></div>
        
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
                <button onclick="openFindStudentModal()"
                        class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-search mr-2"></i>
                    Find your Student ID by Name
                </button>
            </div>
        <?php endif; ?>
        
        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm mt-12 pb-4">
            <p class="mb-2">Holy Cross College Student Portal • School Year 2022-2023 • Official Grade Viewing System</p>
            
            <!-- Credit Section -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-xs text-gray-400">
                    <i class="fas fa-code mr-1"></i>
                    System Developed by: <span class="font-medium text-gray-500">TRS Team</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    <i class="fas fa-building mr-1"></i>
                    Registrar's Office Management System
                </p>
            </div>
            
            <div class="mt-4">
                <p class="text-xs text-gray-400">
                    <i class="fas fa-info-circle mr-1"></i>
                    For discrepancies in grades, please contact the Registrar's Office.
                </p>
            </div>
        </div>
        
    </div>
    
    <script>
        // Simple hover effect for table rows
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

        // Show custom alert function
        function showAlert(message, type = 'warning') {
            const alertContainer = document.getElementById('alertContainer');
            
            // Remove existing alerts
            alertContainer.innerHTML = '';
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `custom-alert p-4 rounded-lg shadow-lg border-l-4 ${
                type === 'warning' 
                    ? 'bg-yellow-50 border-yellow-500 text-yellow-800' 
                    : type === 'error'
                    ? 'bg-red-50 border-red-500 text-red-800'
                    : 'bg-blue-50 border-blue-500 text-blue-800'
            }`;
            
            alertDiv.innerHTML = `
                <div class="flex items-start">
                    <i class="fas fa-${type === 'warning' ? 'exclamation-triangle' : type === 'error' ? 'times-circle' : 'info-circle'} mt-1 mr-3"></i>
                    <div class="flex-1">
                        <p class="font-medium">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            alertContainer.appendChild(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Modal functions
        function openFindStudentModal() {
            document.getElementById('findStudentModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeFindStudentModal() {
            document.getElementById('findStudentModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            resetSearchForm();
        }

        function resetSearchForm() {
            document.getElementById('lastName').value = '';
            document.getElementById('firstName').value = '';
            document.getElementById('searchResults').classList.add('hidden');
            document.getElementById('noResultsMessage').classList.add('hidden');
            document.getElementById('loadingIndicator').classList.add('hidden');
        }

        async function searchStudentByName() {
            const lastName = document.getElementById('lastName').value.trim();
            const firstName = document.getElementById('firstName').value.trim();
            
            // Validate form using HTML5 validation
            const form = document.getElementById('findStudentForm');
            if (!form.checkValidity()) {
                // Trigger browser's native validation
                form.reportValidity();
                return;
            }
            
            // Show loading indicator
            document.getElementById('loadingIndicator').classList.remove('hidden');
            document.getElementById('searchResults').classList.add('hidden');
            document.getElementById('noResultsMessage').classList.add('hidden');
            
            try {
                const response = await fetch('<?= site_url("grades/findStudentByName") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        last_name: lastName,
                        first_name: firstName
                    })
                });
                
                const result = await response.json();
                
                // Hide loading
                document.getElementById('loadingIndicator').classList.add('hidden');
                
                if (result.success && result.students && result.students.length > 0) {
                    displaySearchResults(result.students);
                } else {
                    document.getElementById('noResultsMessage').classList.remove('hidden');
                    // Update error message if available
                    if (result.message) {
                        document.querySelector('#noResultsMessage p').textContent = result.message;
                    }
                }
            } catch (error) {
                document.getElementById('loadingIndicator').classList.add('hidden');
                console.error('Error:', error);
                showAlert('Error searching for student. Please try again.', 'error');
            }
        }

        function displaySearchResults(students) {
            const resultsList = document.getElementById('resultsList');
            const searchResults = document.getElementById('searchResults');
            
            resultsList.innerHTML = '';
            
            students.forEach(student => {
                const studentElement = document.createElement('div');
                studentElement.className = 'bg-gray-50 p-4 rounded-xl border border-gray-200 hover:border-blue-300 transition-colors';
                studentElement.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div>
                            <h5 class="font-bold text-gray-800">${student.full_name}</h5>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-medium">
                                    <i class="fas fa-id-card mr-1"></i> ${student.student_id}
                                </span>
                                <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-medium">
                                    <i class="fas fa-graduation-cap mr-1"></i> ${student.course}
                                </span>
                                <span class="bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full font-medium">
                                    <i class="fas fa-user-graduate mr-1"></i> ${student.level}
                                </span>
                            </div>
                        </div>
                        <button onclick="useStudentId('${student.student_id}')" 
                                class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
                            <i class="fas fa-check-circle mr-1"></i>
                            Use This ID
                        </button>
                    </div>
                `;
                resultsList.appendChild(studentElement);
            });
            
            searchResults.classList.remove('hidden');
        }

        function useStudentId(studentId) {
            // Fill the student number input field
            document.querySelector('input[name="student_number"]').value = studentId;
            
            // Close the modal
            closeFindStudentModal();
            
            // Show success message
            showAlert(`Student ID ${studentId} has been populated in the search field.`, 'info');
            
            // Optional: Focus on the input field
            document.querySelector('input[name="student_number"]').focus();
        }

        // Close modal when clicking outside
        document.getElementById('findStudentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFindStudentModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeFindStudentModal();
            }
        });
    </script>
</body>
</html>