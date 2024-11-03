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
                console.log(result.success);
                console.log(result.data.message);
                if (result.success) {
                    const jsonOutput = JSON.stringify(result.data.message, null, 2);
                    jQuery('#scan-log-area').html(`<pre>${jsonOutput}</pre>`);
                }
            },
            error: function (result, textStatus, error) {
                console.log('error called');
                console.log(result);
                console.log(error);
            }
        });
    });
});
