
function cound_job(argument){
    var table = document.getElementById('seq_table');
    var selectedRow = table.querySelector('.selected');
    //seq_id = selectedRow ? selectedRow.cells[0].innerText : null;
    //seq_name = selectedRow ? selectedRow.cells[1].innerText : null;
  
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