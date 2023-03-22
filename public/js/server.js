(()=>{function t(t){return function(t){if(Array.isArray(t))return e(t)}(t)||function(t){if("undefined"!=typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}(t)||function(t,n){if(!t)return;if("string"==typeof t)return e(t,n);var i=Object.prototype.toString.call(t).slice(8,-1);"Object"===i&&t.constructor&&(i=t.constructor.name);if("Map"===i||"Set"===i)return Array.from(t);if("Arguments"===i||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(i))return e(t,n)}(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function e(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,i=new Array(e);n<e;n++)i[n]=t[n];return i}$(document).ready((function(){t(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map((function(t){return new bootstrap.Tooltip(t)}));var e=$("#server-list").DataTable({stateSave:!0,pageLength:25,oLanguage:{sEmptyTable:"There is no record found",sZeroRecords:"There is no record found",sInfoFiltered:""},sDom:"lrt<'#bottom.row'<'#info.col'i><'#pagination.col'p>>"});function n(){e.column(3).search("").draw(),e.search("").draw()}function i(){1==$(".partition_section").length?$(".partition_section .remove_partition").hide():$(".partition_section .remove_partition").show()}function a(t){var e=t.attr("id").match(/\d+/);e=parseInt(e),$("#hdd_size_radio_"+e).is(":checked")?r(e):$("#hdd_percentage_radio_"+e).is(":checked")&&r(e,!1)}function r(t){var e=!(arguments.length>1&&void 0!==arguments[1])||arguments[1],n=!e,i=!!e;$("#hdd_used_percentage_"+t).prop("disabled",i),$("#hdd_free_percentage_"+t).prop("disabled",i),$("#hdd_used_"+t).prop("disabled",n),$("#hdd_used_unit_"+t).prop("disabled",n),$("#hdd_free_"+t).prop("disabled",n),$("#hdd_free_unit_"+t).prop("disabled",n)}function o(){$("#memory_size_radio").is(":checked")?d():$("#memory_percentage_radio").is(":checked")&&d(!1)}function d(){var t=!(arguments.length>0&&void 0!==arguments[0])||arguments[0],e=!t,n=!!t;$("#memory_percentage_section").find("input[type=text]").each((function(){$(this).prop("disabled",n)})),$("#memory_size_section").find("input[type=text]").each((function(){$(this).prop("disabled",e)})),$("#memory_size_section").find("select").each((function(){$(this).prop("disabled",e)}))}function s(){$("#linux_radio").is(":checked")?c():$("#other_os_radio").is(":checked")&&c(!1)}function c(){var t=!(arguments.length>0&&void 0!==arguments[0])||arguments[0],e=!t,n=!!t;$("#linux_usage").find("input[type=text]").each((function(){$(this).prop("disabled",e)})),$("input[name=other_os_percentage").prop("disabled",n)}function u(t){if(""!=t.find(".hdd_total").val()&&""!=t.find(".hdd_total_unit").val()){var e=_(t.find(".hdd_total_unit").val()),n=_(t.find(".hdd_total").val()),i=f(n,e);if(t.find(".hdd_size_radio").is(":checked")&&""!=t.find(".hdd_used").val()&&""!=t.find(".hdd_used_unit").val()){var a=_(t.find(".hdd_used_unit").val()),r=f(_(t.find(".hdd_used").val()),a),o=m(i,r,a);t.find(".hdd_free_unit > option[value="+a+"]").prop("selected",!0),t.find(".hdd_free").val(o),percentage=parseFloat(100*r/i).toFixed(2);var d=parseFloat(100-percentage).toFixed(2);t.find(".hdd_used_percentage").val(percentage),t.find(".hdd_free_percentage").val(d)}else if(t.find(".hdd_percentage_radio").is(":checked")&&""!=t.find(".hdd_used_percentage").val()){percentage=parseFloat(t.find(".hdd_used_percentage").val().toString());d=parseFloat(100-percentage).toFixed(2);t.find(".hdd_free_percentage").val(d),t.find(".hdd_free_unit > option[value="+e+"]").prop("selected",!0),t.find(".hdd_used_unit > option[value="+e+"]").prop("selected",!0);var s=(percentage*n/100).toFixed(2);o=(n-s).toFixed(2);t.find(".hdd_used").val(s),t.find(".hdd_free").val(o)}}}function p(){var t,e,n=0,i=0;if($(".partition_section").each((function(){var a=$(this);t=0,e=0,""!=a.find(".hdd_total").val()&&""!=a.find(".hdd_total_unit").val()&&""!=a.find(".hdd_used").val()&&""!=a.find(".hdd_used_unit").val()&&(t=f(_(a.find(".hdd_total").val()),_(a.find(".hdd_total_unit").val())),e=f(_(_(a.find(".hdd_used").val())),_(a.find(".hdd_used_unit").val())),n+=t,i+=e)})),0!=n){var a=i/n*100;a=a.toFixed(2),g($("#hdd_status"),$("input[name=hdd_status]"),a)}}function _(t){var e=parseFloat(t.toString());return isNaN(e)?0:e}function l(){var t;$("#other_os_radio").is(":checked")&&""!=$("input[name=other_os_percentage]").val()?t=v($("input[name=other_os_percentage]")):!$("#linux_radio").is(":checked")||""==$("#us").val()&&""==$("#ni").val()&&""==$("#sy").val()||(t=(v($("#us"))+v($("#ni"))+v($("#sy")))/3),g($("#cpu_status"),$("input[name=cpu_status]"),t)}function h(){var t;if(""!=$("#memory_total").val()){var e=v($("#memory_total_unit > option:selected")),n=v($("#memory_total")),i=f(n,e);if($("#memory_size_radio").is(":checked")&&""!=$("#memory_used").val()&&""!=$("#memory_used_unit").val()){var a=v($("#memory_used_unit > option:selected")),r=f(v($("#memory_used")),a),o=m(i,r,a);$("#memory_free_unit > option[value="+a+"]").prop("selected",!0),$("input[name=memory_free]").val(o),t=parseFloat(100*r/i).toFixed(2);var d=parseFloat(100-t).toFixed(2);$("input[name=memory_used_percentage]").val(t),$("input[name=memory_free_percentage]").val(d)}else if($("#memory_percentage_radio").is(":checked")&&""!=$("#memory_used_percentage").val()){t=parseFloat($("#memory_used_percentage").val().toString());d=parseFloat(100-t).toFixed(2);$("#memory_free_percentage").val(d),$("#memory_free_unit option[value="+e+"]").prop("selected",!0),$("#memory_used_unit option[value="+e+"]").prop("selected",!0);var s=(t*n/100).toFixed(2);o=(n-s).toFixed(2);$("#memory_used").val(s),$("#memory_free").val(o)}g($("#memory_status"),$("input[name=memory_status]"),t)}}function f(t,e){return t*Math.pow(1024,e-1)}function m(t,e,n){var i=Math.pow(1024,n-1);return parseFloat(((t-e)/i).toFixed(2))}function v(t){var e=parseFloat(t.val().toString());return isNaN(e)?0:e}function g(t,e,n){void 0===n||!Number.isInteger(n)&&null===n.toString().match(/\d+[.]?\d+/)?(t.empty(),e.val("")):n<=60?(t.text("Normal").removeClass("text-danger text-primary").addClass("text-black"),e.val(1)):n<=89?(t.text("Stable").removeClass("text-danger text-black").addClass("text-primary"),e.val(2)):(t.text("Critical").removeClass("text-black text-primary").addClass("text-danger"),e.val(3))}n(),$(".server-status").on("change",(function(){var t=$("input[name=serverStatus]:checked").val();if(1==t)n();else{if(2==t)var i="^Active$";else if(3==t)i="^Inactive$";e.column(3).search(i,!0,!1).draw()}})),$("#search-input").on("input",(function(){var t=$(this).val();e.search(t).draw()})),$("#add_partition").click((function(t){var e=$(".partition_section").last();1==$(".partition_section").length&&e.find(".remove_partition").show();var n=e.clone();n.find("[id]").each((function(){$(this).attr("id",$(this).attr("id").replace(/(\d+)/,(function(t,e){return parseInt(e)+1})))})),n.find("[name]").each((function(){$(this).attr("name",$(this).attr("name").replace(/(\d+)/,(function(t,e){return parseInt(e)+1}))),"radio"==$(this).attr("type")?1==$(this).attr("value")?$(this).prop("checked",!0):$(this).prop("checked",!1):"text"==$(this).attr("type")?$(this).val(""):$(this).val(1)})),n.find("[for]").each((function(){$(this).attr("for",$(this).attr("for").replace(/(\d+)/,(function(t,e){return parseInt(e)+1})))})),n.find(".text-danger").each((function(){$(this).html("")})),e.after(n),r(parseInt(n.find(".partition_name").attr("id").match(/\d+/)),!0),t.preventDefault()})),$("#hdd_partitions").on("click",".remove_partition",(function(t){var e;$(this).parents(".partition_section").remove(),i(),e=1,$(".partition_section").each((function(){var t=$(this);t.find("[id]").each((function(){$(this).attr("id",$(this).attr("id").replace(/(\d+)/,e))})),t.find("[name]").each((function(){$(this).attr("name",$(this).attr("name").replace(/(\d+)/,e))})),t.find("[for]").each((function(){$(this).attr("for",$(this).attr("for").replace(/(\d+)/,e))})),e++})),t.preventDefault()})),i(),$(".partition_section").each((function(){a($(this).find(".hdd_select_radio"))})),$("#hdd_partitions").on("click",".hdd_select_radio",(function(){a($(this))})),o(),$("input[name=memory_input_type]").change((function(){o()})),s(),$("input[name=os_type]").change((function(){s()})),$("#hdd_partitions").on("change",".hdd_select_radio",(function(){u($(this).parents(".partition_section")),p()})),$("#hdd_partitions").on("input",".hdd_total",(function(){u($(this).parents(".partition_section")),p()})),$("#hdd_partitions").on("change",".hdd_total_unit",(function(){u($(this).parents(".partition_section")),p()})),$("#hdd_partitions").on("input",".hdd_used",(function(){u($(this).parents(".partition_section")),p()})),$("#hdd_partitions").on("change",".hdd_used_unit",(function(){u($(this).parents(".partition_section")),p()})),$("#hdd_partitions").on("input",".hdd_used_percentage",(function(){u($(this).parents(".partition_section")),p()})),$("input[name=os_type]").on("change",(function(){l()})),$("#us").on("input",(function(){l()})),$("#sy").on("input",(function(){l()})),$("#ni").on("input",(function(){l()})),$("input[name=other_os_percentage]").on("input",(function(){l()})),$("input[name=memory_input_type]").on("change",(function(){h()})),$("input[name=memory_total]").on("input",(function(){h()})),$("#memory_total_unit").on("change",(function(){h()})),$("input[name=memory_used]").on("input",(function(){h()})),$("#memory_used_unit").on("change",(function(){h()})),$("input[name=memory_used_percentage]").on("input",(function(){h()})),$("#server_reg_form").on("submit",(function(){$("#usage").find("input").each((function(){$(this).prop("disabled",!1)})),$("#usage").find("select").each((function(){$(this).prop("disabled",!1)})),$("input[name=partitions_count]").val($(".partition_section").length)}))}))})();