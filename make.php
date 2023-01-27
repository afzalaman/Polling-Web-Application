<?php
session_start();
$error = "";

if(isset($_POST['create'])) {
    if(empty($_POST['question']) || empty($_POST['options']) || empty($_POST['expiry'])) {
        $error = "All fields are required";
    } else {
        $polls = array();
        if(file_exists('polls.json')){
            $polls = json_decode(file_get_contents('polls.json'), true);
        }
        $options = explode("\n", $_POST['options']);
        $options = array_map('trim', $options);
        $options_array = array();
        for($i=0; $i<count($options); $i++){
            $options_array[] = array("id"=>$i+1,"text"=>$options[$i],"count"=>0);
        }
        $id = count($polls) + 1;
        $polls[] = [
            'id' => $id,
            'question' => $_POST['question'],
            'options' => $options_array,
            'multiple_choice' => (isset($_POST['multiple_choice'])) ? true : false,
            'expiry' => $_POST['expiry'],
            'startedAt' => date("Y-m-d")
        ];
        file_put_contents('polls.json', json_encode($polls));
        $success = "Poll created successfully";
    }
}
?>
<form method="post">
    <div>
        <label for="question">Question:</label>
        <textarea name="question" id="question"></textarea>
    </div>
    <div>
        <label for="options">Options:</label>
        <textarea name="options" id="options"></textarea>
        <p>Enter one option per line</p>
    </div>
    <div>
        <input type="checkbox" name="multiple_choice" id="multiple_choice">
        <label for="multiple_choice">Allow multiple choices</label>
    </div>
    <div>
        <label for="expiry">expiry:</label>
        <input type="date" name="expiry" id="expiry">
    </div>
    <div>
        <input type="submit" name="create" value="Create Poll">
    </div>
    <a href="index.php">Back to home</a>
    
    <?php if(isset($error)): ?>
    <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <?php if(isset($success)): ?>
    <div class="success"><?= $success ?></div>
    <?php endif; ?>
</form>