<?php
    include_once("functions.php");


    if (array_key_exists('jobs', $_POST)) {
        $jobs = createJobs();
        echo "<script type='text/javascript'>console.log('$jobs');</script>";
    }

    function createJobs()
    {
        $videos = $_POST['video-names'];
        if ($videos == '') {
            echo "<script type='text/javascript'>console.log('Select Video File!');</script>";
            return;
        }
        $videos_arr = explode(',', $videos);
        $in_path = $_POST['input-path'];
        $out_path = $_POST['output-path'];
        $bitrate = $_POST['bitrate'];

        // if(is_numeric($bitrate) && ($bitrate > 0)) {
        //     echo 'bitrate : '. $bitrate*1000000;
        //     return;
        // }

        $count = count($videos_arr);
        
        if ($_FILES['preset']['name'] != '') {
            $xml_str = file_get_contents($_FILES['preset']['tmp_name']);
            $xml = simplexml_load_string($xml_str, "SimpleXMLElement", LIBXML_NOCDATA);
            $xml->output_group->file_group_settings->destination->uri = $out_path;
            if(is_numeric($bitrate) && ($bitrate > 0)) {
                $xml->stream_assembly->video_description->h264_settings->bitrate = $bitrate * 1000000;
            }

            $jobs = [];
            for ($i = 0; $i < $count; $i++) {
                $xml->input->file_input->uri = $in_path.$videos_arr[$i];
                //$xml->asXml();
                array_push($jobs, postXML1($xml->asXml()));
                sleep(3);
            }
            return $jobs;
            //echo '<pre>'; echo $xml->output_group->file_group_settings->destination->uri; echo '</pre>';
        } else {
            echo "<script type='text/javascript'>console.log('Select XML File!');</script>";
        }
    }
?>

<html>
  <title>Duplitech Multiple Post</title>
  <head>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <style>
      html, body {
      display: flex;
      justify-content: center;
      font-family: Roboto, Arial, sans-serif;
      font-size: 15px;
      }
      form {
      border: 5px solid #f1f1f1;
      }
      input[type=text], input[type=password] {
      width: 100%;
      padding: 16px 8px;
      margin: 8px 0;
      display: inline-block;
      border: 1px solid #ccc;
      box-sizing: border-box;
      }
      input[type=button], input[type=submit], #file-upload-button {
      background-color: #4286f4;
      color: white;
      padding: 14px 0;
      margin: 10px 0;
      border: none;
      cursor: grab;
      width: 48%;
      }
      .icon {
      font-size: 110px;
      display: flex;
      justify-content: center;
      color: #4286f4;
      }
      button {
      background-color: #4286f4;
      color: white;
      padding: 14px 0;
      margin: 10px 0;
      border: none;
      cursor: grab;
      width: 48%;
      }
      h1 {
      text-align:center;
      fone-size:18;
      }
      button:hover {
      opacity: 0.8;
      }
      .formcontainer {
      text-align: center;
      margin: 24px 50px 12px;
      }
      .container {
      padding: 16px 0;
      text-align:left;
      }
      span.psw {
      float: right;
      padding-top: 0;
      padding-right: 15px;
      }
      /* Change styles for span on extra small screens */
      @media screen and (max-width: 300px) {
      span.psw {
      display: block;
      float: none;
      }
      }
        ul {
            height: 200px;
            width: 500px;
        }

        ul {
            overflow: hidden;
            overflow-y: scroll;
        }

    </style>
  </head>
  <body>
    <!-- Simple pop-up dialog box containing a form -->
    <dialog id="favDialog">
        <form method="dialog">
            <p>
            <input type="text" id="input" name="" value="/mnt/" disabled style="width: 85%;">
            <input type="button" id="previous" value="<" style="width: 10%;"></p>
            <ul id="parent-list">
                <li id="a">Item A</li>
                <li id="b">Item B</li>
                <li id="c">Item C</li>
            </ul><br>
            <menu>
            <!-- <input type="button" id="select" value="Select"> -->
            <button id="confirmBtn" value="default">Select</button>
            </menu>
        </form>
    </dialog>

    <form method="post" enctype='multipart/form-data'>
        <h1>Duplitech Multiple Post</h1>
        <div class="formcontainer">
            <div class="container">
                <p>
                    <label for=""><strong>Input Path</strong></label>
                    <input type="text" name="input-path" id="input-path" value="/mnt/" >
                    <input type="button" id="open" name="open" value="ChangePath" />
                <p>
                    <textarea id="video-names" name="video-names" hidden></textarea>
                <p>
                    <label for=""><strong>Output Path</strong> </label>
                    <input type="text" name="output-path" id="output-path" value="/mnt/" >
                    <input type="button" id="open-out" name="open-out" value="ChangePath">
                <p>
                    <label for=""><strong>Bitrate(Mb/s)</strong></label>
                    <input type="text" name="bitrate" id="bitrate" value="">
                <p>
                    <label> <strong>XML File</strong></label>
                    <input type="file" name="preset" class="" id="file" accept="text/xml">
                <p>
                    <!-- <input type="button" id="videos" name="videos" value="Select Videos" /> -->
                    <input type="submit" class="button" name="jobs" id="jobs" value="Create Jobs">
                    <!-- <input type="button" name="jobs" id="jobs" value="Create Jobs"> -->
            </div>
        </div>
    </form>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        var originInputPath = $("#input").val();
        $(document).on('dblclick', '#parent-list li', function() {
            $('#input').val(originInputPath + $(this).text() + "/");
            originInputPath = $('#input').val();
            var data = {
                data: {
                    path: originInputPath,
                    dlg_flag: out_dlg_flag
                }
            }
            createUL(data);
        });

        $(document).on('click', '#parent-list li', function() {
            // $('#input').val(originInputPath + $(this).text() + "/");
        });

        $("#previous").click(function() {
            var data = $('#input').val();
            var array = data.split('/');
            if (array.length > 3) {
                var newPath = "";
                for (var index = 0; index < array.length - 2; index++) {
                    newPath += array[index] + "/";
                }
                $('#input').val(newPath);
                originInputPath = newPath;
                // console.log(data, array, newPath);
                var data = {
                    data: {
                        path: newPath,
                        dlg_flag: out_dlg_flag
                    }
                }
                createUL(data);
            }

        });

        $("#videos").bind('click', {
            path: $("#input-path").val()
        }, getVideos);

        // $("#jobs").bind('click', {
        //     in_path: $("#input-path").val(),
        //     out_path: $("#output-path").val()
        // }, createJobs);
        
        $("#open").bind('click', {
            dlg_flag: false
        }, createUL);
        $("#open-out").bind('click', {
            dlg_flag: true
        }, createUL);

        // $("#select").bind('click', {
        // }, selectVideo);

        // function selectVideo() {
            
        // }

        var out_dlg_flag = false;
        function createUL(param) {
            out_dlg_flag = param.data.dlg_flag;
            
            $.ajax({
                url: '/functions.php',
                type: 'post',
                data: {
                    action: 'getPath',
                    path: $("#input").val()
                },
                success: function(output) {
                    var res = $.parseJSON(output);
                    var cList = $('#parent-list');
                    cList.empty();
                    $.each(res.result_d, function(key, value) {
                        var li = $('<li/>')
                            .addClass('ui-menu-item')
                            .attr('style', 'line-break: anywhere;')
                            .attr('role', 'menuitem')
                            .appendTo(cList);
                        var aaa = $('<a/>')
                            .addClass('ui-all')
                            .text(value)
                            .appendTo(li);
                    });
                    if(!out_dlg_flag) {
                        $.each(res.result_f, function(key, value) {
                            var li = $('<li/>')
                                .addClass('ui-menu-item')
                                .attr('style', 'line-break: anywhere;')
                                .attr('role', 'menuitem')
                                .appendTo(cList);
                            var checkbox = $('<input/>')
                                .attr('type', 'checkbox')
                                .prop('checked', true)
                                .text(value)
                                .appendTo(li);
                            var aaa = $('<a/>')
                                .addClass('ui-all')
                                .text(value)
                                .appendTo(li);
                        });
                    }
                    
                }
            });

        }

        function getVideos(param) {
            // console.log($("#input").val());
            $.ajax({
                url: '/functions.php',
                type: 'post',
                data: {
                    action: 'getVideos',
                    path: $("#input").val()
                },
                success: function(output) {
                    var res = $.parseJSON(output);
                    console.log(res);
                }
            });

        }

        var videos = [];
        function createJobs(param) {
            // console.log($("#output-path").val());
            console.log(videos);
            $.ajax({
                url: '/index.php',
                type: 'post',
                data: {
                    action: 'createJobs',
                    path: $("#input-path").val(),
                    out_path: $("#output-path").val(),
                    video_files: videos
                },
                success: function(output) {
                    // var res = $.parseJSON(output);
                    console.log(output);
                }
            });

        }

        var openButton_in = document.getElementById('open');
        var openButton_out = document.getElementById('open-out');
        var favDialog = document.getElementById('favDialog');
        openButton_in.addEventListener('click', function onOpen() {
            
            if (typeof favDialog.showModal === "function") {
                favDialog.showModal();

            } else {
                alert("The <dialog> API is not supported by this browser");
            }
        });
        openButton_out.addEventListener('click', function onOpen() {

            if (typeof favDialog.showModal === "function") {
                favDialog.showModal();

            } else {
                alert("The <dialog> API is not supported by this browser");
            }
        });

        favDialog.addEventListener('close', function onClose() {
            // console.log(out_dlg_flag);
            if(out_dlg_flag) {
                $('#output-path').val($('#input').val());
            } else {
                $('#input-path').val($('#input').val());
                videos = [];
                $(".ui-menu-item input:checked").each(function(){
                    videos.push($(this).text());
                });
                $('#video-names').val(videos);
            }
            
            // console.log(videos);
        });
    </script>
  </body>
</html>


