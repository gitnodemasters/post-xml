<?php

function postXML($xml)
{
    $url = 'http://10.0.2.230/jobs';
    // $data = array('key1' => 'value1', 'key2' => 'value2');
    $data = $xml;

    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { /* Handle error */
    }

    var_dump($result);
}

function postXML1($xml)
{

    //The URL that you want to send your XML to.
    $url = 'http://10.0.2.230/jobs';

    //Initiate cURL
    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_HEADER, 1);

    //Set the Content-Type to text/xml.
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));

    //Set CURLOPT_POST to true to send a POST request.
    curl_setopt($curl, CURLOPT_POST, true);

    //Attach the XML string to the body of our request.
    curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);

    //Tell cURL that we want the response to be returned as
    //a string instead of being dumped to the output.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    //Execute the POST request and send our XML.
    $result = curl_exec($curl);

    //Do some basic error checking.
    if (curl_errno($curl)) {
        throw new Exception(curl_error($curl));
    }

    //Close the cURL handle.
    curl_close($curl);

    //return the response output.
    return $result;
}

function postXML2($input_xml)
{
    $url = 'http://10.0.2.230/jobs';

    //setting the curl parameters.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    // Following line is compulsary to add as it is:
    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        "xmlRequest=" . $input_xml
    );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    $data = curl_exec($ch);
    curl_close($ch);

    //convert the XML result into array
    //$array_data = json_decode(json_encode(simplexml_load_string($data)), true);

    print_r('<pre>');
    print_r($data);
    print_r('</pre>');
}

function getVideos($path)
{
    $connection = ssh2_connect('10.0.2.230', 22);
    ssh2_auth_password($connection, 'dupdev', '86DLgO');

    $sftp = ssh2_sftp($connection);
    $sftp_fd = intval($sftp);

    $paths = [];

    $handle = opendir("ssh2.sftp://$sftp_fd$path");
    while (false != ($entry = readdir($handle))) {
        if (preg_match("/\.(mov|mp4|avi)$/", $entry)) {
            // $entry is in the right format
            // echo "$path$entry\n";
            array_push($paths, "$path$entry");
        }
    }

    

    $data['result'] = $paths;
    // echo json_encode($data);
    return $paths;
}

function getVideoPaths($path)
{
    $connection = ssh2_connect('10.0.2.230', 22);
    ssh2_auth_password($connection, 'dupdev', '86DLgO');

    $sftp = ssh2_sftp($connection);
    $sftp_fd = intval($sftp);

    $paths = [];

    $handle = opendir("ssh2.sftp://$sftp_fd$path");
    while (false != ($entry = readdir($handle))) {
        if (preg_match("/\.(mov|mp4|avi)$/", $entry)) {
            // $entry is in the right format
            // echo "$path$entry\n";
            array_push($paths, "$path$entry");
        }
    }

    

    $data['result'] = $paths;
    echo json_encode($data);
    // return $paths;
}

function getPaths($path)
{
    $connection = ssh2_connect('10.0.2.230', 22);
    ssh2_auth_password($connection, 'dupdev', '86DLgO');

    $sftp = ssh2_sftp($connection);
    $sftp_fd = intval($sftp);

    $paths = [];

    $handle = opendir("ssh2.sftp://$sftp_fd$path");
    while (false != ($entry = readdir($handle))) {
        if ($entry != '.' && $entry != '..') {
            $paths[] = $entry;
        }
        // array_push($paths, "$path$entry");
    }

    // $paths = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
    $data['result'] = $paths;
    echo json_encode($data);
}

if (isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    $path = $_POST['path'];
    switch ($action) {
        case 'getPath':
            getPaths($path);
            break;
        case 'getVideos':
            getVideoPaths($path);
            break;
        // case 'createJobs':
        //     createJobs($path);
        //     break;
            // ...etc...
    }
}
