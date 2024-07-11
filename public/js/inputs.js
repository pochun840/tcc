

function handleEventChange(selectedValue) {
    if(selectedValue ==109){
        document.getElementById('work_goc').style.display = 'block';
    }else{
        document.getElementById('work_goc').style.display = 'none';
    }
}

function edit_handleEventChange(selectedValue) {
    if(selectedValue ==109){
        document.getElementById('edit_work_goc').style.display = 'block';
    }else{
        document.getElementById('edit_work_goc').style.display = 'none';
    }
}


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

                var language = getCookie('language');
                if(language == "zh-cn"){

                    document.getElementById('101') && (document.getElementById('101').textContent = '禁用');
                    document.getElementById('102') && (document.getElementById('102').textContent = '启用');
                    document.getElementById('103') && (document.getElementById('103').textContent = '颗数清除');
                    document.getElementById('104') && (document.getElementById('104').textContent = '确认');
                    document.getElementById('105') && (document.getElementById('105').textContent = '启动');
                    document.getElementById('106') && (document.getElementById('106').textContent = '拆螺丝');
                    document.getElementById('107') && (document.getElementById('107').textContent = '工序清除');
                    document.getElementById('108') && (document.getElementById('108').textContent = '重启');
                    document.getElementById('109') && (document.getElementById('109').textContent = '一次感应');
                    document.getElementById('110') && (document.getElementById('110').textContent = '自定义1');
                    document.getElementById('111') && (document.getElementById('111').textContent = '自定义2');
                    document.getElementById('112') && (document.getElementById('112').textContent = '自定义3');
                    document.getElementById('113') && (document.getElementById('113').textContent = '自定义4');
                    document.getElementById('114') && (document.getElementById('114').textContent = '自定义5');
                
                }else if(language =="zh-tw"){
                    document.getElementById('101') && (document.getElementById('101').textContent = '禁用');
                    document.getElementById('102') && (document.getElementById('102').textContent = 'Enable');
                    document.getElementById('103') && (document.getElementById('103').textContent = '清除顆數');
                    document.getElementById('104') && (document.getElementById('104').textContent = '確認');
                    document.getElementById('105') && (document.getElementById('105').textContent = '啟動');
                    document.getElementById('106') && (document.getElementById('106').textContent = '拆螺絲');
                    document.getElementById('107') && (document.getElementById('107').textContent = '工序清除');
                    document.getElementById('108') && (document.getElementById('108').textContent = '重啟');
                    document.getElementById('109') && (document.getElementById('109').textContent = '一次感應');
                    document.getElementById('110') && (document.getElementById('110').textContent = '自定義1');
                    document.getElementById('111') && (document.getElementById('111').textContent = '自定義2');
                    document.getElementById('112') && (document.getElementById('112').textContent = '自定義3');
                    document.getElementById('113') && (document.getElementById('113').textContent = '自定義4');
                    document.getElementById('114') && (document.getElementById('114').textContent = '自定義5');
                }


            },
            error: function(xhr, status, error) {
            
            }
        }); 
    }
}



function tablesubmit(keyno){
    if(keyno =='show'){
        document.getElementById('TableDataInput').style.display = 'block';
        document.getElementById('input_menu').style.display = 'none';
        get_input_by_job_id(job_id);
    }
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

//delete
function delete_input_id(jobid,input_event){
    if(job_id){
        $.ajax({
            url: "?url=Inputs/delete_input",
            method: "POST",
            data: { 
                job_id: job_id,
                input_event: input_event,
             
            },
            success: function(response) {
            
                var responseData = JSON.parse(response);
                alertify.alert(responseData.res_type, responseData.res_msg, function() {
                    get_input_by_job_id(job_id);
                });

            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });     
    }

}


//create
function create_input_id(){
 
    var input_event = document.getElementById("Event_Option").value;
    var pinval      = collectPinValues('input[name="pin_option"]');
    var pin_old   = pinval[0]['id'];
    var input_wave  = pinval[0]['value'];
    var pagemode    = 1;
    var input_seqid = 0;

    if(input_event == 109){
        var selectedOption = document.querySelector('input[name="gateconfirm"]:checked');
        var gateconfirm    = selectedOption ? selectedOption.value : 0;
    }else{
        var gateconfirm	 = 0;
    }


    var input_pin = pin_old.match(/\d+/)[0];
    if(job_id){
        $.ajax({
            url: "?url=Inputs/create_input_event",
            method: "POST",
            data: { 
                job_id: job_id,
                input_event: input_event,
                input_pin: 	input_pin,
                input_wave: input_wave,
                gateconfirm: gateconfirm,
                pagemode: pagemode,
                input_seqid: input_seqid
            },
            success: function(response) {

                document.getElementById('newinput').style.display='none';
                var responseData = JSON.parse(response);
                alertify.alert(responseData.res_type, responseData.res_msg, function() {
                    get_input_by_job_id(job_id);
                });
            },
            error: function(xhr, status, error) {
                
            }
        });

    }
}

//copy
function copy_input_id(){
    var language = getCookie('language');
    if(language == "zh-cn"){
        var text_info ='若设定已存在，将会取代原有设定';
    }else if(language == "zh-tw"){
        var text_info ='若設定已存在，將會取代原有設定';
    }else{
        var text_info ='If the job input already exists, it will replace the original setting';
    }
    alertify.confirm( text_info , function (e) {
        if (e) {
            var to_job_id = document.getElementById("JobSelect1").value;
            if(to_job_id){
                $.ajax({
                    url: "?url=Inputs/copy_input_event",
                    method: "POST",
                    data: { 
                        from_job_id: job_id,
                        to_job_id: to_job_id
                    },
                    success: function(response) {
                        
                        document.getElementById('copyinput').style.display='none';
                        var responseData = JSON.parse(response);
                        alertify.alert(responseData.res_type, responseData.res_msg, function() {
                            get_input_by_job_id(job_id);
                        });

                        
                    },
                    error: function(xhr, status, error) {
                        
                    }
                });
        
            }

        } else {
            // cancel
        }
    });



}

function resetalignsubmit(job_id) {

    var job_id_new = 0;

    if(job_id_new == 0){
        console.log(job_id_new);
        console.log(job_id);
        $.ajax({
            url: "?url=Inputs/input_alljob",
            method: "POST",
            data: {
                job_id_new: job_id_new
            },
            success: function (response) {
                get_input_by_job_id(job_id);
            },
            error: function (xhr, status, error) {

            }
        });
    }
}




function alignsubmit(job_id) {
    if (job_id) {
        $.ajax({
            url: "?url=Inputs/input_alljob",
            method: "POST",
            data: {
                job_id: job_id
            },
            success: function (response) {
                get_input_by_job_id(job_id);
                buttonDisabled = !buttonDisabled;
                document.getElementById('Button_Select').disabled = buttonDisabled;
     
                backgroundColorYellow = !backgroundColorYellow;
                if (backgroundColorYellow){
                    document.getElementById('job_id').style.backgroundColor = 'yellow';
                }else{
                    document.getElementById('job_id').style.backgroundColor = '';
                }
            },
            error: function (xhr, status, error) {

            }
        });
    }
}



function enableButton() {
    var button = document.getElementById('Button_Select');
    if (button.disabled) {
        button.disabled = false;
    }
}

function resetBackgroundColor() {
    var jobInput = document.getElementById('job_id');
    if (jobInput.style.backgroundColor === 'yellow') {
        jobInput.style.backgroundColor = '';
    }
}

function get_input_info(){

    if(job_id){
        $.ajax({
            url: "?url=Inputs/check_job_event_conflict",
            method: "POST",
            data: { 
                job_id: job_id,
                input_event: input_event,
            },
            success: function(response) {
                
                var responseJSON = JSON.stringify(response);
                var cleanString = responseJSON.replace(/Array|\\n/g, '');
                var cleanString = cleanString.substring(2, cleanString.length - 2);

                var [, jobid] = cleanString.match(/\[input_job_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, input_event] = cleanString.match(/\[input_event]\s*=>\s*([^ ]+)/) || [, null];
                var [, input_pin] = cleanString.match(/\[input_pin]\s*=>\s*([^ ]+)/) || [, null];
                var [, input_wave] = cleanString.match(/\[input_wave]\s*=>\s*([^ ]+)/) || [, null];
                var [, gateconfirm] = cleanString.match(/\[gateconfirm]\s*=>\s*([^ ]+)/) || [, null];

                if(input_wave == 1){
                    var wave = "_high";
                }else{
                    var wave = "_low";
                }
                
                var edit_input_pin = "edit_pin" + input_pin + wave;
                var radioButton = document.getElementById(edit_input_pin);
                radioButton.removeAttribute('disabled');
                old_input_event = input_event;
                
                if(radioButton){
                    radioButton.checked = true;
                }

                document.querySelector("select[name='edit_Event_Option']").value = input_event;

                document.getElementById("edit_Event_Option").onchange = function() {
                    var selectedValue = this.value; 
                    edit_handleEventChange(selectedValue); 
                };

             
            },
            error: function(xhr, status, error) {
                
            }
        });
   
        
    }

}

function get_input_by_job_id(jobid){
    $.ajax({
        url: "?url=Inputs/get_input_by_job_id",
        method: "POST",
        data: { 
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
                });
            });

            var language = getCookie('language');
                if(language == "zh-cn"){

                    document.getElementById('101') && (document.getElementById('101').textContent = '禁用');
                    document.getElementById('102') && (document.getElementById('102').textContent = '启用');
                    document.getElementById('103') && (document.getElementById('103').textContent = '颗数清除');
                    document.getElementById('104') && (document.getElementById('104').textContent = '确认');
                    document.getElementById('105') && (document.getElementById('105').textContent = '启动');
                    document.getElementById('106') && (document.getElementById('106').textContent = '拆螺丝');
                    document.getElementById('107') && (document.getElementById('107').textContent = '工序清除');
                    document.getElementById('108') && (document.getElementById('108').textContent = '重启');
                    document.getElementById('109') && (document.getElementById('109').textContent = '一次感应');
                    document.getElementById('110') && (document.getElementById('110').textContent = '自定义1');
                    document.getElementById('111') && (document.getElementById('111').textContent = '自定义2');
                    document.getElementById('112') && (document.getElementById('112').textContent = '自定义3');
                    document.getElementById('113') && (document.getElementById('113').textContent = '自定义4');
                    document.getElementById('114') && (document.getElementById('114').textContent = '自定义5');
                
                }else if(language =="zh-tw"){
                    document.getElementById('101') && (document.getElementById('101').textContent = '禁用');
                    document.getElementById('102') && (document.getElementById('102').textContent = 'Enable');
                    document.getElementById('103') && (document.getElementById('103').textContent = '清除顆數');
                    document.getElementById('104') && (document.getElementById('104').textContent = '確認');
                    document.getElementById('105') && (document.getElementById('105').textContent = '啟動');
                    document.getElementById('106') && (document.getElementById('106').textContent = '拆螺絲');
                    document.getElementById('107') && (document.getElementById('107').textContent = '工序清除');
                    document.getElementById('108') && (document.getElementById('108').textContent = '重啟');
                    document.getElementById('109') && (document.getElementById('109').textContent = '一次感應');
                    document.getElementById('110') && (document.getElementById('110').textContent = '自定義1');
                    document.getElementById('111') && (document.getElementById('111').textContent = '自定義2');
                    document.getElementById('112') && (document.getElementById('112').textContent = '自定義3');
                    document.getElementById('113') && (document.getElementById('113').textContent = '自定義4');
                    document.getElementById('114') && (document.getElementById('114').textContent = '自定義5');
                }

        },
        error: function(xhr, status, error) {
            console.error("AJAX request failed:", status, error);
        }
    }); 
}


function edit_input_id(){

    var input_event = document.getElementById("edit_Event_Option").value;
    var pinval      = collectPinValues('input[name="edit_pin_option"]');
    var pin_old   = pinval[0]['id'];
    var input_wave  = pinval[0]['value'];
    var pagemode    = 1;
    var input_seqid = 0;
    var input_pin = pin_old.match(/\d+/)[0];

    
    if(input_event == 109){
        var selectedOption = document.querySelector('input[name="edit_gateconfirm"]:checked');
        var gateconfirm    = selectedOption ? selectedOption.value : 0;
    }else{
        var gateconfirm	 = 0;
    }

    if(job_id){
        $.ajax({
            url: "?url=Inputs/edit_input_event",
            method: "POST",
            data: { 
                job_id: job_id,
                input_event: input_event,
                input_pin: 	input_pin,
                input_wave: input_wave,
                gateconfirm: gateconfirm,
                pagemode: pagemode,
                input_seqid: input_seqid,
                old_input_event: old_input_event
            },
            success: function(response) {

                document.getElementById('edit_input').style.display='none';
                var responseData = JSON.parse(response);
                alertify.alert(responseData.res_type, responseData.res_msg, function() {
                    get_input_by_job_id(job_id);
                });

            },
            error: function(xhr, status, error) {
                
            }
        });

    }
}


