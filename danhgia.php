<?php
class danhgia
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insert_danhgia($danhgia_id, $product_id, $user_id, $name, $email, $rating, $comment, $created_at)
    {
        $user_info_query = "SELECT fullname, email FROM users WHERE id = $user_id";
        $user_info_result = $this->db->select($user_info_query);
        $user_info = $user_info_result->fetch_assoc();

        $name = $user_info['fullname'];
        $email = $user_info['email'];
        $query = "INSERT INTO danhgia (danhgia_id, product_id, user_id, name, email, rating, comment, created_at) 
                  VALUES ('$danhgia_id', '$product_id', '$user_id', '$name', '$email', '$rating', '$comment', '$created_at')";

        $result = $this->db->insert($query);
        // header('Location: coupon.php');
        return $result;
    }
    public function show_danhgia()
    {
        $query = "SELECT danhgia_id, product_id, name, email, rating, comment, created_at FROM danhgia ORDER BY danhgia_id ASC";
        $result = $this->db->select($query);

        return $result;
    }
}
$danhgia = new danhgia();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'admin/database.php'; // Đảm bảo đường dẫn đúng

    $db = new Database();
    $product_id = isset($_GET['id']) ? $_GET['id'] : null;
    $user_id = $_POST["user_id"];
    $rating = $_POST["rating"];
    $comment = $_POST["comment"];
    $created_at = date('Y-m-d H:i:s');

    $user_id = $_SESSION['id'];
    // Kiểm tra xem người dùng đã submit đánh giá chưa cho sản phẩm này
    $submission_query = "SELECT danhgia_id FROM danhgia WHERE user_id = $user_id AND product_id = $product_id";
    $submission_result = $db->select($submission_query);
    if ($submission_result && $submission_result->num_rows > 0) {
        // Người dùng đã submit rồi, không thực hiện gì cả
        $hasSubmitted = true;
    } else {
        // Người dùng chưa submit, thực hiện lưu đánh giá mới
        $insert_result = $danhgia->insert_danhgia(null, $product_id, $user_id, '', '', $rating, $comment, $created_at);
        if ($insert_result) {
            $hasSubmitted = true;
        }
    }
}
$reviews = $danhgia->show_danhgia($product_id);
$reviewCount = 0;
$reviewArray = array();

if ($reviews) {
    // Truy vấn thành công
    $reviewArray = array_reverse(mysqli_fetch_all($reviews, MYSQLI_ASSOC));
    foreach ($reviewArray as $review) {
        // Chỉ đếm các đánh giá của sản phẩm có product_id trùng khớp
        if ($review['product_id'] == $product_id) {
            $reviewCount++;
        }
    }
}

?>
<link rel='stylesheet prefetch' href='https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css'>
<style>
    .body_danhgia {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .container {
        width: 80%;
        margin: auto;
        overflow: hidden;
    }

    .danhgia {
        background-color: #fff;
        padding: 20px;
        margin-top: 20px;
        border-radius: 5px;
    }

    div.stars {
        width: 270px;
        display: inline-block;
    }

    input.star {
        display: none;
    }

    label.star {
        float: right;
        padding: 10px;
        font-size: 36px;
        color: #444;
        transition: all .2s;
    }

    input.star:checked~label.star:before {
        content: '\f005';
        color: #FD4;
        transition: all .25s;
    }

    input.star-5:checked~label.star:before {
        color: #FE7;
        text-shadow: 0 0 20px #952;
    }

    input.star-1:checked~label.star:before {
        color: #F62;
    }

    label.star:hover {
        transform: rotate(-15deg) scale(1.3);
    }

    label.star:before {
        content: '\f006';
        font-family: FontAwesome;
    }

    /* Additional CSS for the form and review section */
    .danhgia {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
    }

    .existing-reviews {
        margin-top: 20px;
    }

    .review {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 15px;
    }

    /* CSS for the comment textarea */
    label[for="comment"] {
        display: block;
        margin-top: 10px;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
    }

    textarea[name="comment"] {
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 5px;
        max-width: 600px;
        margin-left: 24%;
        padding: 20px;
    }

    /* CSS for the submit button */
    .submit-container {
        text-align: center;
        margin-top: 20px;
    }

    .send{
        background-color: #007BFF;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;
        margin-left: 47%;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    span.star {
        color: #FFCC00;
    }
</style>
<script>
function validateSurveyForm() {
    var isLoggedIn = <?php echo (isset($_SESSION['id']) ? 'true' : 'false'); ?>;
    var ratingValue = document.querySelector('input[name="rating"]:checked');
    
    if (!isLoggedIn) {
        alert('You need to be logged in to submit the survey.');
        return false;
    }
    if (!ratingValue) {
        alert('Please select a rating before submitting.');
        return false;
    }
}
</script>
</head>
<div clas="body_danhgia">
    <div class="container">
        <div class="danhgia">
            <div class="existing-reviews">
                <h2><?php echo $reviewCount; ?> Comment</h2>
                <?php
                if ($reviewCount > 0) {
                    foreach ($reviewArray as $review) {
                        // Chỉ hiển thị các đánh giá của sản phẩm có product_id trùng khớp
                        if ($review['product_id'] == $product_id) {
                ?>
                            <div class="review">
                                <p><strong>Name:</strong> <?php echo $review['name']; ?> - <?php echo date('M d,Y', strtotime($review['created_at'])); ?></p>
                                <p>
                                    <?php
                                    $ratingValue = intval($review['rating']);
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $ratingValue) {
                                            echo '<span class="star star-' . $i . '">&#9733;</span>';
                                        } else {
                                            echo '<span class="star star-' . $i . '">&#9734;</span>';
                                        }
                                    }
                                    ?>
                                </p>
                                <p><?php echo isset($review['comment']) ? $review['comment'] : ''; ?></p>
                            </div>
                <?php
                        }
                    }
                } else {
                    echo "<p></p>";
                }
                ?>
            </div>
            <!-- Review form -->

            <h2>Leave a comment</h2>
            <?php
if (!isset($_SESSION['id'])) {
    echo "<p>You need to <big><b><a href='login.php'>Login</a></b></big> to leave a comment.</p>";
} else {
    // Check if user has purchased the product
    $product_id = isset($_GET['id']) ? $_GET['id'] : null;
    $user_id = $_SESSION['id'];

    // Check if the user has purchased the product
    $purchase_query = "SELECT * FROM tbl_order_items WHERE product_id = $product_id";
    $purchase_result = $db->select($purchase_query);

    if ($purchase_result && $purchase_result->num_rows > 0) {
        $purchase_data = $purchase_result->fetch_assoc();
        $order_id = $purchase_data['order_id'];

        // Check if the order status is 'delivered'
        $order_status_query = "SELECT * FROM tbl_order WHERE order_id = $order_id AND order_status = 'delivered'";
        $order_status_result = $db->select($order_status_query);

        if ($order_status_result && $order_status_result->num_rows > 0) {
  // User can leave a review
  ?>
  <!-- Review form -->
  <form class="review-form" action="" method="POST">
      <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
      <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <!-- <label for="name">Name:</label>
                <input type="text" name="name" value="" required>
                <label for="email">Email:</label>
                <input type="email" name="email" value="" required> -->
                <label for="rating">Rating:</ <div class="stars">
                    <input class="star star-5" id="star-5" type="radio" name="rating" value="5" />
                    <label class="star star-5" for="star-5"></label>
                    <input class="star star-4" id="star-4" type="radio" name="rating" value="4" />
                    <label class="star star-4" for="star-4"></label>
                    <input class="star star-3" id="star-3" type="radio" name="rating" value="3" />
                    <label class="star star-3" for="star-3"></label>
                    <input class="star star-2" id="star-2" type="radio" name="rating" value="2" />
                    <label class="star star-2" for="star-2"></label>
                    <input class="star star-1" id="star-1" type="radio" name="rating" value="1" />
                    <label class="star star-1" for="star-1"></label>
                </div>
                
                <label for="comment">Comment:</label>
                <textarea name="comment" rows="4" required></textarea>
                <br>
                <button class="send" type="submit" name="submit_danhgia" onclick="return validateSurveyForm();">Send</button>
            </form>
            <?php
        } else {
            echo "<p>You can only leave a review for products that have been delivered.</p>";
        }
    } else {
        echo "<p>You can only leave a review for products you've purchased.</p>";
    }
}
?>
        </div>
    </div>
</div>

