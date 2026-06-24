(function ($) {
  "use strict";

  $(document).ready(function () {
    const $versionSelect = $('select[name="hostbox_recaptcha_version"]');
    const $v3Settings = $(".v3-setting");
    const $v2Settings = $(".v2-setting");

    // Handle version toggle
    $versionSelect.on("change", function () {
      const isV3 = $(this).val() === "v3";
      $v3Settings.toggle(isV3);
      $v2Settings.toggle(!isV3);
      console.log("Version toggled");
    });

    // Initialize visibility based on current selection
    $versionSelect.trigger("change");
  });
})(jQuery);
