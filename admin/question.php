<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/question_class.php";
?>
<?php
$question = new question();
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['question_id'])) {
    $question_id = $_GET['question_id'];
    $question->delete_question($question_id);
    echo "<script>window.location.href = 'question.php';</script>";
    exit;
}
    $questions = $question->show_question();
    
?>
<link rel="stylesheet" type="text/css" href="styles.css">
<style>
 /* styles.css */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    /* background-color: #f5f5f5; */
}

.container {
    max-width: 1260px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #3300FF;
}

h3 {
    text-align: right;
    margin-right: 30px;
}

table.question-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 10px;
    text-align: center;
}

th {
    background-color: #f2f2f2;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

.btn-delete {
    display: inline-block;
    padding: 5px 10px;
    text-decoration: none;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    background-color: #e74c3c;
}
.btn-detail {
    display: inline-block;
    padding: 5px 10px;
    text-decoration: none;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    background-color: #3498db;
}

.btn-detail:hover,
.btn-delete:hover {
        background-color: #2980b9;
    }

</style>
<body>
    <div class="container">
    <h1>Questions List</h1>
    <h3><a href="question_create.php">Create Question</a></h3>
    <table class="question-table" border="1">
        <tr>
        <th>STT</th>
            <th>Question</th>
            <th>Answer 1</th>
            <th>Quantity 1</th>
            <th>Answer 2</th>
            <th>Quantity 2</th>
            <th>Answer 3</th>
            <th>Quantity 3</th>
            <th>Action</th>
        </tr>
        <?php $stt=1; foreach ($questions as $row) : ?>
            <tr>
            <td><?php echo $stt++; ?></td>
                <td><?php echo $row['question']; ?></td>
                <td><?php echo $row['answer1']; ?></td>
                <td><?php echo $row['quantity_answer1']; ?></td>
                <td><?php echo $row['answer2']; ?></td>
                <td><?php echo $row['quantity_answer2']; ?></td>
                <td><?php echo $row['answer3']; ?></td>
                <td><?php echo $row['quantity_answer3']; ?></td>
                <td>
                <a class="btn-detail" href="question_detail.php?question_id=<?php echo $row['question_id']; ?>">Detail</a>
                <a class="btn-delete" href="question.php?action=delete&question_id=<?php echo $row['question_id']; ?>" onclick="return confirm('Are you sure you want to delete this question?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    </div>
</body>
<?php
    include "footer.php";
?>