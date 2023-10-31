<!DOCTYPE html>
<html>

<head>
    <title>Exception Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #333;
        }

        p {
            color: #666;
        }

        pre {
            font-family: Consolas, monospace;
            background-color: #f5f5f5;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Exception Message</h1>
        <p><?php echo $e->getMessage(); ?></p>

        <h1>File</h1>
        <p><?php echo $e->getFile(); ?>(:<?= $e->getLine() ?>)</p>

        <h1>Stack Trace</h1>
        <pre><?php echo $e->getTraceAsString(); ?></pre>
    </div>
</body>

</html>