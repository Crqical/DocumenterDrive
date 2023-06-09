<?php
require_once 'vendor/autoload.php';

session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
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
										<h2>Documents</h2>
									</header>
									<p><?php

if (isset($_SESSION['docName'])) {
    $docName = $_SESSION['docName'];
} else {
    die("Error: docName not set in session data.");
}

$jsonFile = $docName . '.json';
$content = '';
if (file_exists($jsonFile)) {
    $jsonContent = file_get_contents($jsonFile);
    $jsonArray = json_decode($jsonContent, true);

    foreach ($jsonArray as $item) {
        if (strpos($item['name'], 'CURRENT') !== false) {
            $content = $item['content'];
            break;
        }
    }
} else {
    die("Error: JSON file not found.");
}

if (empty($content)) {
    die("Error: No content found in JSON.");
}

// Build the questions for the prompt and question map
$questions = '';
$questionMap = [];
$questionNumber = 1;
foreach ($_POST as $key => $value) {
    if (strpos($key, 'question') !== false) {
        $questions .= '(' . $value . '), ';
        $questionMap[$questionNumber] = $value;
        $questionNumber++;
    }
}
$questions = rtrim($questions, ', ');

// Collect the prompt from POST
$prompt = isset($_POST['prompt']) ? $_POST['prompt'] . ' ' . $questions : '';

$apiKey = "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";
$model = "text-davinci-003";
$temperature = 0.7;
$maxTokens = 256;
$topP = 1;
$frequencyPenalty = 0;
$presencePenalty = 0;

$data = array(
    'model' => $model,
    'prompt' => $prompt . ' ' . $content,
    'temperature' => $temperature,
    'max_tokens' => $maxTokens,
    'top_p' => $topP,
    'frequency_penalty' => $frequencyPenalty,
    'presence_penalty' => $presencePenalty
);

$ch = curl_init();
$jsonResponse = null;

do {
    curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/completions"); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $apiKey));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        die('Error: ' . curl_error($ch));
    }

    $jsonResponse = json_decode($response, true);
    
} while (json_last_error() != JSON_ERROR_NONE);
if (isset($jsonResponse['error'])) {
    die('API Error: ' . $jsonResponse['error']['message']);
}

$generatedText = '';

if (isset($jsonResponse['choices']) && count($jsonResponse['choices']) > 0 && isset($jsonResponse['choices'][0]['text'])) {
    $generatedText = $jsonResponse['choices'][0]['text'];

    // Extract the scores from the generated text
    $pattern = '/Question (\d+) \/ Score: (\d{1,2})/';
    preg_match_all($pattern, $generatedText, $matches, PREG_SET_ORDER);

    // Display the Chat GPT response
    echo "<p>Chat GPT Response:</p>";
    echo "<p>$generatedText</p>";

    $scoreData = array();
    preg_match_all('/"questionID(\d+)": \{"score": (\d+),/', $jsonResponse['choices'][0]['text'], $matches);

    foreach ($matches[1] as $index => $questionNumber) {
        $score = (int) $matches[2][$index];
        $scoreData[$questionNumber] = $score;
    }

    $output = array();
    foreach ($questionMap as $questionNumber => $questionData) {
        if (isset($scoreData[$questionNumber]) && isset($questionData['compare']) && isset($questionData['compareValue'])) {
            $compare = $questionData['compare'];
            $compareValue = (int) $questionData['compareValue'];
            $score = $scoreData[$questionNumber];

            $output["questionID{$questionNumber}"] = array(
                'promptScore' => $score,
                'jsonScore' => $jsonResponse['choices'][0]['text']["q{$questionNumber}"]["score"],
            );
        }
    }

    echo "<h2>Score Data:</h2>";
    echo "<pre>" . htmlspecialchars(json_encode($output, JSON_PRETTY_PRINT)) . "</pre>";

    // Save Chat GPT response as a JSON file
    $jsonFilePath = __DIR__ . '/chat_gpt_responses.json';
    $jsonResponseTrimmed = preg_replace('/\s+/', ' ', $generatedText);
    $jsonContent = substr($jsonResponseTrimmed, strpos($jsonResponseTrimmed, '{'));
    file_put_contents($jsonFilePath, $jsonContent);
    echo "<p>Chat GPT response has been saved as a JSON file: <code>$jsonFilePath</code></p>";

    $score = 0; // Set the initial score to 0

   
    $score = 0;

    $operator1 = $_POST['operator1'] ?? null;  // Use null coalescing operator for cleaner syntax
    $comparevalue1 = $_POST['comparevalue1'] ?? null;  // Use null coalescing operator for cleaner syntax

    $output = array(
        'score' => $score,
        'operator1' => $operator1
    );

    echo "<hr>";
    echo json_encode($output);
    echo "<hr>";

    // Load the JSON data from a file
    $jsonFilePath = __DIR__ . '/chat_gpt_responses.json';
    $jsonString = file_get_contents($jsonFilePath);
    $jsonData = json_decode($jsonString, true);

    // Iterate over the parsed data and print the scores
    foreach ($jsonData as $question) {
        if (isset($question['score'])) {
            echo "Score: " . $question['score'] . "<br>";
        }
    }

    echo "<hr>";

    $questionId = 1;
    while (true) {
        $operatorKey = "operator{$questionId}";
        $compareValueKey = "compareValue{$questionId}";

        if (isset($_POST[$operatorKey]) && isset($_POST[$compareValueKey])) {
            $operator = $_POST[$operatorKey];
            $compareValue = intval($_POST[$compareValueKey]);

            foreach ($jsonData as $question) {
                if (isset($question['score'])) {
                    $equation = "{$question['score']} $operator $compareValue";

                    if (eval("return $equation;")) {
                        echo "PASS because {$question['score']} is {$operator} than {$compareValue}<br>";
                    } else {
                        echo "FAIL because {$question['score']} is not {$operator} than {$compareValue}<br>";
                    }
                }
            }
        } else {
            // if we can't find both operatorX and compareValueX, then we break the loop
            break;
        }

        $questionId++;
    }
}
  
    curl_close($ch);
?>





                    
</p>
									<ul class="actions">
									</ul>
								</div>
							</section>

					</div>

			

				<!-- Footer -->
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

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>