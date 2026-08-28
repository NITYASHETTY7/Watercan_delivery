function pushNotification(heading, text, icon) {
    $.toast({
        heading: heading,
        text: text,
        showHideTransition: 'slide',
        icon: icon,
        loaderBg: '#f96868',
        position: 'top-right'
    });
}

// Ajax CSRF initialization 
var ajaxMessage = ".ajax-message";
var ajaxContainer = "#ajax-container";
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function getData(method, route, dataType, data, callback = null, event = null, toast = 1) {
    NProgress.start();
    $.ajax({
        type: method,
        url: route,
        dataType: dataType,
        data: data,
        success: function (data, textStatus, jqXHR) {
            if (callback != null) {
                response_data = data;
                eval(callback + "(data)");
            } else {
                if (toast == 1) {
                    response_data = data;
                    pushNotification(data.message, data.title, 'success');
                }
            }
        },

        //If there was no response from the server
        error: function (data, textStatus, jqXHR) {
            console.log(data);
            let err = eval("(" + data.responseText + ")");
            if (data.status == 500 || data.status == 400)
                pushNotification("Oops", err.error, "error");
            else
                $.each(err.errors, function (index, value) {
                    pushNotification("Oops", value, "error");
                });
        },

        //capture the request before it was sent to server
        beforeSend: function (jqXHR, settings) {

        },

        //this is called after the response or error functions are finished
        //so that we can take some action
        complete: function (jqXHR, textStatus) {
            NProgress.done();
        }
    });
}

function ajaxButtonLoader(direction = 'in', initial_btn_label) {
    if ($(".ajax-btn").length > 0) {
        if (direction == 'in') {
            $(".ajax-btn").removeClass("btn-primary"); // Add the "btn-secondary" class (typo fixed)
            $(".ajax-btn").addClass("disabled"); // Add the "disabled" class
            $(".ajax-btn").addClass("btn-secondary"); // Add the "btn-secondary" class (typo fixed)
            $(".ajax-btn").css("cursor", "not-allowed"); // Disable pointer events (removed the semicolon)
            $(".ajax-btn").attr("type", "button"); // Remove the "submit" type
            $(".ajax-btn").html('<i class="fa fa-spinner fa-spin"></i>');
        } else {
            setTimeout(() => {
                $(".ajax-btn").removeClass("disabled"); // Add the "disabled" class
                $(".ajax-btn").removeClass("btn-secondary"); // Add the "btn-secondary" class (typo fixed)
                $(".ajax-btn").addClass("btn-primary"); // Add the "btn-secondary" class (typo fixed)
                $(".ajax-btn").css("cursor", "initial"); // Disable pointer events (removed the semicolon)
                $(".ajax-btn").attr("type", "submit"); // Add the "submit" type
                $(".ajax-btn").html(initial_btn_label);
            }, 500);
        }
    }
    return;
}

function postData(method, route, dataType, data, callback = null, event = null, toast = 1, async = true, redirectUrl = null, form = null) {
    let response_data;
    var initial_btn_label = $(".ajax-btn").html();

    var encryptedData = {};
    if(form != null){
        form.find('input[data-encrypt="true"]').each(function() {
            var inputValue = $(this).val();
            var inputName = $(this).attr('name');
            inputValue = btoa(inputValue);
            encryptedData[inputName] = 'zDecrypt-'+inputValue;
        });

        // Add encrypted data to FormData
        for (var key in encryptedData) {
            data.append(key, encryptedData[key]);
        }
    }
    console.log(data);
    $.ajax({
        contentType: false,
        processData: false,
        type: method,
        url: route,
        dataType: dataType,
        async: async,
        data: data != null ? data : {},
        headers: {
            "Accept": "application/json"
        },
        //if received a response from the server
        success: function (data, textStatus, jqXHR) {
            console.log(data);
            if (callback != null) {
                response_data = data;
                eval(callback + "(data)");
            }
            if (toast == 1) {
                response_data = data;
                pushNotification(data.message, data.title, data.status);
            }
            setTimeout(() => {
                if (typeof (response_data) != "undefined" && response_data !== null && response_data.status == "success") {
                    if(redirectUrl != 'not-reload'){
                        if (redirectUrl) {
                            window.location.href = redirectUrl;
                        } else {
                            window.location.reload();
                        }
                    }
                }
            }, 300);
        },

        //If there was no response from the server
        error: function (data, textStatus, jqXHR) {
            let err = eval("(" + data.responseText + ")");
            if (data.status == 500 || data.status == 400)
                pushNotification("Oops", err.error, "error");
            else
                $.each(err.errors, function (index, value) {
                    pushNotification("Oops", value, "error");
                });
        },

        //capture the request before it was sent to server
        beforeSend: function (jqXHR, settings) {
            // Disable Button & Start loading the response
            ajaxButtonLoader('in');
            NProgress.start();
        },

        //this is called after the response or error functions are finished
        //so that we can take some action
        complete: function (jqXHR, textStatus) {
            // Disable Button & Start loading the response
            ajaxButtonLoader('out', initial_btn_label);
            NProgress.done();
        }
    });
    return response_data;
}



// start editor initialization
// Array to store initialized CKEditor instances
let ckEditors = [];

$(window).on('load', function() {
    // Select all elements with the class 'init-ck-editor'
    const editorElements = document.querySelectorAll('.init-ck-editor');
    // Iterate each element and initialize CKEditor
    editorElements.forEach((element, index) => {
        // Create unique ID
        const toolbarId = `toolbar-container-${index}`;

        // Create a new div for the toolbar container
        const toolbarContainer = document.createElement('div');
        toolbarContainer.id = toolbarId;

        // Insert the new toolbar container just before the init-ck-editor element
        element.parentNode.insertBefore(toolbarContainer, element);

        // Retrieve the upload URL from the data-upload_url attribute
        const uploadUrl = element.getAttribute('data-upload_url');

        DecoupledEditor
            .create(element, {
                ckfinder: {
                    uploadUrl: uploadUrl,
                },
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                }
            })
            .then(newEditor => {
                // Append the toolbar of this editor instance to the newly created toolbar container
                const toolbarContainer = document.getElementById(toolbarId);
                toolbarContainer.appendChild(newEditor.ui.view.toolbar.element);

                // Store the editor instance
                ckEditors.push(newEditor);
            })
            .catch(error => {
                console.error(error);
            });
    });
});

// end editor initialization
