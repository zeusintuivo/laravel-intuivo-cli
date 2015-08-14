­<?php


class MyStream {
    protected $buffer;

    function stream_open($path, $mode, $options, &$opened_path) {
        // Has to be declared, it seems...
        return true;
    }

    public function stream_write($data) {
        // Extract the lines ; on y tests, data was 8192 bytes long ; never more
        $lines = explode("\n", $data);

        // The buffer contains the end of the last line from previous time
        // => Is goes at the beginning of the first line we are getting this time
        $lines[0] = $this->buffer . $lines[0];

        // And the last line os only partial
        // => save it for next time, and remove it from the list this time
        $nb_lines = count($lines);
        $this->buffer = $lines[$nb_lines-1];
        unset($lines[$nb_lines-1]);

        // Here, do your work with the lines you have in the buffer
        var_dump($lines);
        echo '<hr />';

        return strlen($data);
    }
}

// Register the wrapper, to be used with the pseudo-protocol "test"
stream_wrapper_register("test", "MyStream") or die("Failed to register protocol");
 
// Open the "file"
$fp = fopen("test://MyTestVariableInMemory", "r+");
 
// Configuration of curl
$ch = curl_init(); c
url_setopt($ch, CURLOPT_URL, "http://www.rue89.com/");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_BUFFERSIZE, 256);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FILE, $fp); // Data will be sent to our stream ;-)
curl_exec($ch);
curl_close($ch);
// Don't forget to close the "file" / stream
fclose($fp);