jQuery(document).ready(function ($) {
    $('#btn-directory-and-files-scan').on('click', function (event) {
        event.stopPropagation();

        jQuery.ajax({
            type: "POST",
            url: duplicator_admin_script_data.ajax_url,
            dataType: "json",
            data: {
                action: 'duplicator_scan_directories_and_files',
                nonce: duplicator_admin_script_data.duplicator_scan_directories_and_files_nonce,
            },
            success: function (result, textStatus, jqXHR) {
                console.log('success called');
                console.log(result);
                // if (result.success) {
                //     redirectToRemoteEndpoint(result.data.funcData);
                // } else {
                //     addErrorMessage(result.data.message);
                // }
            },
            error: function (result, textStatus, error) {
                console.log('error called');
                console.log(result);
                console.log(error);
            }
        });
    });
});
