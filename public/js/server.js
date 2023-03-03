$(document).ready((function(){[].slice.call($('[data-bs-toggle="tooltip"]')).map((function(t){return new bootstrap.Tooltip(t)}));var t=$("#server-list").DataTable({stateSave:!0,pageLength:25,oLanguage:{sEmptyTable:"No Data"},sDom:"rtip"});function e(){t.column(3).search("").draw(),t.search("").draw()}function n(){1==$(".partition_section").length?$(".partition_section .remove_partition").hide():$(".partition_section .remove_partition").show()}function i(t){var e=t.attr("id").match(/\d+/);e=parseInt(e),$("#hdd_size_radio_"+e).is(":checked")?a(e):$("#hdd_percentage_radio_"+e).is(":checked")&&a(e,!1)}function a(t){var e=!(arguments.length>1&&void 0!==arguments[1])||arguments[1],n=!e,i=!!e;$("#hdd_used_percentage_"+t).prop("disabled",i),$("#hdd_free_percentage_"+t).prop("disabled",i),$("#hdd_used_"+t).prop("disabled",n),$("#hdd_used_unit_"+t).prop("disabled",n),$("#hdd_free_"+t).prop("disabled",n),$("#hdd_free_unit_"+t).prop("disabled",n)}function o(){$("#memory_size_radio").is(":checked")?d():$("#memory_percentage_radio").is(":checked")&&d(!1)}function d(){var t=!(arguments.length>0&&void 0!==arguments[0])||arguments[0],e=!t,n=!!t;$("#memory_percentage_section").find("input[type=text]").each((function(){$(this).prop("disabled",n)})),$("#memory_size_section").find("input[type=text]").each((function(){$(this).prop("disabled",e)})),$("#memory_size_section").find("select").each((function(){$(this).prop("disabled",e)}))}function r(){$("#linux_radio").is(":checked")?s():$("#other_os_radio").is(":checked")&&s(!1)}function s(){var t=!(arguments.length>0&&void 0!==arguments[0])||arguments[0],e=!t,n=!!t;$("#linux_usage").find("input[type=text]").each((function(){$(this).prop("disabled",e)})),$("input[name=other_os_percentage").prop("disabled",n)}function c(t){if(""!=t.find(".hdd_total").val()&&""!=t.find(".hdd_total_unit").val()){var e=u(t.find(".hdd_total_unit").val()),n=u(t.find(".hdd_total").val()),i=l(n,e);if(t.find(".hdd_size_radio").is(":checked")&&""!=t.find(".hdd_used").val()&&""!=t.find(".hdd_used_unit").val()){var a=u(t.find(".hdd_used_unit").val()),o=l(u(t.find(".hdd_used").val()),a),d=f(i,o,a);t.find(".hdd_free_unit > option[value="+a+"]").prop("selected",!0),t.find(".hdd_free").val(d),percentage=parseFloat(100*o/i).toFixed(2);var r=parseFloat(100-percentage).toFixed(2);t.find(".hdd_used_percentage").val(percentage),t.find(".hdd_free_percentage").val(r)}else if(t.find(".hdd_percentage_radio").is(":checked")&&""!=t.find(".hdd_used_percentage").val()){percentage=parseFloat(t.find(".hdd_used_percentage").val().toString()),r=parseFloat(100-percentage).toFixed(2),t.find(".hdd_free_percentage").val(r),t.find(".hdd_free_unit > option[value="+e+"]").prop("selected",!0),t.find(".hdd_used_unit > option[value="+e+"]").prop("selected",!0);var s=(percentage*n/100).toFixed(2);d=(n-s).toFixed(2),t.find(".hdd_used").val(s),t.find(".hdd_free").val(d)}}}function p(){var t,e,n=0,i=0;if($(".partition_section").each((function(){var a=$(this);t=0,e=0,""!=a.find(".hdd_total").val()&&""!=a.find(".hdd_total_unit").val()&&""!=a.find(".hdd_used").val()&&""!=a.find(".hdd_used_unit").val()&&(t=l(u(a.find(".hdd_total").val()),u(a.find(".hdd_total_unit").val())),e=l(u(u(a.find(".hdd_used").val())),u(a.find(".hdd_used_unit").val())),n+=t,i+=e)})),0!=n){var a=i/n*100;a=a.toFixed(2),v($("#hdd_status"),$("input[name=hdd_status]"),a)}}function u(t){var e=parseFloat(t.toString());return isNaN(e)?0:e}function _(){var t;$("#other_os_radio").is(":checked")&&""!=$("input[name=other_os_percentage]").val()?t=m($("input[name=other_os_percentage]")):!$("#linux_radio").is(":checked")||""==$("#us").val()&&""==$("#ni").val()&&""==$("#sy").val()||(t=(m($("#us"))+m($("#ni"))+m($("#sy")))/3),v($("#cpu_status"),$("input[name=cpu_status]"),t)}function h(){var t;if(""!=$("#memory_total").val()){var e=m($("#memory_total_unit > option:selected")),n=m($("#memory_total")),i=l(n,e);if($("#memory_size_radio").is(":checked")&&""!=$("#memory_used").val()&&""!=$("#memory_used_unit").val()){var a=m($("#memory_used_unit > option:selected")),o=l(m($("#memory_used")),a),d=f(i,o,a);$("#memory_free_unit > option[value="+a+"]").prop("selected",!0),$("input[name=memory_free]").val(d),t=parseFloat(100*o/i).toFixed(2);var r=parseFloat(100-t).toFixed(2);$("input[name=memory_used_percentage]").val(t),$("input[name=memory_free_percentage]").val(r)}else if($("#memory_percentage_radio").is(":checked")&&""!=$("#memory_used_percentage").val()){t=parseFloat($("#memory_used_percentage").val().toString()),r=parseFloat(100-t).toFixed(2),$("#memory_free_percentage").val(r),$("#memory_free_unit option[value="+e+"]").prop("selected",!0),$("#memory_used_unit option[value="+e+"]").prop("selected",!0);var s=(t*n/100).toFixed(2);d=(n-s).toFixed(2),$("#memory_used").val(s),$("#memory_free").val(d)}v($("#memory_status"),$("input[name=memory_status]"),t)}}function l(t,e){return t*Math.pow(1024,e-1)}function f(t,e,n){var i=Math.pow(1024,n-1);return parseFloat(((t-e)/i).toFixed(2))}function m(t){var e=parseFloat(t.val().toString());return isNaN(e)?0:e}function v(t,e,n){void 0===n||!Number.isInteger(n)&&null===n.toString().match(/\d+[.]?\d+/)?(t.empty(),e.val("")):n<=60?(t.text("Normal").removeClass("text-danger text-primary").addClass("text-black"),e.val(1)):n<=89?(t.text("Stable").removeClass("text-danger text-black").addClass("text-primary"),e.val(2)):(t.text("Critical").removeClass("text-black text-primary").addClass("text-danger"),e.val(3))}e(),$(".server-status").on("change",(function(){var n=$("input[name=serverStatus]:checked").val();if(1==n)e();else{if(2==n)var i="^Active$";else 3==n&&(i="^Inactive$");t.column(3).search(i,!0,!1).draw()}})),$("#search-input").on("input",(function(){var e=$(this).val();t.search(e).draw()})),$("#add_partition").click((function(t){var e=$(".partition_section").last();1==$(".partition_section").length&&e.find(".remove_partition").show();var n=e.clone();n.find("[id]").each((function(){$(this).attr("id",$(this).attr("id").replace(/(\d+)/,(function(t,e){return parseInt(e)+1})))})),n.find("[name]").each((function(){$(this).attr("name",$(this).attr("name").replace(/(\d+)/,(function(t,e){return parseInt(e)+1}))),"radio"==$(this).attr("type")?1==$(this).attr("value")?$(this).prop("checked",!0):$(this).prop("checked",!1):"text"==$(this).attr("type")?$(this).val(""):$(this).val(1)})),n.find("[for]").each((function(){$(this).attr("for",$(this).attr("for").replace(/(\d+)/,(function(t,e){return parseInt(e)+1})))})),n.find(".text-danger").each((function(){$(this).html("")})),e.after(n),a(parseInt(n.find(".partition_name").attr("id").match(/\d+/)),!0),t.preventDefault()})),$("#hdd_partitions").on("click",".remove_partition",(function(t){var e;$(this).parents(".partition_section").remove(),n(),e=1,$(".partition_section").each((function(){var t=$(this);t.find("[id]").each((function(){$(this).attr("id",$(this).attr("id").replace(/(\d+)/,e))})),t.find("[name]").each((function(){$(this).attr("name",$(this).attr("name").replace(/(\d+)/,e))})),t.find("[for]").each((function(){$(this).attr("for",$(this).attr("for").replace(/(\d+)/,e))})),e++})),t.preventDefault()})),n(),$(".partition_section").each((function(){i($(this).find(".hdd_select_radio"))})),$("#hdd_partitions").on("click",".hdd_select_radio",(function(){i($(this))})),o(),$("input[name=memory_input_type]").change((function(){o()})),r(),$("input[name=os_type]").change((function(){r()})),$("#hdd_partitions").on("change",".hdd_select_radio",(function(){c($(this).parents(".partition_section")),p()})),$("#hdd_partitions").on("input",".hdd_total",(function(){c($(this).parents(".partition_section")),p()})),$("#hdd_partitions").on("change",".hdd_total_unit",(function(){c($(this).parents(".partition_section")),p()})),$("#hdd_partitions").on("input",".hdd_used",(function(){c($(this).parents(".partition_section")),p()})),$("#hdd_partitions").on("change",".hdd_used_unit",(function(){c($(this).parents(".partition_section")),p()})),$("#hdd_partitions").on("input",".hdd_used_percentage",(function(){c($(this).parents(".partition_section")),p()})),$("input[name=os_type]").on("change",(function(){_()})),$("#us").on("input",(function(){_()})),$("#sy").on("input",(function(){_()})),$("#ni").on("input",(function(){_()})),$("input[name=other_os_percentage]").on("input",(function(){_()})),$("input[name=memory_input_type]").on("change",(function(){h()})),$("input[name=memory_total]").on("input",(function(){h()})),$("#memory_total_unit").on("change",(function(){h()})),$("input[name=memory_used]").on("input",(function(){h()})),$("#memory_used_unit").on("change",(function(){h()})),$("input[name=memory_used_percentage]").on("input",(function(){h()})),$("#server_reg_form").on("submit",(function(){$("#usage").find("input").each((function(){$(this).prop("disabled",!1)})),$("#usage").find("select").each((function(){$(this).prop("disabled",!1)})),$("input[name=partitions_count]").val($(".partition_section").length)}))}));