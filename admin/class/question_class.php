<?php
    include 'database.php';

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
    public function delete_question($question_id) {
        $query = "DELETE FROM questions WHERE question_id = '$question_id'";
        $result = $this->db->delete($query);
        header('Location: question.php');
        return $result;
    }
    public function save_survey_response($question_id, $selected_answer, $user_id) {
        // Get user information from the users table based on user_id
        $user_info_query = "SELECT username, email FROM users WHERE id = $user_id";
        $user_info_result = $this->db->select($user_info_query);
        $user_info = $user_info_result->fetch_assoc();

        $user_name = $user_info['username'];
        $email = $user_info['email'];

        $query = "INSERT INTO survey_responses (question_id, selected_answer, username, email, user_id)
                  VALUES ('$question_id', '$selected_answer', '$username', '$email', '$user_id')";
        
        $result = $this->db->insert($query);

        if ($result) {
            $updateColumn = "quantity_" . $selected_answer;
            $updateQuery = "UPDATE questions SET $updateColumn = $updateColumn + 1 WHERE question_id = $question_id";
            $this->db->update($updateQuery);
        }

        return $result;
    }
    public function get_responses_by_question_id($question_id) {
        $query = "SELECT * FROM survey_responses WHERE question_id = '$question_id'";
        $result = $this->db->select($query);
        $responses = array();
    
        while ($row = $result->fetch_assoc()) {
            $responses[] = $row;
        }
    
        return $responses;
    }
    public function isQuestionExists($questionText) {
        $query = "SELECT COUNT(*) AS total FROM questions WHERE question = '$questionText'";
        $result = $this->db->select($query);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'] > 0; // Trả về true nếu có câu hỏi tồn tại
        }
    
        return false; // Trả về false nếu có lỗi hoặc không tìm thấy câu hỏi
    }
    
}
?>