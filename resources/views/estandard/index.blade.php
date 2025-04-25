<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Data</title>
    <!-- Add any CSS or JS libraries if needed -->
</head>
<body>
    <h1>Upload Data</h1>

    <!-- Display errors if any -->
    @if ($errors->any())
        <div>
            <strong>Whoops! There were some problems with your input:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Upload Form -->
    <form action="{{ route('isbn.upload-data') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="tistype">Tistype:</label>
            <input type="text" id="tistype" name="tistype" value="02" required>
        </div>

        <div>
            <label for="tisno">Tisno:</label>
            <input type="text" id="tisno" name="tisno" value="xxx-xxxx" required>
        </div>

        <div>
            <label for="tisname">Tisname:</label>
            <input type="text" id="tisname" name="tisname" value="xxxxxxxx" required>
        </div>

        <div>
            <label for="page">Page:</label>
            <input type="number" id="page" name="page" value="5" required>
        </div>

        <div>
            <label for="cover_file">Cover File:</label>
            <input type="file" id="cover_file" name="cover_file" required>
        </div>

        <button type="submit">Upload</button>
    </form>

    <!-- Display success message if any -->
    @if (session('success'))
        <div>
            <strong>{{ session('success') }}</strong>
        </div>
    @endif
</body>
</html>
