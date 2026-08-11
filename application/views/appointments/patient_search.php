<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Patient</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-primary {
            background-color: green;
            border-color: green;
        }
        .btn-primary:hover {
            background-color: darkgreen;
            border-color: darkgreen;
        }
    </style>
</head>
<body>
    <main class="container mt-4">
        <h2>Search</h2>
        <form action="<?php echo site_url('appointments/search'); ?>" method="post">
            <div class="form-group">
                <label for="name">Email:</label>
                <input type="text" name="name" class="form-control" placeholder="Enter patient's email" required value="<?php echo isset($search_name) ? htmlspecialchars($search_name) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
