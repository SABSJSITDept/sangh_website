<!DOCTYPE html>
<html>
<head>
    <title>Send Notification</title>
</head>
<body>
    <h2>Send Notification to All Users</h2>
    <form method="POST" action="/send-notification" enctype="multipart/form-data"> <!-- ✅ multipart -->
        @csrf
        <input type="text" name="title" placeholder="Enter Title" required><br><br>
        <textarea name="body" placeholder="Enter Message" required></textarea><br><br>
        <input type="file" name="image"><br><br> <!-- ✅ Image Upload -->
        <button type="submit">Send</button>
    </form>
</body>
</html>
