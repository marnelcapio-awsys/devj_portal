$(document).ready((function(){var e=$("#laptop-list").DataTable({stateSave:!0,bFilter:!1,pageLength:25,oLanguage:{sEmptyTable:"No Data"}});function a(){$.ajax({type:"GET",url:"/api/laptops/search",data:{keyword:$("input[name=searchInput]").val(),availability:$("input[name=laptopAvailability]:checked").val(),status:$("input[name=laptopStatus]:checked").val()},dataType:"json",encode:!0}).done((function(a){console.log(a),a.success&&(e.clear().draw(),a.update.forEach((function(a){e.row.add(['<a href="'+window.location.href+a.id+'">'+a.tag_number+"</a>",a.peza_form_number,a.peza_permit_number,a.laptop_make,a.laptop_model,a.status]).draw(!1)})))})).fail((function(){console.log("error")}))}$(".laptop-search-availability").click((function(){a()})),$(".laptop-status").click((function(){a()})),$("#search-input").on("input",(function(){a()})),$("#edit-form").submit((function(e){var a=$("#edit-form").serializeArray(),r=[];a.forEach((function(e){r[e.name]=e.value}));var t=JSON.stringify(Object.assign({},r));t=JSON.parse(t),console.log(t),$.ajax({type:"POST",url:"/api/laptops/update",data:t,dataType:"json",encode:!0}).done((function(e){for(var a in t)$("#"+a+"-error").empty();if(e.success)location.reload();else for(var a in errors=e.data,errors)$("#"+a+"-error").html(errors[a][0])})).fail((function(){console.log("error")})),e.preventDefault()})),$("#link-req-tbl").DataTable({stateSave:!0,bFilter:!1,ordering:!1,pageLength:10,oLanguage:{sEmptyTable:"No Data"}}),$("#update-linkage-form").submit((function(e){var a=$("#update-linkage-form").serializeArray(),r=[];a.forEach((function(e){r[e.name]=e.value})),r.brought_home_flag=$("#ul-brought-home").is(":checked")?1:0,r.vpn_flag=$("#ul-vpn").is(":checked")?1:0,r.surrender_flag=$("#ul-surrender").is(":checked")?1:0;var t=JSON.stringify(Object.assign({},r));t=JSON.parse(t),$.ajax({type:"POST",url:"/api/laptops/updateLinkage",data:t,dataType:"json",encode:!0}).done((function(e){for(var a in t)$("#ul-"+a+"-error").empty();if(e.success)location.reload();else for(var a in errors=e.data,errors)$("#ul-"+a+"-error").html(errors[a][0]).addClass("text-danger")})).fail((function(){console.log("error")})),e.preventDefault()})),$("#link-form").submit((function(e){var a=$("#link-form").serializeArray(),r=[];a.forEach((function(e){r[e.name]=e.value})),r.brought_home_flag=$("#ll-brought-home").is(":checked")?1:0,r.vpn_flag=$("#ll-vpn").is(":checked")?1:0;var t=JSON.stringify(Object.assign({},r));t=JSON.parse(t),console.log(t),$.ajax({type:"POST",url:"/api/laptops/registLinkage",data:t,dataType:"json",encode:!0}).done((function(e){for(var a in t)$("#ll-"+a+"-error").empty();if(e.success)location.reload();else for(var a in errors=e.data,errors)console.log("#ll-"+a+"-error"),$("#ll-"+a+"-error").html(errors[a][0]).addClass("text-danger")})).fail((function(){console.log("error")})),e.preventDefault()})),$("#emp-hist-tbl").DataTable({stateSave:!0,pageLength:10,ordering:!1,bFilter:!1,oLanguage:{sEmptyTable:"No Data"}}),$("#reject-request-form").submit((function(){return""==$("#reject-reason").val()?(console.log("hello"),$("#reject-reason-error").html("The reason field is required.").addClass("text-danger text-start"),!1):$("#reject-reason").val().length>1024?($("#reject-reason-error").html("The reason must not be greater than 1024 characters.").addClass("text-danger text-start"),!1):void $("#reject-sub").prop("disabled",!0)}))}));