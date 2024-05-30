<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Move Table Rows without Changing Seq ID</title>
</head>
<body>

<table id="myTable" border="1">
  <thead>
    <tr>
      <th>Header</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="seq-id" data-sequence-id="1">Row 1</td>
      <td><button onclick="MoveUp(this)">Up</button><button onclick="MoveDown(this)">Down</button></td>
    </tr>
    <tr>
      <td class="seq-id" data-sequence-id="2">Row 2</td>
      <td><button onclick="MoveUp(this)">Up</button><button onclick="MoveDown(this)">Down</button></td>
    </tr>
    <tr>
      <td class="seq-id" data-sequence-id="3">Row 3</td>
      <td><button onclick="MoveUp(this)">Up</button><button onclick="MoveDown(this)">Down</button></td>
    </tr>
  </tbody>
</table>

<script>
function MoveUp(button) {
    var row = button.parentNode.parentNode;
    var index = row.rowIndex;

    if (index > 1) {
        swap_row(row, 'up');
        var prevRow = row.previousElementSibling;
        if (prevRow && !prevRow.classList.contains('seq-id-row')) {
            row.parentNode.insertBefore(row, prevRow);
        } else {
            alert("已經到達頂部！");
        }
    } else {
        alert('已經到達頂部！');
    }
}

function MoveDown(button) {
    var row = button.parentNode.parentNode;
    var index = row.rowIndex;
    var table = row.parentNode;

    if (index < table.rows.length) {
        swap_row(row, 'down');
        var nextRow = row.nextElementSibling;
        if (nextRow && !row.classList.contains('seq-id-row')) {
            row.parentNode.insertBefore(nextRow, row);
        } else {
            alert("已經到達底部！");
        }
    } else {
        alert('已經到達底部！');
    }
}

function swap_row(row, direction) {
    var table = row.parentNode;
    var currentIndex = row.rowIndex;

    if (direction === 'up') {
        var targetIndex = currentIndex - 1;
        if (targetIndex > 0 && !table.rows[targetIndex].classList.contains('seq-id')) {
            table.insertBefore(row, table.rows[targetIndex]);
        } else {
            alert("已經到達頂部！");
        }
    } else if (direction === 'down') {
        var targetIndex = currentIndex + 1;
        if (targetIndex < table.rows.length) {
            table.insertBefore(table.rows[targetIndex], row);
        } else {
            alert("已經到達底部！");
        }
    }
}
</script>

</body>
</html>
