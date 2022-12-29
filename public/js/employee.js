$(document).ready((function(){var t=$("#employee-list").DataTable({stateSave:!0,bFilter:!1,pageLength:25,oLanguage:{sEmptyTable:"There is no record found"}});function a(){var e=$("input[name='searchInput']").val(),a=$("input[name='searchFilter']:checked").val(),r=$("input[name='employeeStatus']:checked").val();$.ajax({type:"get",url:"api/employees/search",data:{keyword:e,filter:a,status:r},success:function(e){t.clear().draw(),JSON.parse(e).forEach((function(e){var a="";0==e.active_status?a=1==e.approved_status||2==e.approved_status||4==e.approved_status?"Deactivated":"Pending for Approval":1==e.active_status&&(a=1==e.approved_status?"Deactivated":2==e.approved_status||4==e.approved_status?"Active":"Pending for Approval"),url=window.location.href+"/"+e.id,t.row.add(['<a href="'+url+'">'+e.last_name+", "+e.first_name+" ("+e.middle_name+")</a>",e.email,e.cellphone_number,e.current_address_city,e.current_address_province,a]).draw(!1)}))}})}function r(){var e=!1;$(":input[required]").each((function(){""==$(this).val()&&(e=!0)})),e?$("#emp-reg-submit").prop("disabled",!0):$("#emp-reg-submit").prop("disabled",!1)}$("#send-notif").on("click",(function(){$(".spinner-border").show()})),$(".search-status-rdb-input").on("click",(function(){a()})),$(".search-filter-rdb-input").on("click",(function(){a()})),$("#search-input").on("input",(function(){a()})),r(),$(":input[required]").change((function(){r()})),$("#emp-confirm-password, #emp-password").keyup((function(){$("#emp-confirm-password").val()!=$("#emp-password").val()?($("#confirm-pass-text").html("Password does not match.").addClass("text-danger text-start"),$("#emp-reg-submit").prop("disabled",!0)):($("#confirm-pass-text").html(""),r())})),$(".btn-prevent-multiple-submit").on("submit",(function(t){e.preventDefault(),$(".btn-prevent-multiple-submit").prop("disabled",!0)})),$("#cp-confirm-pw, #cp-new-pw").keyup((function(){$("#cp-confirm-pw").val()!=$("#cp-new-pw").val()?($("#confirm-pass-text").html("Password does not match.").addClass("text-danger text-start"),$("#ecp-submit-btn").prop("disabled",!0)):$("#confirm-pass-text").html("")})),$("#cp-submit-btn").click((function(e){var t={_token:$("#changePasswordForm > input[name=_token]").val(),current_password:$("#cp-current-pw").val(),new_password:$("#cp-new-pw").val(),id:$("#changePasswordForm > input[name=cp_id]").val()};$.ajax({type:"POST",url:"/api/changePassword",data:t,dataType:"json",encode:!0}).done((function(e){if(e.success)$("#changePasswordForm").trigger("reset"),$("#current-pass-error").empty(),$("#new-pass-error").empty(),$("#cp-success-msg").html('<i class="bi bi-check-circle-fill"></i>&nbsp;You have successfully changed your account password.').addClass("text-success mb-4 text-start");else{$("#cp-success-msg").empty(),$("#current-pass-error").empty(),$("#new-pass-error").empty();var t=e.data.current_password;t&&t.length>0&&$("#current-pass-error").html(t[0]).addClass("text-danger text-start");var a=e.data.new_password;a&&a.length>0&&$("#new-pass-error").html(a[0]).addClass("text-danger text-start")}})).fail((function(){console.log("error")})),e.preventDefault()}));var s=$("#project-tbl").DataTable({stateSave:!0,bFilter:!1,bPaginate:!1,bInfo:!1,oLanguage:{sEmptyTable:"No Data"}});$("#lp-submit-btn").click((function(e){$("#lp-submit-btn").prop("disabled",!0);var t={_token:$("#linkProjectForm > input[name=_token]").val(),employee_id:$("#linkProjectForm > input[name=lp_employee_id]").val(),project_id:$("#projectList > option:selected").val(),project_start:$("#project-start").val(),project_end:$("#project-end").val(),project_role:$("#projectRoleList > option:selected").val(),project_onsite:$("#project-onsite").is(":checked")?1:0};$.ajax({type:"POST",url:"/api/linkProject",data:t,dataType:"json",encode:!0}).done((function(e){if(e.success)$("#linkProjectForm").trigger("reset"),$("#error-lp-proj-name").empty(),$("#error-lp-proj-role").empty(),$("#error-lp-proj-start").empty(),$("#error-lp-proj-end").empty(),$("#projectList > option[value="+t.project_id+"]").remove(),$("#lp-success-msg").html('<i class="bi bi-check-circle-fill"></i>&nbsp;'+e.message+".").addClass("text-success mb-2 text-start"),s.clear().draw(),e.update.forEach((function(e){var t=window.location.origin+"/devj_portal/projects/"+e.project_id;sDate=new Date(e.start_date),spanStart=sDate.getFullYear()+"/"+sDate.getMonth()+"/"+sDate.getDate()+" - ",spanEnd="",""!=e.end_date&&null!=e.end_date&&(eDate=new Date(e.end_date),spanEnd=eDate.getFullYear()+"/"+eDate.getMonth()+"/"+eDate.getDate()),s.row.add(['<a href="'+t+'" class="text-decoration-none">'+e.name+"</a>",spanStart+spanEnd,e.project_status]).draw(!1)}));else{$("#lp-success-msg").empty(),$("#error-lp-proj-name").empty(),$("#error-lp-proj-role").empty(),$("#error-lp-proj-start").empty(),$("#error-lp-proj-end").empty();var a=e.data.project_id;a&&a.length>0&&$("#error-lp-proj-name").html(a[0]).addClass("text-danger text-start");var r=e.data.project_role;r&&r.length>0&&$("#error-lp-proj-role").html(r[0]).addClass("text-danger text-start");var o=e.data.project_start;o&&o.length>0&&$("#error-lp-proj-start").html(o[0]).addClass("text-danger text-start");var n=e.data.project_end;n&&n.length>0&&$("#error-lp-proj-end").html(n[0]).addClass("text-danger text-start")}$("#lp-submit-btn").prop("disabled",!1)})).fail((function(){console.log("error")})),e.preventDefault()}));var o=$("#laptop-tbl").DataTable({stateSave:!0,bFilter:!1,bPaginate:!1,bInfo:!1,oLanguage:{sEmptyTable:"No Data"}});function n(){8==$("#position").val()||9==$("#position").val()?($("#admin-check").hide(),$("#admin-detail").hide(),$("#is-admin").prop("disabled",!0)):($("#admin-check").show(),$("#admin-detail").show(),$("#is-admin").prop("disabled",!1))}function p(){var e=$("#projectList :selected").data("mindate"),t=$("#projectList :selected").data("maxdate");$("#project-start").attr({min:e,max:t}),$("#project-end").attr({min:e,max:t})}$("#ll-submit-btn").click((function(e){$("#ll-submit-btn").prop("disabled",!0);var t={_token:$("#linkLaptopForm > input[name=_token]").val(),employee_id:$("#linkLaptopForm > input[name=ll_employee_id]").val(),laptop_id:$("#laptopList > option:selected").val(),brought_home_flag:$("#ll-brought-home").is(":checked")?1:0,vpn_access_flag:$("#ll-vpn").is(":checked")?1:0};$.ajax({type:"POST",url:"/api/linkLaptop",data:t,dataType:"json",encode:!0}).done((function(e){if(e.success)$("#linkLaptopForm").trigger("reset"),$("#error-laptop-id").empty(),$("#laptopList > option[value="+t.laptop_id+"]").remove(),$("#ll-success-msg").html('<i class="bi bi-check-circle-fill"></i>&nbsp;'+e.message+".").addClass("text-success mb-2 text-start"),o.clear().draw(),e.update.forEach((function(e){var t=window.location.origin+"/devj_portal/laptops/"+e.id;o.row.add(['<a href="'+t+'" class="text-decoration-none">'+e.tag_number+"</a>",e.brought_home,e.laptop_make,e.laptop_model,e.use_vpn]).draw(!1)}));else{$("#ll-success-msg").empty(),$("#error-laptop-id").empty();var a=e.data.laptop_id;a&&a.length>0&&$("#error-laptop-id").html(a[0]).addClass("text-danger text-start")}$("#ll-submit-btn").prop("disabled",!1)})).fail((function(){console.log("error")})),e.preventDefault()})),$("#reject-request-form").submit((function(){return""==$("#reject-reason").val()?(console.log("hello"),$("#reject-reason-error").html("The reason field is required.").addClass("text-danger text-start"),!1):$("#reject-reason").val().length>1024?($("#reject-reason-error").html("The reason must not be greater than 1024 characters.").addClass("text-danger text-start"),!1):void $("#reject-sub").prop("disabled",!0)})),$("#approve-request-form").submit((function(){$("#approve-request").prop("disabled",!0)})),$("#emp-update-form").submit((function(){$("#active-status").is(":checked")&&$("#active-status-hidden").prop("disabled",!0),$("#server-manage-flag").is(":checked")&&$("#server-manage-flag-hidden").prop("disabled",!0),$("#emp-update-submit").prop("disabled",!0)})),$("linkProjectModal").on("hidden.bs.modal",(function(){$("#lp-success-msg").empty()})),$("#linkLaptopModal").on("hidden.bs.modal",(function(){$("#ll-success-msg").empty()})),$("#changePasswordModal").on("hidden.bs.modal",(function(){$("#cp-success-msg").empty()})),n(),$("#position").change((function(){n()})),p(),$("#projectList").change((function(e){p()}))}));