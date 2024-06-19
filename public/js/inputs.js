function job_confirm(){
    var jobid = document.getElementById("JobNameSelect").value;
    localStorage.setItem("jobid", jobid);
    job_id = jobid;
    all_job = jobid;

    if(jobid){
        $.ajax({
            url: "?url=Inputs/get_input_by_job_id",
            method: "POST",
            data:{ 
                jobid: jobid,
            },
            success: function(response) {
                var data = JSON.parse(response);
                var job_inputlist = data.job_inputlist;
                temp = data.temp;
                tempA = data.tempA;

                document.getElementById("input_jobid_select").innerHTML = job_inputlist;
                document.getElementById("JobSelect").style.display = 'none';
                document.getElementById("job_id").value = jobid;
            
                var rows = document.querySelectorAll('#input_jobid_select tr');
                rows.forEach(function(row) {
                    row.addEventListener('click', function() { 
                        input_event = this.className; 
                        old_input_event = this.className;
                    
                    });
                });

            },
            error: function(xhr, status, error) {
            
            }
        }); 
    }
}
