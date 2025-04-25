<div id="toolbar">
    <button class="toolbar" type="button" onclick="formatText('bold')"><i class="fas fa-bold"></i></button>
    <button class="toolbar" type="button" onclick="formatText('italic')"><i class="fas fa-italic"></i></button>
    {{-- <button class="toolbar" type="button" onclick="formatText('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>
    <button class="toolbar" type="button" onclick="formatText('insertOrderedList')"><i class="fas fa-list-ol"></i></button> --}}
    <button class="toolbar" type="button" onclick="showTablePopup('editor-{{$id}}')"><i class="fas fa-table"></i></button>
    {{-- <button class="toolbar" type="button" onclick="setHeading(1)">H1</button>
    <button class="toolbar" type="button" onclick="setHeading(2)">H2</button>
    <button class="toolbar" type="button" onclick="setHeading(3)">H3</button> 
    <button class="toolbar" type="button" onclick="getEditorHTML('{{$id}}')"><i class="fas fa-code"></i></button> --}}
</div>


<div id="editor-{{$id}}" class="editor" contenteditable="true"></div>

<!-- Popup สำหรับกำหนดแถวและคอลัมน์ -->
<div id="tablePopup" class="popup">
    <label>จำนวนแถว: <input type="text" id="rows" value="2" min="1"></label>
    <label>จำนวนคอลัมน์: <input type="text" id="cols" value="2" min="1"></label>
    <button type="button" onclick="insertTable()">แทรก</button>
    <button type="button" onclick="closeTablePopup()">ยกเลิก</button>
</div>

<div id="contextMenu"></div>

<script>
    editorId = '{{ $id }}';
    currentEditorId = '';
    document.getElementById("editor-" + editorId).addEventListener("paste", function (event) {
            event.preventDefault(); 
            
            // ดึงข้อมูลจาก clipboard
            let text = event.clipboardData.getData("text/plain");

            // ลบแท็ก <br> และแท็ก HTML อื่นๆ (หรือแท็กที่ไม่ต้องการ)
            text = text
                .replace(/\n+/g, ' ') // แทนที่ newline ด้วยช่องว่าง
                .replace(/<br\s*\/?>/gi, ' ') // ลบ <br> หรือแทนที่ด้วยช่องว่าง
                .replace(/\s{2,}/g, ' ') // ลบช่องว่างเกิน 1 ตัว
                .trim(); // ตัดช่องว่างที่ส่วนหัวและท้าย

            // แทรกข้อความที่ได้ (ไม่รวมสไตล์หรือแท็ก HTML)
            document.execCommand("insertText", false, text); 
        });


    function formatText(command) {
        document.execCommand(command, false, null);
    }

    function showTablePopup(editorId) {
        currentEditorId = editorId; // เก็บ editorId ที่เลือก
        document.getElementById("tablePopup").style.display = "block";
    }

    function closeTablePopup() {
        document.getElementById("tablePopup").style.display = "none";
    }

    // function insertTable() {
    //     let rows = document.getElementById("rows").value;
    //     let cols = document.getElementById("cols").value;
    //     if (rows < 1 || cols < 1) {
    //         alert("Rows and Columns must be at least 1.");
    //         return;
    //     }

    //     let tableHTML = '<table class="editor-table" style="border: 1px solid black;" >';
    //     for (let i = 0; i < rows; i++) {
    //         tableHTML += '<tr>';
    //         for (let j = 0; j < cols; j++) {
    //             tableHTML += `<td contenteditable="true" style="min-width: 50px; text-align: left;">Cell <div class="resizer"></div></td>`;
    //         }
    //         tableHTML += '</tr>';
    //     }
    //     tableHTML += '</table><br>';

    //     // ใช้ editorId ที่ได้รับในการแทรกตารางใน editor ที่เลือก
    //     let editor = document.getElementById(currentEditorId);
    //     editor.focus();  // ใส่ focus เพื่อให้แน่ใจว่า cursor อยู่ใน editor ที่ถูกต้อง
    //     document.execCommand("insertHTML", false, tableHTML);
    //     closeTablePopup();
    //     makeTableResizable();
    // }

    function insertTable() {
        let rows = document.getElementById("rows").value;
        let cols = document.getElementById("cols").value;
        if (rows < 1 || cols < 1) {
            alert("Rows and Columns must be at least 1.");
            return;
        }

        let tableHTML = '<table class="editor-table" style="border-collapse: collapse;">';
        for (let i = 0; i < rows; i++) {
            tableHTML += '<tr style="border: 1px solid black;">';
            for (let j = 0; j < cols; j++) {
                tableHTML += `<td contenteditable="true" style="min-width: 50px; text-align: left; border: 1px solid black;">Cell <div class="resizer"></div></td>`;
            }
            tableHTML += '</tr>';
        }
        tableHTML += '</table><br>';

        // ใช้ editorId ที่ได้รับในการแทรกตารางใน editor ที่เลือก
        let editor = document.getElementById(currentEditorId);
        editor.focus();  // ใส่ focus เพื่อให้แน่ใจว่า cursor อยู่ใน editor ที่ถูกต้อง

        // ใช้ innerHTML เพื่อแทรกตาราง
        editor.innerHTML += tableHTML; // แทรกตารางที่สร้างขึ้น

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
            <button type="button" onclick="insertRow()">แทรกแถว</button>
            <button type="button" onclick="insertColumn()">แทรกคอลัมน์</button>
            <button type="button" onclick="deleteRow()">ลบแถว</button>
            <button type="button" onclick="deleteColumn()">ลบคอลัมน์</button>
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



    // function getEditorHTML(editorId) {
    //     let editorContent = document.getElementById("editor-" + editorId).innerHTML;
    //     console.log(editorContent);

    //     // คัดลอกค่า HTML ไปที่ clipboard
    //     navigator.clipboard.writeText(editorContent).then(() => {
    //         alert("HTML copied to clipboard!");
    //     }).catch(err => {
    //         console.error("Failed to copy:", err);
    //     });
    // }

    // function getEditorHTML(editorId) {
    //     let editorElement = document.getElementById("editor-" + editorId);
    //     if (!editorElement) {
    //         console.warn("Editor not found for ID:", editorId);
    //         return "";
    //     }

    //     let editorContent = editorElement.innerHTML;
    //     console.log(editorContent);

    //     // คัดลอกค่า HTML ไปที่ clipboard
    //     navigator.clipboard.writeText(editorContent).then(() => {
    //         alert("HTML copied to clipboard!");
    //     }).catch(err => {
    //         console.error("Failed to copy:", err);
    //     });

    //     return editorContent; // ✅ Return ค่า HTML กลับไป
    // }

    
    function getEditorHTML(editorId) 
        {
            let editorElement = document.getElementById("editor-" + editorId);
            let output = document.getElementById("output");
            output.innerHTML = ""; 

            let range = document.createRange();
            let lines = [];
            let lastTop = null;

            function processNode(node) {
                if (node.nodeType === Node.TEXT_NODE) {
                    let text = node.textContent;
                    if (text === "") return;

                    let tempLine = "";
                    for (let i = 0; i < text.length; i++) {
                        range.setStart(node, i);
                        range.setEnd(node, i + 1);
                        let rects = range.getClientRects();
                        if (rects.length > 0) {
                            let top = rects[0].top;

                            if (lastTop === null || Math.abs(top - lastTop) > 5) {
                                if (tempLine !== "") {
                                    lines.push(tempLine);
                                    tempLine = "";
                                }
                            }

                            tempLine += text[i];
                            lastTop = top;
                        }
                    }
                    if (tempLine !== "") lines.push(tempLine);
                } else if (node.nodeType === Node.ELEMENT_NODE) {
                    if (node.tagName === "TABLE" || node.tagName === "UL" || node.tagName === "OL" || node.tagName === "B" ) {
                        lines.push(node.outerHTML);
                        return;
                    }
                    else if (node.tagName === "BR") {
                        lines.push("<br>");
                        return;
                    }
                    else if (node.tagName === "DIV" && node.innerHTML.trim() === "<br>") {
                        lines.push("<br>");
                        return;
                    }
                    else if (node.tagName === "DIV") {
                        let childLines = [];
                        node.childNodes.forEach(child => {
                            processNode(child);
                        });
                        lines.push("<br>"); // ขึ้นบรรทัดใหม่เมื่อจบ <div>
                        return;
                    }
                    node.childNodes.forEach(child => processNode(child));
                }
            }

            editorElement.childNodes.forEach(node => processNode(node));

            let result = lines.map((line, index) => {

                line = line.replace(/ /g, '&nbsp;');
                
                if (line.startsWith("<table") || line.startsWith("<ul") || line.startsWith("<ol") || line === "<br>") {
                    return line;
                }
                return line + "<br>";
            }).join("");

            result = result.replace(/(<br>\s*){2,}/g, "<br>");

            console.log(result);
            output.innerHTML = result;
        }


</script>
