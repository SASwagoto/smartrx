function handleFormSubmit(
    formSelector,
    modalSelector,
    tableSelctor,
    isUpdate = false,
    onSuccessCallback = null,
){
    $(document).on("submit", formSelector, function (e) {
        console.log("Form submitted:", formSelector); // Debug log
        e.preventDefault();
        const form = $(this);
        let formData = new FormData(form[0]);
        let submitButton = form.find('button[type="submit"]');

        if (isUpdate) formData.append('_method', 'PATCH');

        form.find('input[type="checkbox"]').each(function () {
            let name = this.name;
            if(name){
                if(name.includes('[]')){
                    return; // Skip checkboxes with array names
                }else{
                    formData.append(name, this.checked ? 1 : 0);
                }
            }
        });

        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                submitButton
                    .prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
                form.find('.invalid-feedback').remove();
                form.find('.is-invalid').removeClass('is-invalid');
            },
            success: function (response) {
                if (response.status === false) {
                    showFloatingAlert("error", response.message);
                } else {
                    $(modalSelector).modal("hide");
                    showFloatingAlert("success", response.message);
                    $(tableSelctor).DataTable().ajax.reload(null, false);
                    form[0].reset();
                    if (typeof onSuccessCallback === "function") {
                        onSuccessCallback(form, response);
                    }
                    let defaultImage = form
                        .find(".image-preview-class")
                        .data("default");
                    form.find(".image-preview-class").attr("src", defaultImage);
                }
            },
            error: function (xhr) {
                if (xhr.status === 402 || xhr.status === 403) {
                    let response = xhr.responseJSON;

                    // টোস্টার বা অ্যালার্ট দেখানো
                    if (typeof showFloatingAlert === "function") {
                        showFloatingAlert(
                            "error",
                            response.error || response.message,
                        );
                    } else {
                        alert(response.error || response.message);
                    }

                    // যদি রিডাইরেক্ট URL থাকে, তবে ২ সেকেন্ড পর রিডাইরেক্ট হবে
                    if (response.redirect) {
                        setTimeout(function () {
                            window.location.href = response.redirect;
                        }, 2000);
                    }
                    return; // ভ্যালিডেশন চেকে যাবে না
                }
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
                        let input = form.find('[name="' + key + '"]');
                        input.addClass("is-invalid");
                        input.after(
                            '<div class="invalid-feedback">' + value[0] + "</div>",
                        );
                    });
                }
            },
            complete: function () {
                submitButton
                    .prop("disabled", false)
                    .html(
                        isUpdate
                            ? 'Update User'
                            : 'Create User',
                    );
            },
        })
    });
}

$(document).on("click", ".delete-btn", function (e) {
    e.preventDefault();

    let url = $(this).data("url"); // Delete URL
    let itemName = $(this).data("item") || "Item";
    let tableId = $(this).data("table-id") || ".datatable";
    let name = $(this).data("name") || "Item"; // Item name (e.g., Currency)
    let deleteBtn = $("#deleteConfirm"); // Modal confirm button

    // Show modal

    $("#deleteConfirmModal .modal-body").html(
        `Are you sure you want to delete <b>${itemName}</b> ?`,
    );

    $("#deleteConfirmModal").modal("show");

    // Button click event
    deleteBtn.off("click").on("click", function () {
        $.ajax({
            url: url,
            type: "DELETE",
            beforeSend: function () {
                deleteBtn
                    .prop("disabled", true)
                    .text("Deleting...");
            },
            success: function (response) {
                //console.log("Delete Response:", response); // Debug log
                if (response.status === false) {
                    showFloatingAlert("error", response.message);
                } else {
                    $("#deleteConfirmModal").modal("hide");
                    showFloatingAlert(
                        "success",
                        response.message || `${name} deleted successfully!`,
                    );
                    $(tableId).DataTable().ajax.reload(null, false);
                } // Current page static, reload data
            },
            error: function (xhr) {
                // Log the full object in your browser console to see exactly what Laravel sent
                //console.error("Server Error Object:", xhr);

                let errorMessage = `Unable to delete ${name}.`;

                // Extract message from Laravel standard JSON format
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    // Fallback if Laravel crashes into an unexpected raw HTML template
                    errorMessage =
                        "Database constraint violation occurred on server.";
                }

                showFloatingAlert("error", errorMessage);
            },
            complete: function () {
                deleteBtn
                    .prop("disabled", false)
                    .text("Delete");
            },
        });
    });
});