document.addEventListener('DOMContentLoaded', function() {
    var backButton = document.getElementById('back_btn');
    if (backButton) {
        backButton.onclick = function() {
            window.location.href = '?url=Jobs/index';
        };
    } else {
        //console.error('Element with ID "back_btn" not found.');
    }

    var table = document.getElementById('seq_table');

    if (table) {
        table.addEventListener('click', function(event) {
            var target = event.target;
            if (target.classList.contains('seq-id')) {
                seqid = target.textContent.trim();
            }
        });
    } else {
        //console.error('Element with ID "seq_table" not found.');
    }
});

var seqid = ''; 

function cound_job(argument){
    var table = document.getElementById('seq_table');
    var selectedRow = table.querySelector('.selected');
    var selectedRowData = selectedRow ? selectedRow.cells[0].innerText : null;
    var seqid = selectedRowData || null;
    
    var seqid = readFromLocalStorage('seqid');

    if(argument == 'del' && seqid != null){
        delete_seqid(seqid);
    }

    if(argument =="edit" && seqid != null){
        
        edit_seq(seqid);
    }

    if(argument =="new"){
        create_seq();
    }

    if(argument =="copy" && seqid != null){
        copy_seq(seqid);
    }

}