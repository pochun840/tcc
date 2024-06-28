var old_output_event; 
function job_confirm(){
    var jobid = document.getElementById("JobNameSelect").value;
    localStorage.setItem("jobid", jobid);
    job_id = jobid;
    all_job = jobid;

    if(jobid){
        $.ajax({
            url: "?url=Outputs/get_output_by_job_id",
            method: "POST",
            data:{ 
                job_id: job_id,
            },
            success: function(response) {
                var data = JSON.parse(response);
                var job_outputlist = data.job_outputlist;
                temp = data.temp;
                tempA = data.tempA;


                document.getElementById("output_jobid_select").innerHTML = job_outputlist;
                document.getElementById("JobSelect").style.display = 'none';
                document.getElementById("job_id").value = job_id;
            
                var rows = document.querySelectorAll('#output_jobid_select tr');
                rows.forEach(function(row) {
                    row.addEventListener('click', function() { 
                        output_event = this.className; 
                        old_output_event = this.className;
                    
                    });
                });
               

            },
            error: function(xhr, status, error) {
            
            }
        });
    }
}

//delete
function delete_output_id(job_id,output_event){
    if(job_id){
        $.ajax({
            url: "?url=Outputs/delete_output",
            method: "POST",
            data: { 
                job_id: job_id,
                output_event: output_event,
             
            },
            success: function(response) {
                console.log(response);
                alert(response);
                get_output_by_job_id(job_id);
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });     
    }   
}


function get_output_by_job_id(job_id){
    $.ajax({
        url: "?url=Outputs/get_output_by_job_id",
        method: "POST",
        data: { 
            job_id: job_id,
        },
        success: function(response) {
            var data = JSON.parse(response);
            var job_outputlist = data.job_outputlist;
            temp = data.temp;
            tempA = data.tempA;

            document.getElementById("output_jobid_select").innerHTML = job_outputlist;
            document.getElementById("JobSelect").style.display = 'none';
            document.getElementById("job_id").value = job_id;
        
            var rows = document.querySelectorAll('#output_jobid_select tr');
            rows.forEach(function(row) {
                row.addEventListener('click', function() { 
                    output_event = this.className; 
                });
            });
        },
        error: function(xhr, status, error) {
            console.error("AJAX request failed:", status, error);
        }
    }); 

}


function collectPinValues(selector) {
    var pinOptions = document.querySelectorAll(selector);
    var selectedValues = [];

    pinOptions.forEach(function(option) {
        if (option.checked){ 
            var radioInfo = {
                id: option.id,
                value: option.value
            };
            selectedValues.push(radioInfo);
        }
    });

    return selectedValues;
}
function create_output_id() {
    var output_event = document.getElementById("Event_Option").value;
    var pinval = collectPinValues('input[name="pin_option"]');

    if (pinval.length > 0) {
        var pin_old = pinval[0]['id']; 
        var wave = pinval[0]['value'];
        
        var match = pin_old.match(/\d+/); 
        var output_pin = match ? parseInt(match[0]) : null;
        
        var time_ms = 'time'+ output_pin;
        var wave_on =  document.getElementById(time_ms).value;

        if (job_id) {
            $.ajax({
                url: "?url=Outputs/create_output_event",
                method: "POST",
                data: { 
                    job_id: job_id,
                    output_pin: output_pin,
                    output_event: output_event,
                    wave: wave,
                    wave_on: wave_on
                },
                success: function(response) {
                    console.log(response);
                    alert(response);
                    get_output_by_job_id(job_id);
                    document.getElementById('new_output').style.display = 'none';
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed:", status, error);
                }
            });
        }
    } else {
        console.error("No pinval found or pinval[0] is undefined.");
    }
}

function edit_output_id(){
    var output_event = document.getElementById("edit_event_option").value;
    var pinval       = collectPinValues('input[name="edit_pin_option"]');
    var pin_old      = pinval[0]['id'];
    var wave         = pinval[0]['value'];
    var match        = pin_old.match(/\d+/); 
    var output_pin   = match ? parseInt(match[0]) : null;

    var time_ms = 'edit_time'+ output_pin;
    var wave_on =  document.getElementById(time_ms).value;
    if(job_id){
        $.ajax({
            url: "?url=Outputs/edit_output_event",
            method: "POST",
            data: { 
                job_id: job_id,
                output_pin: output_pin,
                output_event: output_event,
                wave: wave,
                wave_on: wave_on,
                old_output_event: old_output_event
            },
            success: function(response) {
                console.log(response);
                alert(response);
                get_output_by_job_id(job_id);
                document.getElementById('edit_output').style.display='none';
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });         
    }

}

function resetalignsubmit(job_id) {

    var job_id_new = 0;
    if(job_id_new == 0){
        $.ajax({
            url: "?url=Outputs/output_alljob",
            method: "POST",
            data: {
                job_id_new: job_id_new
            },
            success: function (response) {
                console.log(response);
                get_output_by_job_id(job_id);
            },
            error: function (xhr, status, error) {

            }
        });

    }

}
function alignsubmit(job_id) {
    if (job_id) {
        $.ajax({
            url: "?url=Outputs/output_alljob",
            method: "POST",
            data: {
                job_id: job_id
            },
            success: function (response) {
                console.log(response);
                get_output_by_job_id(job_id);
            
                buttonDisabled = !buttonDisabled;
                document.getElementById('Button_Select').disabled = buttonDisabled;
     
                backgroundColorYellow = !backgroundColorYellow;
                if (backgroundColorYellow) {
                    document.getElementById('job_id').style.backgroundColor = 'yellow';
                } else {
                    document.getElementById('job_id').style.backgroundColor = '';
                }
            },
            error: function (xhr, status, error) {

            }
        });
    }
}



//copy
function copy_output_id(){
    var to_job_id = document.getElementById("JobSelect1").value;
    if(to_job_id){
        $.ajax({
            url: "?url=Outputs/copy_output",
            method: "POST",
            data: { 
                from_job_id: job_id,
                to_job_id: to_job_id
            },
            success: function(response) {

                document.getElementById('copy_output').style.display='none';
                console.log(response);
                get_output_by_job_id(job_id);
            },
            error: function(xhr, status, error) {
                
            }
        });

    }    
}

function get_output_info(job_id,output_event){

    if(job_id){
     $.ajax({
             url: "?url=Outputs/check_job_event",
             method: "POST",
             data: { 
                 job_id: job_id,
                 output_event: output_event
             },
             success: function(response) {
              
                 var responseJSON = JSON.stringify(response);
                 var cleanString = responseJSON.replace(/Array|\\n/g, '');
                 var cleanString = cleanString.substring(2, cleanString.length - 2);
                 var [, job_id] = cleanString.match(/\[output_job_id]\s*=>\s*([^ ]+)/) || [, null];
                 var [, output_event] = cleanString.match(/\[output_event]\s*=>\s*([^ ]+)/) || [, null];
                 var [, output_pin] = cleanString.match(/\[output_pin]\s*=>\s*([^ ]+)/) || [, null];
                 var [, wave] = cleanString.match(/\[wave]\s*=>\s*([^ ]+)/) || [, null];
                 var [, wave_on] = cleanString.match(/\[wave_on]\s*=>\s*([^ ]+)/) || [, null];
 
                 var edit_output_pin = "edit_pin" + output_pin + "_"+ wave;
                 var radioButton = document.getElementById(edit_output_pin);
                 radioButton.removeAttribute('disabled');
 
                 var time_ms = 'edit_time'+ output_pin;
 
                 document.getElementById(time_ms).value = wave_on;
 
                 old_output_even = output_event;
 
                 if(radioButton){
                     radioButton.checked = true;
                 }
                 
                 document.querySelector("select[name='edit_event_option']").value = output_event;
 
                 document.getElementById("edit_event_option").onchange = function() {
                     var selectedValue = this.value; 
                 };
             },
             error: function(xhr, status, error) {
                 console.error("AJAX request failed:", status, error);
             }
     });      
    }
 
 
 }


 //update
 //var old_output_event = document.getElementById("some_element_id").value;






