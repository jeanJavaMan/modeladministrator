<script>

    $(document).ready(function () {
        $(".js-select").select2({width: '100%'});
    });

    function add_search_field(element) {
        var tbody = $("#tbody_search");
        tbody.append($("#tr_copy").val());
        var td = $("#table_search > tbody > tr").last().prev().find("td")[3];
        $(td).append($("#operator_relation").val());
        observeSelectTableSearch("#table_search > tbody > tr:last-child > td:first-child > select");
        $("#table_search > tbody > tr:last-child > td > select").select2({width: '100%'});
        $(td).find("select").select2({width: '100%'});
    }

    function remove_search_field(element) {
        $(element).parent().parent().remove();
        if ($("#table_search > tbody > tr").length === 1) {
            var td = $("#table_search > tbody > tr > td")[3];
            $(td).html("");
        }
    }

    function focusInDiv(focus) {
        if (count_expand_table == 0 && focus) {
            $("body").append("<div id='focusintable' class='background-show'></div>");
            $("#table_container").addClass("focused-div");
        } else if (!focus && count_expand_table == 0) {
            $("#focusintable").remove();
            $("#table_container").removeClass("focused-div");
        }
    }

    $.urlParam = function (name, url) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)')
            .exec(url);
        if (results == null) {
            return 0;
        }
        return results[1] || 0;
    };

    $(document).on('click', '.pagination a', function (event) {
        var parent = $(this).offsetParent();
        if (parent.prop("tagName") === "TD") {
            event.preventDefault();
            var url = $(this).attr("href");
            var id = $.urlParam("id", url);
            show_information(id, url);
        }
    });

    function register_functions_in_edit() {
        $("[register-function='true']").each(function (index, element) {
            var el = $(element);
            var function_element = el.attr("execute-script");
            if (function_element) {
                window[function_element](el);
            }
        });
    }

    function delete_attachment(selector_link) {
        Swal.fire({
            title: "{{__("modeladminlang::default.delete")}}",
            text: "{{__("modeladminlang::default.delete_message")}}",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#08dd32',
            confirmButtonText: "{{__("modeladminlang::default.confirm_delete_button")}}",
            cancelButtonText: "{{__("modeladminlang::default.cancel_button")}}"
        }).then((result) => {
            if (result.value) {
                $(selector_link)[0].click();
            }
        })
    }

    function showMessage(title,text, type = "success") {
        Swal.fire({
            title: title,
            text: text,
            type: type
        })
    }

    function clear_inputs_function(formSelector) {
        $(formSelector)[0].reset();
        $('select').val(null).trigger('change');
    }

    function validateAndSubmit(url,formSelector, button, clear_inputs = true, reload_page = false){
        $(button).prop("disabled",true);
        const formData = new FormData($(formSelector)[0]);
        $.ajax({
            type: "POST",
            dataType: "json",
            data: formData,
            url: url,
            processData: false,
            contentType: false
        }).done(function (data) {
            if(data.success){
                $("#print_error_msg").hide();
                showMessage("{{__("modeladminlang::default.success")}}","{{__("modeladminlang::default.save_with_success")}}");
                if(clear_inputs){
                    clear_inputs_function(formSelector);
                }
            }else{
                const div_error = $("#print_error_msg");
                div_error.find("ul").html('');
                div_error.show();
                $.each(data.error,function (key, value) {
                    $("#print_error_msg").find("ul").append("<li>"+value+"</li>");
                })
            }
        }).fail(function (jqXHR, textStatus ) {
            showMessage("Erro","{{__("modeladminlang::default.error")}}! "+textStatus,"error");
        }).always(function () {
            $(button).prop("disabled",false);
            if(reload_page){
                location.reload();
            }
        });
    }
    function marcar_checkbox(checkbox) {
       let  checkado = $(checkbox).prop("checked");
        if(checkado){
            $(checkbox).removeAttr("checked");
        }else{
            $(checkbox).attr("checked",true);
        }
    }
</script>
