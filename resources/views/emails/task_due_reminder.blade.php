<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Due Reminder</title>
</head>
<body>
    <p>Hi {{ $task->user->name }},</p>
    <p>This is a reminder that the following task is due soon:</p>
    <p><strong>Task Title:</strong> {{ $task->title }}</p>
    <p><strong>Description:</strong> {{ $task->description }}</p>
    <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
    <p>Make sure to complete it before the due date!</p>
</body>
</html>
