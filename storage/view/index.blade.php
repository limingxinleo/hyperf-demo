<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hyperf</title>
</head>
<body>
Hello, {{$name}}. You are using blade template now.
</body>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
<script src="/debug.js"></script>
<script>
    $.post('/index.json', function (res) {
        console.log(res);
    });
</script>
</html>