<?php
// Kết nối cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "WebThiTracNghiem");

// Kiểm tra kết nối
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Lấy danh sách đề thi
$dethi = [];
$query = "SELECT made, tende FROM dethi WHERE trangthai = 1"; // Lấy đề thi đang hoạt động
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $dethi[] = $row;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đề Thi</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 600px;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1,
    h2 {
        text-align: center;
    }

    button {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: none;
        border-radius: 5px;
        background-color: #28a745;
        color: white;
        cursor: pointer;
    }

    button:hover {
        background-color: #218838;
    }

    #questions {
        margin-top: 20px;
    }

    .question {
        margin-bottom: 20px;
        padding: 10px;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .answers {
        margin-left: 20px;
    }

    .answers input[type="radio"] {
        margin-right: 10px;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Chọn Đề Thi</h1>
        <div id="dethi-list">
            <?php foreach ($dethi as $item): ?>
            <button class="dethi" data-made="<?php echo $item['made']; ?>">
                <?php echo $item['tende']; ?>
            </button>
            <?php endforeach; ?>
        </div>

        <h2>Câu Hỏi</h2>
        <div id="questions">
            <!-- Câu hỏi sẽ được tải vào đây khi chọn đề thi -->
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dethiButtons = document.querySelectorAll('.dethi');
        dethiButtons.forEach(button => {
            button.addEventListener('click', function() {
                const made = this.dataset.made;
                loadQuestions(made);
            });
        });
    });

    function loadQuestions(made) {
        fetch(`get_question.php?made=${made}`) // Gọi API để lấy câu hỏi theo mã đề thi
            .then(response => response.json())
            .then(data => {
                const questionsDiv = document.getElementById('questions');
                questionsDiv.innerHTML = ''; // Xóa nội dung cũ

                if (data.length > 0) {
                    data.forEach(question => {
                        const questionElement = document.createElement('div');
                        questionElement.className = 'question';
                        questionElement.innerHTML = `<strong>Câu hỏi:</strong> ${question.noidung}`;

                        const answersDiv = document.createElement('div');
                        answersDiv.className = 'answers';

                        question.cautraloi.forEach(answer => {
                            const answerElement = document.createElement('div');
                            answerElement.innerHTML = `
                                    <input type="radio" name="answer_${question.macauhoi}" value="${answer.ladapan}">
                                    ${answer.noidungtl}
                                `;
                            answersDiv.appendChild(answerElement);
                        });

                        questionElement.appendChild(answersDiv);
                        questionsDiv.appendChild(questionElement);
                    });

                    // Thêm nút nộp bài
                    const submitButton = document.createElement('button');
                    submitButton.textContent = 'Nộp bài';
                    submitButton.addEventListener('click', function() {
                        submitAnswers(data);
                    });
                    questionsDiv.appendChild(submitButton);
                } else {
                    questionsDiv.textContent = 'Không có câu hỏi cho đề thi này.';
                }
            })
            .catch(error => console.error('Error loading questions:', error));
    }

    function submitAnswers(questions) {
        let answers = [];

        questions.forEach(question => {
            const selectedAnswer = document.querySelector(`input[name="answer_${question.macauhoi}"]:checked`);
            if (selectedAnswer) {
                answers.push({
                    macauhoi: question.macauhoi,
                    answer: selectedAnswer.value
                });
            }
        });

        fetch('submit_answers.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    answers
                })
            })
            .then(response => response.json())
            .then(result => {
                alert(result.message); // Hiển thị kết quả
            })
            .catch(error => console.error('Error submitting answers:', error));
    }
    </script>
</body>

</html>