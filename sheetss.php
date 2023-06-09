<?php
session_start();

// Set the default prompt value
$defaultPrompt = "
  ";

// Check if the form is submitted to update the prompt
if (isset($_POST['update_prompt'])) {
    $prompt = $_POST['prompt'];
    $_SESSION['prompt'] = $prompt;
} else {
    $_SESSION['prompt'] = $defaultPrompt; // Set the default prompt value if not updated
}

// Check that both the URL and docName are set
if (isset($_GET['url']) && isset($_GET['name'])) {
    $docId = $_GET['url'];
    $docName = $_GET['name'];
}
?>

<!DOCTYPE HTML>
<!--
    Forty by HTML5 UP
    html5up.net | @ajlkn
    Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
<head>
    <title>GradeFlow Test</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload">

    <!-- Wrapper -->
        <div id="wrapper">

            <!-- Header -->
                <header id="header" class="alt">
                    <a href="index.php" class="logo"><strong>GradeFlow</strong> <span>Test</span></a>
                    <nav>
                        
                    </nav>
                </header>



            <!-- Main -->
                <div id="main">

        

                    <!-- Two -->
                        <section id="two">
                            <div class="inner">
                                <header class="major">
                                    <h2>Sheets</h2>
                                </header>
                         <!DOCTYPE HTML>
<html>
<head>
    <title>GradeFlow Test</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body class="is-preload">

    <div id="wrapper">
        <header id="header" class="alt">
            <a href="index.php" class="logo"><strong>GradeFlow</strong> <span>Test</span></a>
            <nav></nav>
        </header>

        <div id="main">
            <section id="two">
                <div class="inner">
                    <header class="major">
                        <h2>Sheets</h2>
                    </header>
                    <h1>Google Sheets</h1>
                    <p>Sheets Name:</p>

                    <input type="text" id="docName" name="docName" value="<?= isset($_GET['name']) ? $_GET['name'] : '' ?>"><br>
                    <p>Sheets URL:</p>

                    <input type="text" id="docId" name="docId" value="<?= isset($_GET['url']) ? $_GET['url'] : '' ?>"><br>

                    <p>Each Question Points:</p>

                    <input type="text" id="points" name="points" value="7"><br>

                    <p>Statement:</p>

                    <input type="text" id="statement" name="statement" value="What?(Words)(Images)(Word Number)">
                    <hr>

                    <form action="sheetgrade.php?url=<?php echo $docId; ?>" method="post">
                        <input type="hidden" name="docId" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">
                        <input type="hidden" name="docName" value="<?= isset($_SESSION['docName']) ? $_SESSION['docName'] : '' ?>">
                        <p>Prompt:</p>
                        <textarea id="prompt" name="prompt" rows="6" cols="50"><?= isset($_SESSION['prompt']) ? $_SESSION['prompt'] : '' ?></textarea><br>
                        <p>Questions:</p>
                        <div id="questions">
                            <input type="text" name="question1" placeholder="Enter question 1" required><br>
                            <select name="operator1">
                                <option value="<">Less Than</option>
                                <option value=">">Greator Than </option>
                                <option value="==">Equal to  </option>
                            </select>
                           <select name="compareValue1" placeholder="Comparison Value" width="100px">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                              <option value="9">9</option>
                              <option value="10">10</option>
                            </select>
                           
                        </div>
                        <button type="button" id="add">Add More Questions</button>
                        <input type="submit" value="Grade Document">
                    </form>

                 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
  var i = 1;
  $('#add').click(function() {
    i++;
    $('#questions').append('<br> <input type="text" name="question' + i + '" placeholder="Enter question ' + i + '" required><select name="compare' + i + '"><option value="<">Less than</option><option value=">">Greater than</option><option value="=">Equal to</option></select><select name="compareValue' + i + '" placeholder="Comparison Value" style="width: 100px;"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option></select><br>');
  });
});
</script>


<div id="questions"></div>


                </div>
            </section>
        </div><div

        <section id="contact">
    <div class="inner">
        <div class="field half">
            <label for="name">Entire URL:</label>
            <input type="text" name="url" id="name" value="<?= isset($_GET['url']) ? $_GET['url'] : '' ?>" />
        </div></div>
        
        <ul class="actions">
            <li>
                <form action="https://docs.google.com/spreadsheets/d/<?= isset($_GET['url']) ? $_GET['url'] : '' ?>/edit" method="GET" target="_blank">
                    <input type="submit" value="Go to SHEETS" class="primary" />
                </form>
            </li>
        </ul>
    </div>
</section>


                <footer id="footer">
                    <div class="inner">
                        <ul class="icons">
                            <li><a href="#" class="icon brands alt fa-twitter"><span class="label">Twitter</span></a></li>
                            <li><a href="#" class="icon brands alt fa-facebook-f"><span class="label">Facebook</span></a></li>
                            <li><a href="#" class="icon brands alt fa-instagram"><span class="label">Instagram</span></a></li>
                            <li><a href="#" class="icon brands alt fa-github"><span class="label">GitHub</span></a></li>
                            <li><a href="#" class="icon brands alt fa-linkedin-in"><span class="label">LinkedIn</span></a></li>
                        </ul>
                        <ul class="copyright">
                            <li>&copy; Untitled</li><li>Design: <a href="https://html5up.net">HTML5 UP</a></li>
                        </ul>
                    </div>
                </footer>

            </div>
        </section>

    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.scrolly.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>

</body>
</html>
