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

        button {
            padding: 8px 16px;
            font-size: 16px;
            cursor: pointer;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f8f9fa;
            color: #333;
            transition: background-color 0.3s, color 0.3s;
        }

        button:hover {
            background-color: #007bff;
            color: white;
        }

        button:active {
            background-color: #0056b3;
            color: white;
        }

        #editor {
            width: 900px;
            min-height: 300px;
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

        table {
            border-collapse: collapse;
            min-width: 200px;
            width: auto;
            max-width: 100%;
            table-layout: auto;
        }

        table, th, td {
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

    <div id="toolbar">
        <button onclick="formatText('bold')"><i class="fas fa-bold"></i></button>
        <button onclick="formatText('italic')"><i class="fas fa-italic"></i></button>
        <button onclick="formatText('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>
        <button onclick="formatText('insertOrderedList')"><i class="fas fa-list-ol"></i></button>
        <button onclick="showTablePopup()"><i class="fas fa-table"></i></button>
        <button onclick="setHeading(1)">H1</button>
        <button onclick="setHeading(2)">H2</button>
        <button onclick="setHeading(3)">H3</button>
        <button onclick="getEditorHTML()"><i class="fas fa-code"></i></button>
    </div>
    
    <div id="editor" contenteditable="true"></div>


    <!-- Popup สำหรับกำหนดแถวและคอลัมน์ -->
    <div id="tablePopup" class="popup">
        <label>Rows: <input type="number" id="rows" value="2" min="1"></label>
        <label>Cols: <input type="number" id="cols" value="2" min="1"></label>
        <button onclick="insertTable()">Insert</button>
        <button onclick="closeTablePopup()">Cancel</button>
    </div>

    <div id="contextMenu"></div>

    <script>
        document.getElementById("editor").addEventListener("paste", function (event) {
            event.preventDefault(); 
            let text = (event.clipboardData || window.clipboardData).getData("text"); 
            document.execCommand("insertText", false, text); 
        });

        function formatText(command) {
            document.execCommand(command, false, null);
        }

        function showTablePopup() {
            document.getElementById("tablePopup").style.display = "block";
        }

        function closeTablePopup() {
            document.getElementById("tablePopup").style.display = "none";
        }

        function insertTable() {
            let rows = document.getElementById("rows").value;
            let cols = document.getElementById("cols").value;
            if (rows < 1 || cols < 1) {
                alert("Rows and Columns must be at least 1.");
                return;
            }

            let tableHTML = '<table>';
            for (let i = 0; i < rows; i++) {
                tableHTML += '<tr>';
                for (let j = 0; j < cols; j++) {
                    tableHTML += `<td contenteditable="true" style="min-width: 50px; text-align: left;">Cell <div class="resizer"></div></td>`;
                }
                tableHTML += '</tr>';
            }
            tableHTML += '</table><br>';

            document.execCommand("insertHTML", false, tableHTML);
            closeTablePopup();
            makeTableResizable();
        }


        function setHeading(level) {
            document.execCommand("formatBlock", false, "H" + level);
        }

        function makeTableResizable() {
            document.querySelectorAll("table").forEach(table => {
                table.querySelectorAll("td").forEach(td => {
                    // ตรวจสอบว่ามี .resizer หรือยัง ถ้ายังไม่มีให้เพิ่มเข้าไป
                    if (!td.querySelector(".resizer")) {
                        let resizer = document.createElement("div");
                        resizer.className = "resizer";
                        td.appendChild(resizer);
                    }

                    let resizer = td.querySelector(".resizer");
                    if (resizer) {
                        resizer.addEventListener("mousedown", initResize);
                    }
                });
            });
        }

        let startX, startWidth, resizingCol;

        function initResize(event) {
            startX = event.clientX;
            resizingCol = event.target.parentElement;
            startWidth = resizingCol.offsetWidth;
            document.addEventListener("mousemove", resizeColumn);
            document.addEventListener("mouseup", stopResize);
        }


        function resizeColumn(event) {
            let newWidth = startWidth + (event.clientX - startX);
            if (newWidth > 30) {
                resizingCol.style.width = newWidth + "px";
            }
        }

        function stopResize() {
            document.removeEventListener("mousemove", resizeColumn);
            document.removeEventListener("mouseup", stopResize);
        }

        document.addEventListener("DOMContentLoaded", () => {
            makeTableResizable();
        });

        document.addEventListener("contextmenu", function (event) {
            let cell = event.target.closest("td");
            if (cell) {
                event.preventDefault();
                selectedCell = cell;
                showContextMenu(event.pageX, event.pageY);
            }
        });

        function showContextMenu(x, y) {
            let contextMenu = document.getElementById("contextMenu");
            contextMenu.innerHTML = `
                <button onclick="insertRow()">Insert Row</button>
                <button onclick="insertColumn()">Insert Column</button>
                <button onclick="deleteRow()">Delete Row</button>
                <button onclick="deleteColumn()">Delete Column</button>
            `;
            contextMenu.style.left = `${x}px`;
            contextMenu.style.top = `${y}px`;
            contextMenu.style.display = "block";
        }

        function insertRow() {
            let row = selectedCell.parentElement;
            let newRow = row.cloneNode(true);
            row.parentElement.insertBefore(newRow, row.nextSibling);
        }

        function insertColumn() {
            let table = selectedCell.closest("table");
            let colIndex = selectedCell.cellIndex;
            for (let row of table.rows) {
                let newCell = row.insertCell(colIndex + 1);
                newCell.contentEditable = "true";
                newCell.innerText = "Cell";
                newCell.style.minWidth = "50px";
                newCell.style.textAlign = "left"; 
            }
            makeTableResizable();
        }

        function getSelectedCell() {
            let selection = window.getSelection();
            if (!selection.rangeCount) return null;

            let range = selection.getRangeAt(0);
            let cell = range.commonAncestorContainer;

            while (cell && cell.nodeName !== "TD") {
                cell = cell.parentElement;
            }
            return cell;
        }

        function deleteRow() {
            let selectedCell = getSelectedCell();
            if (!selectedCell) return;

            let row = selectedCell.parentElement;
            let table = row.parentElement;

            if (table.rows.length > 1) {
                row.remove();
            }
        }

        function deleteColumn() {
            let selectedCell = getSelectedCell();
            if (!selectedCell) return;

            let table = selectedCell.closest("table");
            let colIndex = selectedCell.cellIndex;

            for (let row of table.rows) {
                if (row.cells.length > 1) {
                    row.deleteCell(colIndex);
                }
            }
        }

        document.addEventListener("click", function () {
            document.getElementById("contextMenu").style.display = "none";
        });

        function getEditorHTML() {
            let editorContent = document.getElementById("editor").innerHTML;
            alert(editorContent);
            console.log(editorContent); 
            navigator.clipboard.writeText(editorContent).then(() => {
                alert("HTML copied to clipboard!");
            }).catch(err => {
                console.error("Failed to copy:", err);
            });
        }
    </script>

</body>
</html>
