<?php

define('TELEGRAM_TOKEN', '');
define('TELEGRAM_MESSAGE_ID', '');

function send_msg($text) {
	$postData = array(
		'chat_id=' . TELEGRAM_MESSAGE_ID,
		'text=' . urlencode($text),
        'parse_mode=html'
    );

    $query = implode('&', $postData);
    
    $url = 'https://api.telegram.org/bot' . TELEGRAM_TOKEN . '/sendMessage?' . $query;

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$headers = array();
	$headers[] = 'Accept: application/json';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);

	return $result;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$callback_data = file_get_contents("php://input");
	$data = json_decode($callback_data);
    $type_event = $_SERVER['HTTP_X_GITHUB_EVENT'];
    $action = $data->action;

    $msg = "Event: " . strtoupper($type_event) . ' - [' . strtoupper($action) . "] \n";
    $msg .= "Repository:  " . $data->repository->name . " (" .$data->repository->html_url. ")\n";

    switch($type_event) {
        case 'push':
            $msg .= "Commits:\n";
            $commit_id = 1;
            foreach($data->commits as $commit) {
                $msg .= "- by " . $commit->committer->name . " with message: " . $commit->message . "\n";
                $commit_id++;
            }
            $msg .= "Pushed by: " . $data->pusher->name . "\n";
            break;
        case 'pull_request':
            $msg .= "Pull request: '" . $data->pull_request->title . "' was " . $data->pull_request->state . " by " . $data->pull_request->user->login . "\n";
            $msg .= "URL: " . $data->pull_request->html_url . "\n";
            break;
        case 'pull_request_review':
            $msg .= "Pull request: '" . $data->pull_request->title . "' was " . $data->review->state . " by " . $data->review->user->login . "\n";
            $msg .= "URL: " . $data->pull_request->html_url . "\n";
            break;
        case 'issues';
            $msg .= "Issue: " . $data->issue->title . " \nBy: " . $data->issue->user->login . " \n";
            $msg .= "URL: " . $data->issue->html_url . "\n";
        case 'issue_comment';
            $msg .= "Comment in Issue: " . $data->issue->title . " \nBy: " . $data->issue->user->login . " \n";
            $msg .= "URL: " . $data->issue->html_url . "\n";
        default:
            // nothing
    }

    $send = send_msg($msg);
    print_r($send);
} else {
    // do nothing lol :)
}