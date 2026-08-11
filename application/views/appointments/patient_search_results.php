<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Search Results</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="container mt-4">
        <h2 class="text-success">Search Results</h2>

        <?php if (!empty($patients)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Birthday</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients as $patient): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($patient['name']); ?></td>
                                <td><?php echo htmlspecialchars($patient['mname']); ?></td>
                                <td><?php echo htmlspecialchars($patient['lname']); ?></td>
                                <td><?php echo htmlspecialchars(date('F j, Y', strtotime($patient['birthday']))); ?></td>
                                <td><?php echo htmlspecialchars($patient['address']); ?></td>
                                <td>
                                    <a href="<?php echo site_url('appointments/create/' . $patient['id'] . '?name=' . urlencode($patient['name']) . '&mname=' . urlencode($patient['mname']) . '&lname=' . urlencode($patient['lname'])); ?>" class="btn btn-success">Create Appointment</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <strong>No patients found!</strong> No patients found with the name "<?php echo htmlspecialchars($search_name); ?>". Please try again.
            </div>
        <?php endif; ?>
    </main>

    <!-- Bootstrap JS and dependencies CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
