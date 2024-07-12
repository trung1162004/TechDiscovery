<?php
include "header.php";
include "navbar.php";
include "survey_onclick.php";

class question {
    private $db;
    public function __construct() {
        $this->db = new Database();
    }

    public function insert_question($question_id, $question, $answer1, $answer2, $answer3) {
        $query = "INSERT INTO questions(question_id, question, answer1, answer2, answer3) 
                  VALUES ('$question_id', '$question', '$answer1', '$answer2', '$answer3')";

        $result = $this->db->insert($query);
        return $result;
    }
    public function show_question(){
        $query = "SELECT * FROM questions ORDER BY question_id ASC ";
        $result = $this->db->select($query);
        return $result;
    }
    public function save_survey_response($question_id, $selected_answer_label, $user_id) {
        // Get user information from the users table based on user_id
        $user_info_query = "SELECT username, email FROM users WHERE id = $user_id";
        $user_info_result = $this->db->select($user_info_query);
        $user_info = $user_info_result->fetch_assoc();
            
        $username = $user_info['username'];
        $email = $user_info['email'];
         // Kiểm tra xem user_id đã submit chưa
         $submission_query = "SELECT survey_responses_id FROM survey_responses WHERE question_id = $question_id AND user_id = $user_id";
         $submission_result = $this->db->select($submission_query);
    
        if ($submission_result->num_rows > 0) {
        // Người dùng đã submit rồi, không thực hiện gì cả
        return false;
         }
        // Lấy tên đáp án dựa trên nhãn đáp án
        $selected_answer = $this->get_answer_label($question_id, $selected_answer_label);
    
        $query = "INSERT INTO survey_responses (question_id, selected_answer, username, email, user_id)
                  VALUES ('$question_id', '$selected_answer', '$username', '$email', '$user_id')";
            
        $result = $this->db->insert($query);
    
        if ($result) {
            $updateColumn = "quantity_" . $selected_answer_label;
            $updateQuery = "UPDATE questions SET $updateColumn = $updateColumn + 1 WHERE question_id = $question_id";
            $this->db->update($updateQuery);
        }
    
        return $result;
    }
    
    // Hàm lấy tên đáp án dựa trên nhãn đáp án
    private function get_answer_label($question_id, $selected_answer_label) {
        $answer_column = "";
        switch ($selected_answer_label) {
            case 'answer1':
                $answer_column = 'answer1';
                break;
            case 'answer2':
                $answer_column = 'answer2';
                break;
            case 'answer3':
                $answer_column = 'answer3';
                break;
            // Thêm các trường hợp khác nếu cần
        }
    
        $query = "SELECT $answer_column FROM questions WHERE question_id = $question_id";
        $result = $this->db->select($query);
        $row = $result->fetch_assoc();
    
        return $row[$answer_column];
    }
     
    
}
class coupon {
    private $db;
    private $conn;
    public function __construct() {
        $this->db = new Database();
    }
    public function get_valid_coupon_code() {
        $timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $current_date = new DateTime('now', $timezone);
    
        $query = "SELECT code, expiry_date, quantity_coupon FROM coupon WHERE expiry_date > ? AND quantity_coupon > 0 ORDER BY RAND() LIMIT 1";
        $stmt = $this->db->link->prepare($query);
        $current_date_format = $current_date->format('Y-m-d H:i:s');
        $stmt->bind_param("s", $current_date_format);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
    
            $expiry_date = DateTime::createFromFormat('Y-m-d H:i:s', $row['expiry_date'], $timezone);
    
            if ($current_date < $expiry_date) {
                return $row['code'];
            }
        }
    
        return null;
    }
}
$answerLabels = array('answer1', 'answer2', 'answer3');
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["question_ids"]) && isset($_POST["answers"])) {
    require_once 'admin/database.php';
    $question_ids = $_POST["question_ids"];
    $answers = $_POST["answers"];
    $question = new question();

    $user_id = $_SESSION['id'];
    $hasSubmitted = false;
    foreach ($question_ids as $question_id) {
        $selected_answer_label = $answers[$question_id];
        
        // Kiểm tra xem đã submit chưa và lưu kết quả
        $submission_result = $question->save_survey_response($question_id, $selected_answer_label, $user_id);
        
        if ($submission_result) {
            $hasSubmitted = true;
        }
    }
    
    if ($hasSubmitted) {
        $coupon_code = "";
        $coupon = new coupon();
        $coupon_code = $coupon->get_valid_coupon_code();
        echo '<script>window.location.href = "survey_xuatcode.php?coupon_code=' . urlencode($coupon_code) . '";</script>';
    } else {
        echo '<script>alert("You have already submitted the survey.");</script>';
    }
}
$question = new question();
$questions = $question->show_question();

?>
<style>
     .khaosat{
        margin-top: 100px;
        margin-bottom: 10px;
     }
     .khaosat h1 {
        text-align: center;
        color: #444444;
            font-family: monospace;
            font-size: 45px;
     }
     .khaosat p{
        font-family: monospace;
            
            font-size: 20px;
            margin-left: 10%; 
            margin-right: 10%;
            text-align: center;
            margin-top: 20px;
     }
     .khaosat h4 {
        font-family: 'Courier New', Courier, monospace;
        text-align: center;
        color: #aaaaaa;
     }
.nut{
    font-family: monospace;
            
            font-size: 17px;
            margin-left: 10%; 
            margin-right: 10%;
            text-align: center;
            /* margin-top: 20px; */
}
input[type="submit"] {
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 30px; /* Khoảng cách giữa nút Submit và các radio buttons */
    margin-left: 47%
}


input[type="submit"]:hover {
    background-color: red;
}

</style>
<script>
function validateSurveyForm() {
    var isLoggedIn = <?php echo (isset($_SESSION['id']) ? 'true' : 'false'); ?>;
    
    if (!isLoggedIn) {
        alert('You need to be logged in to submit the survey.');
        return false;
    }
    var questions = document.querySelectorAll('.nut');

    for (var i = 0; i < questions.length; i++) {
        var answerRadios = questions[i].querySelectorAll('input[type="radio"]');
        var answered = false;

        for (var j = 0; j < answerRadios.length; j++) {
            if (answerRadios[j].checked) {
                answered = true;
                break;
            }
        }

        if (!answered) {
            alert('Please complete all surveys before submitting.');
            return false;
        }
    }

    return true;
}

</script>
<!-- --------------- survey_onclick ------------------------>
<style>
        /* Lớp mờ */
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 999;
        }

        #popup {
            display: none;
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px 30px 10px 40px;
            border-radius: 7px;
            width: 600px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        #popup h1 {
            text-align: center;
            color: #444444;
            font-family: monospace;
            font-size: 35px;
        }

        #popup p {
            font-family: monospace;
            line-height: 20px;
            font-size: 15px;
        }

        #closeButton {
            background-color: red;
            color: #fff;
            padding: 6px 18px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            width: 80px;
            margin-left: 40%;
        }
    </style>
</head>
<body>
    <!-- Lớp mờ -->
    <div id="overlay">

    <div id="popup">
        <h1>Dear Customers!</h1><br>
        <p>Welcome to DealZone survey site.</p>
        <p>We would like to collect your opinions on our future products.</p>
        <p>Even when you give us your opinion, you will receive a promotion code!</p>
        <p>We hope this little gift makes you happy during your purchasing.</p><br>
        <div id="closeButton">Close</div>
    </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const popup = document.getElementById("popup");
            const overlay = document.getElementById("overlay");
            const closeButton = document.getElementById("closeButton");

            closeButton.addEventListener("click", function() {
                popup.style.display = "none";
                overlay.style.display = "none";
            });

            // Hiển thị popup và lớp mờ khi trang web tải xong
            popup.style.display = "block";
            overlay.style.display = "block";
        });
    </script>
</body>
<!-- ----------------end survey-onclick --------------------- -->

<!------------------------------------------ survey --------------------------------------------->
<div class="khaosat">
    <h1>Send Us Opinion now!</h1>
    <h4>Please complete all surveys</h4><br>
    <form method="post" action="" onsubmit="return validateSurveyForm();">
        <?php foreach ($questions as $row) : ?>
            <p><?php echo $row['question']; ?></p>
            <div class="nut">
                <input type="hidden" name="question_ids[]" value="<?php echo $row['question_id']; ?>">
                <?php foreach ($answerLabels as $label) : ?>
                    <input type="radio" name="answers[<?php echo $row['question_id']; ?>]" value="<?php echo $label; ?>">
                    <?php echo $row[$label]; ?>
                <?php endforeach; ?>
            </div><br>
        <?php endforeach; ?>
        <input type="submit" value="Submit">
    </form>
</div>


<!------------------------------------------end-------------------------------------->
<?php
include "footer.php"
?>