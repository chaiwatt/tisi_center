<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WYSIWYG Editor</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@100;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        #toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        button.toolbar {
            padding: 8px 16px;
            font-size: 12px;
            cursor: pointer;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f8f9fa;
            color: #333;
            transition: background-color 0.3s, color 0.3s;
        }

        button.toolbar:hover {
            background-color: #007bff;
            color: white;
        }

        button.toolbar:active {
            background-color: #0056b3;
            color: white;
        }


        .editor {
            width: 900px;
            min-height: 200px;
            max-height: 600px;
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
            font-family: 'Sarabun', sans-serif; /* ใช้ฟอนต์ TH Sarabun */
            font-size: 16px;
            padding: 10px;
            border: 1px solid #ccc;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .editor-table {
            border-collapse: collapse;
            min-width: 200px;
            width: auto;
            max-width: 100%;
            table-layout: auto;
        }

        .editor-table, .editor-table th, .editor-table td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            position: relative;
        }


        .resizer {
            position: absolute;
            right: 0;
            top: 0;
            width: 5px;
            height: 100%;
            cursor: col-resize;
            background: transparent;
        }

        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
        }

        .popup input {
            width: 50px;
            margin-right: 10px;
        }

        .popup button {
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .popup button:hover {
            background-color: #0056b3;
        }

        #contextMenu {
            position: absolute;
            display: none;
            background: white;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 5px rgba(0,0,0,0.2);
            padding: 5px;
            z-index: 1000;
        }

        #contextMenu button {
            display: block;
            width: 100%;
            background: none;
            border: none;
            padding: 5px 10px;
            text-align: left;
            cursor: pointer;
            font-size: 14px;
        }

        #contextMenu button:hover {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

    <script>
           let startX, startWidth, resizingCol;
           let editorId;
           let currentEditorId = '';
    </script>
    <p>หัวข้อที่1</p>
    @include('demo_html_pdf_editor.editor', ['id' => '1'])
    <p>หัวข้อที่2</p>
    @include('demo_html_pdf_editor.editor', ['id' => '2'])
    <p>หัวข้อที่3</p>
    @include('demo_html_pdf_editor.editor', ['id' => '3'])
    <p>หัวข้อที่4</p>
    @include('demo_html_pdf_editor.editor', ['id' => '4'])

</body>
</html>
