<?php
$poll_id = $_GET['poll_id'];
$poll_data = json_decode(file_get_contents("polls.json"), true);

$question = "";
$options = array();
foreach ($poll_data as $poll) {
    if ($poll_id == $poll['id']) {
        $question = $poll['question'];
        $options = $poll['options'];
        break;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Poll Results</title>
</head>
<body>
    <h1>Poll Results</h1>
    <h2><?php echo $question; ?></h2>
    <ul>
    <?php 
    foreach ($options as $option) {
        echo "<li>".$option['text']." <span class='vote-count'>".$option['count']." votes</span></li>";
    }
    ?>
    </ul>
    <div>
        <a href="index.php">Back to Polls</a>
    </div>
</body>
</html>