<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

$errors = [];
$poll_id = $_GET['poll_id'] ?? null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $options = $_POST['options'] ?? [];
    if (!$options) {
        $errors[] = 'You must select at least one option.';
    } else {
    }

    if (!$errors) {
        $message = 'SUCCESS';
    }
}

if(file_exists('polls.json')){
    $polls = json_decode(file_get_contents('polls.json'), true);
    $poll = null;
    foreach ($polls as $p) {
        if ($p['id'] == $poll_id) {
            $poll = $p;
            break;
        }
    }
    if (!$poll) {
        echo 'Poll not found.';
        exit;
    }
}else{
    echo 'Polls not found.';
    exit;
}

?>
<div>
    <h2>Poll: <?= htmlspecialchars($poll['question']) ?></h2>
    <p>expiry: <?= htmlspecialchars($poll['expiry']) ?></p>
    <p>Time of creation: <?= htmlspecialchars($poll['startedAt']) ?></p>
    <form method="post">
        <ul>
            <?php foreach ($poll['options'] as $opt): ?>
                <li>
                    <input type="<?= $poll['multiple_choice'] ? 'checkbox' : 'radio'?>" id="<?= $opt['id'] ?>" name="options[]" value="<?= $opt['id'] ?>" >
                    <label for="<?= $opt['id'] ?>"><?= htmlspecialchars($opt['text']) ?></label>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if ($errors): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if (!empty($message)): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>
        <input type="submit" value="Vote">
    </form>
<div>
    <a href="index.php">Back to polls</a>
</div>
</div>
<script>
var multiple_choice = <?= $poll['multiple_choice'] ?>;
if(!multiple_choice){
    var radios = document.getElementsByName('options[]');
    for (var i = 0, length =radios.length; i < length; i++){
        radios[i].onclick = function() {
for (var i = 0; i < radios.length; i++) {
radios[i].checked = false;
}
this.checked = true;
};
}
}
</script>
<?php

if(isset($_POST['options'])) {
    if(empty($_POST['options'])) {
        $errors[] = "Please select an option.";
    } else {
    }

    if (!$errors) {
        $message = 'SUCCESS';
    }
}

if(file_exists('polls.json')){
    $polls = json_decode(file_get_contents('polls.json'), true);
    $poll = null;
    foreach ($polls as $p) {
        if ($p['id'] == $poll_id) {
            $poll = $p;
            break;
        }
    }
    if (!$poll) {
        echo 'Poll not found.';
        exit;
    }
}else{
    echo 'Polls not found.';
    exit;
}

?>