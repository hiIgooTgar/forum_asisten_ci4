$(document).ready(function () {
    $(document).on("click", ".toggle-password", function () {
        const targetInput = $($(this).data("target"));
        const icon = $(this).find("i");

        if (targetInput.attr("type") === "password") {
            targetInput.attr("type", "text");
            icon.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            targetInput.attr("type", "password");
            icon.removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });
});
