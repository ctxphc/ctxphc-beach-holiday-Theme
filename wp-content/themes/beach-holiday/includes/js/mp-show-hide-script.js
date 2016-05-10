/**
 * Created by ken_kilgore1 on 1/11/2015.
 */

jQuery(document).ready(function ($) {
    $("#spouse_info").hide();
    $("#spouse_spacer").hide();
    $("#family_info").hide();
    $("#family_spacer").hide();
    $("input:radio[name$='memb_type']").click(function () {

        if ($("input[name$='memb_type']:checked").val() === 1) {
            $("#spouse_info").hide();
            $("#spouse_spacer").hide();
            $("#family_info").hide();
            $("#family_spacer").hide();
        } else if ($("input[name$='memb_type']:checked").val() === 2) {
            $("#spouse_info").hide();
            $("#spouse_spacer").hide();
            $("#family_info").show();
            $("#family_spacer").show();
        } else if ($("input[name$='memb_type']:checked").val() === 3) {
            $("#spouse_spacer").show();
            $("#spouse_info").show();
            $("#family_info").hide();
            $("#family_spacer").hide();
        } else if ($("input[name$='memb_type']:checked").val() === 4) {
            $("#spouse_info").show();
            $("#spouse_spacer").show();
            $("#family_info").show();
            $("#family_spacer").show();
        }
    });
});