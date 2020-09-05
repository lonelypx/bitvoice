<?php $graph_url= "https://graph.facebook.com/v5.0/100282594870233/feed";
  $postData = "&access_token=EAACjU4if86oBAARqUZBiocWwziJvdRrBU9E5C5GuMUYsYgrrLoJY2miSN6ZAtyG8UkZAL3k84AAQVZC9D0ZBXVXbhHiW1tdTsLa44bpGrJNEHv8dYzqKO0nfN8ygBSCh40MF8zWQmKZC5ZCxplrEYAMq5QQAmspxkQjKerRixwuN1ZCZAWr8UAy7dC4fvfIivzElpPHofZAeJOx4YL5YE1mJeL" ;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $graph_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $output = curl_exec($ch);
		print_r($output);

        curl_close($ch);
		?>