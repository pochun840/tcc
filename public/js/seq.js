var seqid = ''; 
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('#seq_table tbody tr');

    rows.forEach(row => {
        row.addEventListener('click', function() {
            const seqIdCell = this.querySelector('.seq-id');
            if (seqIdCell) {
                seqid = seqIdCell.textContent.trim(); 
                console.log('Clicked Sequence ID:', seqid);
                localStorage.setItem("seqid", seqid);
            }
        });
    });
});

function cound_job(argument){
    var table = document.getElementById('seq_table');
  
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