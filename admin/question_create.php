<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/question_class.php";
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_id = null;
    $questionText = $_POST["question"];
    $answer1 = $_POST["answer1"];
    $answer2 = $_POST["answer2"];
    $answer3 = $_POST["answer3"];

    $questionObject = new question();
    $insertResult = $questionObject->insert_question($question_id, $questionText, $answer1, $answer2, $answer3);
    if ($insertResult) {
        echo "<script>window.location.href = 'question.php';</script>";
    } else {
        echo "Error saving question and answers.";
    }
}
?>
<link rel="stylesheet" type="text/css" href="styles.css">
<style>
        /* styles.css */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
}

.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
    color: red;
}

form {
    text-align: center;
}

label, input {
    display: block;
    margin-bottom: 10px;
}

input[type="text"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
}

input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 3px;
    cursor: pointer;
    margin-left: 42%;
}

input[type="submit"]:hover {
    background-color: red;
    color: black;
}

    </style>
<body>
    <div class="container">
        <h1>Create New Question</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="question">Question:</label>
            <input type="text" name="question" required>
            <label for="answer1">Answer 1:</label>
            <input type="text" name="answer1" required>
            <label for="answer2">Answer 2:</label>
            <input type="text" name="answer2" required>
            <label for="answer3">Answer 3:</label>
            <input type="text" name="answer3" required>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
<?php
    include "footer.php";
?>