<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha256-eSi1q2PG6J7g7ib17yAaWMcrr5GrtohYChqibrV7PBE=" crossorigin="anonymous">

    <title>Hello, world!</title>
    <style>
        #editor {
            height: 500px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <script id="editor" type="text/plain"></script>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="/static/ueditor/ueditor.config.js" type="text/javascript"></script>
<script src="/static/ueditor/ueditor.all.min.js" type="text/javascript"></script>
<script type="text/javascript">

    (function () {
        var ue = UE.getEditor('editor');
    })()

</script>

</body>
</html>