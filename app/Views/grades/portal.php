<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HCC Viewing Grades</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap'); body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-slate-100 min-h-screen p-4 flex flex-col items-center">

    <div class="bg-white w-full max-w-5xl rounded-2xl shadow-sm p-6 mb-6 flex flex-col md:flex-row items-center gap-6 border-l-8 border-blue-900">
        <img src="<?= base_url('uploads/hcc logo.png') ?>" class="w-24 h-24 object-cover rounded-full shadow-md bg-white border-2 border-gray-100">
        <div class="text-center md:text-left">
            <h1 class="text-3xl font-extrabold text-blue-900 tracking-tight">HOLY CROSS COLLEGE</h1>
            <p class="text-blue-600 font-bold uppercase tracking-wide text-sm">Student Grade Viewing Portal</p>
            <p class="text-gray-400 text-xs mt-1">School Year 2022-2023</p>
        </div>
    </div>

    <div class="bg-white w-full max-w-5xl rounded-xl shadow-sm p-8 mb-6">
        <form action="<?= base_url('grades') ?>" method="post" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Student Number</label>
                <div class="relative">
                    <i class='bx bxs-user-badge absolute left-4 top-1/2 transform -translate-y-1/2 text-xl text-gray-400'></i>
                    <input type="text" name="student_number" value="<?= esc($student_number ?? '') ?>" 
                           class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-bold text-gray-700 text-lg placeholder-gray-400 transition-all"
                           placeholder="Enter Student No. (e.g. 22-0013795)" required>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 pt-2">
                <button type="submit" name="semester_btn" value="1st Semester" class="py-4 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class='bx bx-book-open'></i> 1st Semester
                </button>
                <button type="submit" name="semester_btn" value="2nd Semester" class="py-4 rounded-xl font-bold text-white bg-orange-500 hover:bg-orange-600 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class='bx bx-book-bookmark'></i> 2nd Semester
                </button>
            </div>
        </form>
    </div>

    <?php if (isset($error)): ?>
        <div class="w-full max-w-5xl bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-sm mb-6 flex items-center">
            <i class='bx bxs-error-circle mr-2 text-xl'></i> <?= esc($error) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($student_name)): ?>
    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 mt-6">
        
        <div class="bg-gray-800 text-white p-6 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h2 class="text-2xl font-bold uppercase"><?= esc($student_name ?? 'Student Record') ?></h2>
                <p class="text-gray-400 text-sm mt-1"><?= esc($student_course ?? '') ?></p>
                <p class="text-gray-400 text-xs mt-1">Student No: <?= esc($student_number ?? '') ?></p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="px-4 py-2 bg-blue-600 rounded-lg text-sm font-bold shadow-sm border border-blue-500">
                    <?= esc($selected_sem ?? '') ?>
                </span>
            </div>
        </div>

        <?php if (!empty($results)): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 font-bold uppercase border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 w-1/4">Subject</th>
                            <th class="px-6 py-4">Teacher</th>
                            <th class="px-3 py-4 text-center">Prelim</th>
                            <th class="px-3 py-4 text-center">Midterm</th>
                            <th class="px-3 py-4 text-center">Finals</th>
                            <th class="px-3 py-4 text-center text-blue-700">Grade</th>
                            <th class="px-3 py-4 text-center">Equiv</th>
                            <th class="px-6 py-4 text-center">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($results as $row): ?>
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800 text-base"><?= esc($row['subject_code'] ?? 'N/A') ?></div>
                                <div class="text-xs text-gray-500 mt-0.5"><?= esc($row['subject_description'] ?? '') ?></div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-medium">
                                <?= esc(empty($row['teacher_name']) ? ($row['teacher_raw'] == '0' ? 'TBA' : $row['teacher_raw']) : $row['teacher_name']) ?>
                            </td>
                            <td class="px-3 py-4 text-center text-gray-600"><?= esc($row['prelim'] ?? '') ?></td>
                            <td class="px-3 py-4 text-center text-gray-600"><?= esc($row['midterm'] ?? '') ?></td>
                            <td class="px-3 py-4 text-center text-gray-600"><?= esc($row['finals'] ?? '') ?></td>
                            <td class="px-3 py-4 text-center font-extrabold text-blue-700 text-base bg-blue-50/50">
                                <?= esc($row['semestral'] ?? '') ?>
                            </td>
                            <td class="px-3 py-4 text-center font-bold text-gray-700"><?= esc($row['equivalent'] ?? '') ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php 
                                    $rem = isset($row['remarks']) ? strtoupper($row['remarks']) : '';
                                    $badgeClass = match($rem) {
                                        'PASSED' => 'bg-green-100 text-green-700 border-green-200',
                                        'FAILED' => 'bg-red-100 text-red-700 border-red-200',
                                        'INC'    => 'bg-orange-100 text-orange-700 border-orange-200',
                                        default  => 'bg-gray-100 text-gray-600 border-gray-200'
                                    };
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-bold border <?= $badgeClass ?>">
                                    <?= esc($rem) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="p-12 text-center border-t border-gray-100">
                <div class="inline-block p-4 rounded-full bg-gray-50 mb-3">
                    <i class='bx bx-search-alt text-4xl text-gray-300'></i>
                </div>
                <h3 class="text-lg font-bold text-gray-700">No Grades Found</h3>
                <p class="text-gray-500 text-sm">
                    We found your name, but there are no grades recorded for 
                    <span class="font-bold text-gray-700"><?= esc($selected_sem ?? 'Selected Semester') ?></span> of 
                    <span class="font-bold text-gray-700">2022-2023</span>.
                </p>
                <p class="text-gray-400 text-xs mt-2">
                    Student Number: <?= esc($student_number ?? '') ?><br>
                    Semester Filter: <?= esc($selected_sem ?? '') ?> (Mapped to: <?= 
                        ($selected_sem ?? '') === '1st Semester' ? '1ST SEM' : 
                        (($selected_sem ?? '') === '2nd Semester' ? '2ND SEM' : 'Unknown') 
                    ?>)
                </p>
            </div>
        <?php endif; ?>
        
    </div>
    <?php endif; ?>

</body>
</html>