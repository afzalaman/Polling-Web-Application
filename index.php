<?php
session_start();

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    echo $_SESSION['username'];
}

$jsonData = file_get_contents("polls.json");
$polls = json_decode($jsonData, true);

$activePoll = array();
$expiredPoll = array();
foreach ($polls as $poll) {
    if (strtotime($poll['expiry']) > time()) {
        $activePoll[] = $poll;
    } else {
        $expiredPoll[] = $poll;
    }
}

usort($activePoll, function($a, $b) {
    return ($b['id']) - ($a['id']);
});
usort($expiredPoll, function($a, $b) {
    return ($b['id']) - ($a['id']);
});
?>
<!DOCTYPE html>
<html>
<head>
    <title>Poll Application</title>
</head>
<body>
    <?php
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
        echo '<form method="post">';
        echo '<input type="submit" name="logout" value="Logout">';
        echo '</form>';
        }
    ?>
    <?php
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        echo '<form method="post">';
        echo '<input type="submit" name="login" value="Login/Register">';
        echo '</form>';
        }
    ?>
    <h1>Poll WEB APPLICATION</h1>
    <p>A web application where logged-in users can cast their votes on polls (questionnaires/forms)</p>
    <h2>Active Polls</h2>
    <ul>
    <?php foreach ($activePoll as $poll): ?>
        <li>Poll #<?php echo $poll['id']; ?> - <?php echo $poll['question']; ?>
            <?php echo $poll['startedAt']; ?> - <?php echo $poll['expiry']; ?>
            <a href="vote.php?poll_id=<?php echo $poll['id']; ?>">Vote</a>
            </li>
    <?php endforeach; ?>
    </ul>
    <h2>Expired Polls</h2>
    <ul>
    <?php foreach ($expiredPoll as $poll): ?>
            <li>Poll #<?php echo $poll['id']; ?> - <?php echo $poll['question']; ?>
            <?php echo $poll['startedAt']; ?> - <?php echo $poll['expiry']; ?>
            <a href="result.php?poll_id=<?php echo $poll['id']; ?>">Results</a>
            </li>
    <?php endforeach; ?>
    </ul>
    <?php 
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) 
    {
        echo '<a href="make.php" class="create-poll-button">Create Poll</a>';
    }
    ?>
</body>
</html>    