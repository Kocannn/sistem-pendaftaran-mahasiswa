<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ujian Online - Universitas</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            'poppins': ['Poppins', 'sans-serif'],
          },
          colors: {
            'academic-blue': {
              50: '#eff6ff',
              100: '#dbeafe',
              200: '#bfdbfe',
              300: '#93c5fd',
              400: '#60a5fa',
              500: '#3b82f6',
              600: '#2563eb',
              700: '#1d4ed8',
              800: '#1e40af',
              900: '#1e3a8a',
            }
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 font-poppins min-h-screen">
  <!-- Fixed Top Bar -->
  <header class="fixed top-0 left-0 right-0 bg-white shadow-lg border-b-2 border-academic-blue-600 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo and University Name -->
        <div class="flex items-center space-x-3">
          <div class="flex items-center justify-center w-10 h-10 bg-academic-blue-600 rounded-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
          </div>
          <div class="hidden sm:block">
            <h1 class="text-lg font-semibold text-gray-900">Universitas Negeri</h1>
            <p class="text-xs text-gray-500">Ujian Masuk 2024</p>
          </div>
        </div>

        <!-- Student Info and Timer -->
        <div class="flex items-center space-x-4">
          <!-- Student Name -->
          <div class="hidden md:block text-right">
            <p class="text-sm font-medium text-gray-900">Ahmad Rizki Pratama</p>
            <p class="text-xs text-gray-500">NIM: 2024001</p>
          </div>

          <!-- Timer -->
          <div class="bg-academic-blue-50 border border-academic-blue-200 rounded-lg px-4 py-2">
            <div class="flex items-center space-x-2">
              <svg class="w-5 h-5 text-academic-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <div class="text-center">
                <p class="text-xs text-academic-blue-600 font-medium">Sisa Waktu</p>
                <p id="timer" class="text-lg font-bold text-academic-blue-800">15:00</p>
              </div>
            </div>
          </div>

          <!-- Logout Button -->
          <button onclick="handleLogout()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            <span class="hidden sm:inline">Keluar</span>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="pt-20 pb-8 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Progress Indicator -->
      <div class="mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
          <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium text-gray-700">Progress Ujian</span>
            <span id="progress-text" class="text-sm font-medium text-academic-blue-600">10%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-3">
            <div id="progress-bar" class="bg-gradient-to-r from-academic-blue-500 to-academic-blue-600 h-3 rounded-full transition-all duration-300" style="width: 10%"></div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Question Content -->
        <div class="lg:col-span-3">
          <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <!-- Question Header -->
            <div class="bg-academic-blue-600 text-white p-6">
              <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Soal <span id="current-question">1</span> dari 10</h2>
                <div class="bg-academic-blue-500 px-3 py-1 rounded-full">
                  <span class="text-sm font-medium">Pilihan Ganda</span>
                </div>
              </div>
            </div>

            <!-- Question Content -->
            <div class="p-8">
              <div id="question-content" class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 leading-relaxed">
                  Manakah dari pernyataan berikut yang paling tepat menggambarkan konsep algoritma dalam ilmu komputer?
                </h3>
              </div>

              <!-- Answer Options -->
              <div id="answer-options" class="space-y-4">
                <label class="answer-option flex items-start space-x-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-academic-blue-300 hover:bg-academic-blue-50 transition-all duration-200">
                  <input type="radio" name="answer" value="A" class="mt-1 w-5 h-5 text-academic-blue-600 border-gray-300 focus:ring-academic-blue-500">
                  <div class="flex-1">
                    <span class="font-semibold text-academic-blue-600 mr-3">A.</span>
                    <span class="text-gray-800">Sebuah bahasa pemrograman yang digunakan untuk membuat aplikasi web</span>
                  </div>
                </label>

                <label class="answer-option flex items-start space-x-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-academic-blue-300 hover:bg-academic-blue-50 transition-all duration-200">
                  <input type="radio" name="answer" value="B" class="mt-1 w-5 h-5 text-academic-blue-600 border-gray-300 focus:ring-academic-blue-500">
                  <div class="flex-1">
                    <span class="font-semibold text-academic-blue-600 mr-3">B.</span>
                    <span class="text-gray-800">Langkah-langkah sistematis untuk menyelesaikan masalah atau mencapai tujuan tertentu</span>
                  </div>
                </label>

                <label class="answer-option flex items-start space-x-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-academic-blue-300 hover:bg-academic-blue-50 transition-all duration-200">
                  <input type="radio" name="answer" value="C" class="mt-1 w-5 h-5 text-academic-blue-600 border-gray-300 focus:ring-academic-blue-500">
                  <div class="flex-1">
                    <span class="font-semibold text-academic-blue-600 mr-3">C.</span>
                    <span class="text-gray-800">Perangkat keras komputer yang digunakan untuk memproses data</span>
                  </div>
                </label>

                <label class="answer-option flex items-start space-x-4 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-academic-blue-300 hover:bg-academic-blue-50 transition-all duration-200">
                  <input type="radio" name="answer" value="D" class="mt-1 w-5 h-5 text-academic-blue-600 border-gray-300 focus:ring-academic-blue-500">
                  <div class="flex-1">
                    <span class="font-semibold text-academic-blue-600 mr-3">D.</span>
                    <span class="text-gray-800">Sistem operasi yang menjalankan program komputer</span>
                  </div>
                </label>
              </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="bg-gray-50 px-8 py-6 flex justify-between items-center">
              <button id="prev-btn" onclick="previousQuestion()" class="bg-gray-300 text-gray-600 px-6 py-3 rounded-lg font-medium transition-colors duration-200 cursor-not-allowed" disabled>
                <div class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                  </svg>
                  <span>Sebelumnya</span>
                </div>
              </button>

              <div class="text-center">
                <p class="text-sm text-gray-600">Soal <span id="question-number">1</span> dari 10</p>
              </div>

              <button id="next-btn" onclick="nextQuestion()" class="bg-academic-blue-600 hover:bg-academic-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                <div class="flex items-center space-x-2">
                  <span>Selanjutnya</span>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </div>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
          <div class="sticky top-24">
            <!-- Question Navigator -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Navigasi Soal</h3>
              <div class="grid grid-cols-5 gap-2">
                <button onclick="goToQuestion(1)" class="question-nav-btn w-10 h-10 rounded-lg border-2 border-academic-blue-600 bg-academic-blue-600 text-white font-medium text-sm transition-all duration-200">1</button>
                <button onclick="goToQuestion(2)" class="question-nav-btn w-10 h-10 rounded-lg border-2 border-gray-300 text-gray-600 font-medium text-sm hover:border-academic-blue-300 transition-all duration-200">2</button>
                <button onclick="goToQuestion(3)" class="question-nav-btn w-10 h-10 rounded-lg border-2 border-gray-300 text-gray-600 font-medium text-sm hover:border-academic-blue-300 transition-all duration-200">3</button>
                <button onclick="goToQuestion(4)" class="question-nav-btn w-10 h-10 rounded-lg border-2 border-gray-300 text-gray-600 font-medium text-sm hover:border-academic-blue-300 transition-all duration-200">4</button>
                <button onclick="goToQuestion(5)" class="question-nav-btn w-10 h-10 rounded-lg border-2 border-gray-300 text-gray-600 font-medium text-sm hover:border-academic-blue-300 transition-all duration-200">5</button>
                <button onclick="goToQuestion(6)" class="question-nav-btn w-10 h-10 rounded-lg border-2 border-gray-300 text-gray-600 font-medium text-sm hover:border-academic-blue-300 transition-all duration-200">6</button>
                <button onclick="goToQuestion(7)" class="question-nav-btn w-10 h-10 rounded-lg border-2 border-gray-300 text-gray-600 font-medium text-sm hover:border-academic-blue-300 transition-all duration-200">7</button>
                <button onclick="goToQuestion(8)" class="question-nav-btn w-10 h-10 rounded-lg border-2 border-gray-300 text-gray-600 font-medium text-sm hover:border-academic-blue-300 transition-all duration-200">8</button>
                <button onclick="goToQuestion(9)" class="question-nav-btn w-10 h-10 rounded-lg border-2 border-gray-300 text-gray-600 font-medium text-sm hover:border-academic-blue-300 transition-all duration-200">9</button>
                <button onclick="goToQuestion(10)" class="question-nav-btn w-10 h-10 rounded-lg border-2 border-gray-300 text-gray-600 font-medium text-sm hover:border-academic-blue-300 transition-all duration-200">10</button>
              </div>
              <div class="mt-4 text-xs text-gray-500">
                <div class="flex items-center space-x-2 mb-1">
                  <div class="w-3 h-3 bg-academic-blue-600 rounded"></div>
                  <span>Soal saat ini</span>
                </div>
                <div class="flex items-center space-x-2 mb-1">
                  <div class="w-3 h-3 bg-green-500 rounded"></div>
                  <span>Sudah dijawab</span>
                </div>
                <div class="flex items-center space-x-2">
                  <div class="w-3 h-3 bg-gray-300 rounded"></div>
                  <span>Belum dijawab</span>
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
              <button onclick="showSubmitModal()" class="w-full bg-green-600 hover:bg-green-700 text-white py-4 px-6 rounded-lg font-semibold text-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Selesai Ujian</span>
              </button>
              <p class="text-xs text-gray-500 text-center mt-2">Pastikan semua jawaban sudah benar</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Submit Confirmation Modal -->
  <div id="submitModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
      <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
          <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Selesai Ujian</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menyelesaikan ujian? Setelah dikonfirmasi, Anda tidak dapat mengubah jawaban lagi.</p>

        <div id="answer-summary" class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
          <h4 class="font-medium text-gray-900 mb-2">Ringkasan Jawaban:</h4>
          <div class="text-sm text-gray-600">
            <p>Soal dijawab: <span id="answered-count" class="font-medium text-green-600">1</span> dari 10</p>
            <p>Soal belum dijawab: <span id="unanswered-count" class="font-medium text-red-600">9</span></p>
          </div>
        </div>

        <div class="flex space-x-3">
          <button onclick="hideSubmitModal()" class="flex-1 bg-gray-300 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-400 transition-colors duration-200">
            Batal
          </button>
          <button onclick="submitExam()" class="flex-1 bg-green-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-700 transition-colors duration-200">
            Ya, Selesai
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Logout Confirmation Modal -->
  <div id="logoutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
      <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
          <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Keluar</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari ujian? Jawaban yang sudah diisi akan tersimpan.</p>

        <div class="flex space-x-3">
          <button onclick="hideLogoutModal()" class="flex-1 bg-gray-300 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-400 transition-colors duration-200">
            Batal
          </button>
          <button onclick="confirmLogout()" class="flex-1 bg-red-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-red-700 transition-colors duration-200">
            Ya, Keluar
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Exam data
    const examData = [{
        question: "Manakah dari pernyataan berikut yang paling tepat menggambarkan konsep algoritma dalam ilmu komputer?",
        options: [
          "Sebuah bahasa pemrograman yang digunakan untuk membuat aplikasi web",
          "Langkah-langkah sistematis untuk menyelesaikan masalah atau mencapai tujuan tertentu",
          "Perangkat keras komputer yang digunakan untuk memproses data",
          "Sistem operasi yang menjalankan program komputer"
        ]
      },
      {
        question: "Apa yang dimaksud dengan struktur data dalam pemrograman?",
        options: [
          "Cara mengorganisir dan menyimpan data agar dapat diakses dan dimodifikasi secara efisien",
          "Bahasa pemrograman yang digunakan untuk membuat database",
          "Perangkat lunak untuk mengelola file komputer",
          "Metode untuk mengenkripsi data sensitif"
        ]
      },
      {
        question: "Manakah yang merupakan contoh dari bahasa pemrograman tingkat tinggi?",
        options: [
          "Assembly Language",
          "Machine Code",
          "Python",
          "Binary Code"
        ]
      },
      {
        question: "Apa fungsi utama dari sistem operasi?",
        options: [
          "Membuat aplikasi mobile",
          "Mengelola sumber daya komputer dan menyediakan interface antara user dan hardware",
          "Mengenkripsi data pengguna",
          "Membuat website"
        ]
      },
      {
        question: "Dalam konteks jaringan komputer, apa yang dimaksud dengan protokol?",
        options: [
          "Perangkat keras untuk menghubungkan komputer",
          "Software antivirus untuk melindungi jaringan",
          "Aturan dan standar komunikasi antara perangkat dalam jaringan",
          "Kabel yang digunakan untuk koneksi internet"
        ]
      },
      {
        question: "Apa yang dimaksud dengan database relasional?",
        options: [
          "Database yang hanya bisa menyimpan teks",
          "Database yang mengorganisir data dalam bentuk tabel dengan relasi antar tabel",
          "Database yang hanya bisa diakses secara offline",
          "Database yang tidak memerlukan query language"
        ]
      },
      {
        question: "Manakah yang bukan merupakan komponen utama dari arsitektur komputer?",
        options: [
          "CPU (Central Processing Unit)",
          "RAM (Random Access Memory)",
          "Antivirus Software",
          "Storage Device"
        ]
      },
      {
        question: "Apa yang dimaksud dengan cloud computing?",
        options: [
          "Penyimpanan data di hard disk lokal",
          "Penggunaan jaringan internet untuk mengakses dan menyimpan data serta aplikasi",
          "Software untuk editing foto",
          "Perangkat keras untuk koneksi wireless"
        ]
      },
      {
        question: "Dalam pemrograman berorientasi objek, apa yang dimaksud dengan inheritance?",
        options: [
          "Proses debugging program",
          "Kemampuan suatu class untuk mewarisi properti dan method dari class lain",
          "Cara mengenkripsi kode program",
          "Metode untuk mengoptimalkan performa program"
        ]
      },
      {
        question: "Apa fungsi utama dari firewall dalam keamanan jaringan?",
        options: [
          "Mempercepat koneksi internet",
          "Menyimpan password pengguna",
          "Memfilter dan mengontrol lalu lintas jaringan berdasarkan aturan keamanan",
          "Membuat backup data otomatis"
        ]
      }
    ];

    // Exam state
    let currentQuestion = 0;
    let answers = {};
    let timeRemaining = 15 * 60; // 15 minutes in seconds

    // Initialize exam
    function initializeExam() {
      displayQuestion();
      startTimer();
      updateProgress();
      updateNavigationButtons();
    }

    // Display current question
    function displayQuestion() {
      const question = examData[currentQuestion];
      document.getElementById('question-content').innerHTML = `
                <h3 class="text-lg font-medium text-gray-900 mb-4 leading-relaxed">
                    ${question.question}
                </h3>
            `;

      const optionsContainer = document.getElementById('answer-options');
      optionsContainer.innerHTML = '';

      question.options.forEach((option, index) => {
        const letter = String.fromCharCode(65 + index); // A, B, C, D
        const isSelected = answers[currentQuestion] === letter;

        optionsContainer.innerHTML += `
                    <label class="answer-option flex items-start space-x-4 p-4 border-2 ${isSelected ? 'border-academic-blue-500 bg-academic-blue-50' : 'border-gray-200'} rounded-xl cursor-pointer hover:border-academic-blue-300 hover:bg-academic-blue-50 transition-all duration-200">
                        <input type="radio" name="answer" value="${letter}" ${isSelected ? 'checked' : ''} class="mt-1 w-5 h-5 text-academic-blue-600 border-gray-300 focus:ring-academic-blue-500" onchange="saveAnswer('${letter}')">
                        <div class="flex-1">
                            <span class="font-semibold text-academic-blue-600 mr-3">${letter}.</span>
                            <span class="text-gray-800">${option}</span>
                        </div>
                    </label>
                `;
      });

      // Update question numbers
      document.getElementById('current-question').textContent = currentQuestion + 1;
      document.getElementById('question-number').textContent = currentQuestion + 1;
    }

    // Save answer
    function saveAnswer(answer) {
      answers[currentQuestion] = answer;
      updateProgress();
      updateQuestionNavigator();
    }

    // Next question
    function nextQuestion() {
      if (currentQuestion < examData.length - 1) {
        currentQuestion++;
        displayQuestion();
        updateNavigationButtons();
        updateQuestionNavigator();
      }
    }

    // Previous question
    function previousQuestion() {
      if (currentQuestion > 0) {
        currentQuestion--;
        displayQuestion();
        updateNavigationButtons();
        updateQuestionNavigator();
      }
    }

    // Go to specific question
    function goToQuestion(questionNumber) {
      currentQuestion = questionNumber - 1;
      displayQuestion();
      updateNavigationButtons();
      updateQuestionNavigator();
    }

    // Update navigation buttons
    function updateNavigationButtons() {
      const prevBtn = document.getElementById('prev-btn');
      const nextBtn = document.getElementById('next-btn');

      if (currentQuestion === 0) {
        prevBtn.disabled = true;
        prevBtn.className = "bg-gray-300 text-gray-600 px-6 py-3 rounded-lg font-medium transition-colors duration-200 cursor-not-allowed";
      } else {
        prevBtn.disabled = false;
        prevBtn.className = "bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200";
      }

      if (currentQuestion === examData.length - 1) {
        nextBtn.textContent = "Selesai";
        nextBtn.onclick = showSubmitModal;
      } else {
        nextBtn.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <span>Selanjutnya</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                `;
        nextBtn.onclick = nextQuestion;
      }
    }

    // Update progress
    function updateProgress() {
      const answeredCount = Object.keys(answers).length;
      const progressPercentage = (answeredCount / examData.length) * 100;

      document.getElementById('progress-bar').style.width = progressPercentage + '%';
      document.getElementById('progress-text').textContent = Math.round(progressPercentage) + '%';
    }

    // Update question navigator
    function updateQuestionNavigator() {
      const navButtons = document.querySelectorAll('.question-nav-btn');
      navButtons.forEach((btn, index) => {
        btn.className = 'question-nav-btn w-10 h-10 rounded-lg border-2 font-medium text-sm transition-all duration-200';

        if (index === currentQuestion) {
          btn.className += ' border-academic-blue-600 bg-academic-blue-600 text-white';
        } else if (answers[index]) {
          btn.className += ' border-green-500 bg-green-500 text-white';
        } else {
          btn.className += ' border-gray-300 text-gray-600 hover:border-academic-blue-300';
        }
      });
    }

    // Timer functionality
    function startTimer() {
      const timer = setInterval(() => {
        timeRemaining--;
        updateTimerDisplay();

        if (timeRemaining <= 0) {
          clearInterval(timer);
          alert('Waktu ujian telah habis!');
          submitExam();
        }
      }, 1000);
    }

    function updateTimerDisplay() {
      const minutes = Math.floor(timeRemaining / 60);
      const seconds = timeRemaining % 60;
      document.getElementById('timer').textContent =
        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

      // Change color when time is running low
      const timerElement = document.getElementById('timer');
      if (timeRemaining <= 300) { // 5 minutes
        timerElement.className = 'text-lg font-bold text-red-600';
      } else if (timeRemaining <= 600) { // 10 minutes
        timerElement.className = 'text-lg font-bold text-yellow-600';
      }
    }

    // Modal functions
    function showSubmitModal() {
      const answeredCount = Object.keys(answers).length;
      const unansweredCount = examData.length - answeredCount;

      document.getElementById('answered-count').textContent = answeredCount;
      document.getElementById('unanswered-count').textContent = unansweredCount;
      document.getElementById('submitModal').classList.remove('hidden');
    }

    function hideSubmitModal() {
      document.getElementById('submitModal').classList.add('hidden');
    }

    function submitExam() {
      // Here you would typically send the answers to the server
      alert('Ujian telah selesai! Jawaban Anda telah tersimpan.');
      // Redirect to results page or login page
      window.location.href = '/exam-results';
    }

    function handleLogout() {
      document.getElementById('logoutModal').classList.remove('hidden');
    }

    function hideLogoutModal() {
      document.getElementById('logoutModal').classList.add('hidden');
    }

    function confirmLogout() {
      // Save current progress
      localStorage.setItem('examProgress', JSON.stringify({
        currentQuestion,
        answers,
        timeRemaining
      }));

      alert('Progress ujian telah disimpan. Anda akan diarahkan ke halaman login.');
      window.location.href = '/login';
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
      if (e.key === 'ArrowLeft' && currentQuestion > 0) {
        previousQuestion();
      } else if (e.key === 'ArrowRight' && currentQuestion < examData.length - 1) {
        nextQuestion();
      } else if (e.key >= '1' && e.key <= '4') {
        const optionIndex = parseInt(e.key) - 1;
        const letter = String.fromCharCode(65 + optionIndex);
        saveAnswer(letter);

        // Update radio button
        const radioButtons = document.querySelectorAll('input[name="answer"]');
        radioButtons[optionIndex].checked = true;

        // Update visual selection
        displayQuestion();
      }
    });

    // Prevent accidental page refresh
    window.addEventListener('beforeunload', function(e) {
      e.preventDefault();
      e.returnValue = '';
    });

    // Initialize exam when page loads
    document.addEventListener('DOMContentLoaded', initializeExam);
  </script>
</body>

</html>
