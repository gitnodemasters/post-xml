<div class="">
    <form method="post" enctype='multipart/form-data'>
        <p>
            <label for="">Input Path : </label>
            <input type="text" name="input-path" id="input-path" value="/mnt/">
            <input type="button" id="open" name="open" value="Change Path" />
        <p>
            <input type="button" id="videos" name="videos" value="Select Videos" />
        <p>
            <label for="">Output Path: </label>
            <input type="text" name="output-path" id="output-path" value="/mnt/ElementalTesting/Output/">
            <!-- <input type="button" name="chan    ge" class="button" value="ChangePath"> -->
        <p>
            <label> XML File : </label>
            <input type="file" name="preset" class="" id="file" accept="text/xml">
        <p>
            <input type="button" name="jobs" id="jobs" value="Create Jobs">

    </form>
</div>

<!-- Simple pop-up dialog box containing a form -->
<style>
    ul {
        height: 100px;
        width: 200px;
    }

    ul {
        overflow: hidden;
        overflow-y: scroll;
    }
</style>
<dialog id="favDialog">
    <form method="dialog">
        <p><label>Path:
                <input type="text" id="input" name="" value="/mnt/" disabled>
            </label><input type="button" id="previous" value="<"></p>
        <ul id="parent-list">
            <li id="a">Item A</li>
            <li id="b">Item B</li>
            <li id="c">Item C</li>
            <li id="d">Item D</li>
            <li id="e">Item E</li>
            <li id="f">Item F</li>
            <li id="g">Item A</li>
            <li id="h">Item B</li>
            <li id="i">Item C</li>
            <li id="j">Item D</li>
            <li id="k">Item E</li>
            <li id="l">Item F</li>
        </ul><br>
        <menu>
            <button id="confirmBtn" value="default">Select</button>
        </menu>
    </form>
</dialog>
<!-- <menu>
	<button id="open">Open</button>
</menu> -->

<!-- <input type="text" id="output" name="output" disabled> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script type="text/javascript">
    var originInputPath = $("#input").val();
    $(document).on('dblclick', '#parent-list li', function() {
        originInputPath = $('#input').val();
        var data = {
            data: {
                path: originInputPath
            }
        }
        createUL(data);
    });

    $(document).on('click', '#parent-list li', function() {
        $('#input').val(originInputPath + $(this).text() + "/");
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
                    path: newPath
                }
            }
            createUL(data);
        }

    });

    $("#videos").bind('click', {
        path: $("#input-path").val()
    }, getVideos);
    $("#jobs").bind('click', {
        in_path: $("#input-path").val(),
        out_path: $("#output-path").val()
    }, createJobs);
    $("#open").bind('click', {
        path: $("#input-path").val()
    }, createUL);

    function createUL(param) {
        console.log($("#input").val())
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
                $.each(res.result, function(key, value) {
                    var li = $('<li/>')
                        .addClass('ui-menu-item')
                        .attr('role', 'menuitem')
                        .appendTo(cList);
                    var aaa = $('<a/>')
                        .addClass('ui-all')
                        .text(value)
                        .appendTo(li);
                });
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

    function createJobs(param) {
        // console.log($("#output-path").val());
        $.ajax({
            url: '/functions.php',
            type: 'post',
            data: {
                action: 'createJobs',
                path: $("#input").val(),
                out_path: $("#output-path").val()
            },
            success: function(output) {
                var res = $.parseJSON(output);
                console.log(res);
            }
        });

    }

    var openButton = document.getElementById('open');
    var favDialog = document.getElementById('favDialog');
    openButton.addEventListener('click', function onOpen() {
        // console.log("tttt");

        if (typeof favDialog.showModal === "function") {
            favDialog.showModal();

        } else {
            alert("The <dialog> API is not supported by this browser");
        }
    });

    favDialog.addEventListener('close', function onClose() {
        // $('#output').val($('#input').val());
        $('#input-path').val($('#input').val());
        // $('#input-path').attr( 'value', $('#input-path').val() );
        // $('#input-path').attr( 'value', $('#input-path').val() );
    });
</script>