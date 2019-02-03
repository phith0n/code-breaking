<?php
if(isset($_GET['read-source'])) {
    exit(show_source(__FILE__));
}

define('DATA_DIR', dirname(__FILE__) . '/data/' . md5($_SERVER['REMOTE_ADDR']));

if(!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}
chdir(DATA_DIR);

$domain = isset($_POST['domain']) ? $_POST['domain'] : '';
$log_name = isset($_POST['log']) ? $_POST['log'] : date('-Y-m-d');
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha256-eSi1q2PG6J7g7ib17yAaWMcrr5GrtohYChqibrV7PBE=" crossorigin="anonymous">

    <title>Domain Detail</title>
    <style>
    pre {
        width: 100%;
        background-color: #f6f8fa;
        border-radius: 3px;
        font-size: 85%;
        line-height: 1.45;
        overflow: auto;
        padding: 16px;
        border: 1px solid #ced4da;
    }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col">
            <form method="post">
                <div class="input-group mt-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">dig -t A -q</span>
                    </div>
                    <input type="text" name="domain" class="form-control" placeholder="Your domain">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">执行</button>
                    </div>
                </div>
            </form>
        </div>
    
    </div>

    <div class="row">
        <div class="col">
            <pre class="mt-3"><?php if(!empty($_POST) && $domain):
                $command = sprintf("dig -t A -q %s", escapeshellarg($domain));
                $output = shell_exec($command);

                $output = htmlspecialchars($output, ENT_HTML401 | ENT_QUOTES);

                $log_name = $_SERVER['SERVER_NAME'] . $log_name;
                if(!in_array(pathinfo($log_name, PATHINFO_EXTENSION), ['php', 'php3', 'php4', 'php5', 'phtml', 'pht'], true)) {
                    file_put_contents($log_name, $output);
                }

                echo $output;
            endif; ?></pre>
        </div>
    </div>

</div>

</body>
</html>