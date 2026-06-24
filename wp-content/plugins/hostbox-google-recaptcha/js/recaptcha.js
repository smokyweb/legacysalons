class RecaptchaHandler {
  constructor() {
    // Initialize WooCommerce forms
    this.wcForms = {
      login: document.querySelector(".woocommerce-form-login, .login"),
      register: document.querySelector(".woocommerce-form-register, .register"),
      checkout: document.querySelector("form.checkout"),
      lostpassword: document.querySelector(".woocommerce-ResetPassword"),
    };

    // Get specific forms that need reCAPTCHA
    this.forms = [
      document.getElementById("commentform"), // Comment form
      document.getElementById("loginform"), // WP login form
      document.getElementById("registerform"), // WP register form
    ].filter(Boolean);

    // Initialize based on reCAPTCHA version
    this.init();

    // Handle CF7 forms for v3
    if (recaptchaVars.version === "v3") {
      this.initializeCF7Forms();
    }
  }

  async initializeCF7Forms() {
    const cf7Forms = document.querySelectorAll(".wpcf7-form");

    for (const form of cf7Forms) {
      try {
        const token = await this.executeRecaptcha("contact_form");
        this.updateTokenInput(form, token);
      } catch (error) {
        console.error("Error initializing CF7 form:", error);
      }
    }

    // Handle CF7 form submission
    document.addEventListener("wpcf7beforesubmit", async (e) => {
      try {
        const token = await this.executeRecaptcha("contact_form");
        this.updateTokenInput(e.target, token);
      } catch (error) {
        console.error("CF7 submission error:", error);
      }
    });
  }

  init() {
    if (recaptchaVars.version === "v3") {
      this.initV3();
    } else {
      this.initV2();
    }
  }

  initV2() {
    // Handle regular form submissions
    this.forms.forEach((form) => {
      form.addEventListener("submit", (e) => this.handleV2Submit(e));
    });

    // Handle WooCommerce checkout
    if (this.wcForms.checkout) {
      jQuery("form.checkout").on("checkout_place_order", () => this.validateV2Checkout());
    }

    // Reset captcha after CF7 submissions
    document.addEventListener("wpcf7submit", () => this.resetCaptcha());
  }

  initV3() {
    // Handle regular form submissions
    this.forms.forEach((form) => {
      form.addEventListener("submit", (e) => this.handleV3Submit(e));
    });

    // Handle WooCommerce checkout
    if (this.wcForms.checkout) {
      jQuery("form.checkout").on("checkout_place_order", () => this.validateV3Checkout());
    }

    // Initialize tokens and refresh every 90 seconds
    this.initializeV3Tokens();
    setInterval(() => this.initializeV3Tokens(), 90000);
  }

  handleV2Submit(e) {
    const form = e.target;
    if (form.classList.contains("checkout")) return;

    const recaptchaResponse = form.querySelector('[name="g-recaptcha-response"]')?.value;
    if (!recaptchaResponse) {
      e.preventDefault();
      alert("Please complete the reCAPTCHA verification.");
    }
  }

  async handleV3Submit(e) {
    const form = e.target;
    if (form.classList.contains("checkout") || form.classList.contains("wpcf7-form")) return;

    e.preventDefault();

    try {
      const action = this.getFormAction(form);
      const token = await this.executeRecaptcha(action);
      this.updateTokenInput(form, token);

      // Handle form submission
      if (typeof form.submit === "function") {
        form.submit();
      } else {
        const hiddenSubmit = document.createElement("input");
        hiddenSubmit.type = "submit";
        hiddenSubmit.style.display = "none";
        form.appendChild(hiddenSubmit);
        hiddenSubmit.click();
        form.removeChild(hiddenSubmit);
      }
    } catch (error) {
      console.error("reCAPTCHA error:", error);
    }
  }

  validateV2Checkout() {
    const response = this.wcForms.checkout?.querySelector('[name="g-recaptcha-response"]')?.value;
    if (!response) {
      alert("Please complete the reCAPTCHA verification.");
      return false;
    }
    return true;
  }

  async validateV3Checkout() {
    try {
      const token = await this.executeRecaptcha("wc_checkout");
      this.updateTokenInput(this.wcForms.checkout, token);
      return true;
    } catch (error) {
      console.error("reCAPTCHA error:", error);
      return false;
    }
  }

  getFormAction(form) {
    if (form === this.wcForms.login) return "wc_login";
    if (form === this.wcForms.register) return "wc_register";
    if (form === this.wcForms.checkout) return "wc_checkout";
    if (form === this.wcForms.lostpassword) return "wc_lostpassword";
    if (form.classList.contains("wpcf7-form")) return "contact_form";
    return form.getAttribute("data-recaptcha-action") || "submit";
  }

  async executeRecaptcha(action) {
    await this.ensureRecaptchaLoaded();
    return grecaptcha.execute(recaptchaVars.siteKey, { action });
  }

  ensureRecaptchaLoaded() {
    return new Promise((resolve) => {
      const checkRecaptcha = setInterval(() => {
        if (window.grecaptcha?.execute) {
          clearInterval(checkRecaptcha);
          resolve();
        }
      }, 100);
    });
  }

  updateTokenInput(form, token) {
    let tokenInput = form.querySelector('[name="recaptcha_response"]');
    if (!tokenInput) {
      tokenInput = document.createElement("input");
      tokenInput.type = "hidden";
      tokenInput.name = "recaptcha_response";
      form.appendChild(tokenInput);
    }
    tokenInput.value = token;
  }

  async initializeV3Tokens() {
    // Initialize regular forms
    for (const form of this.forms) {
      try {
        const action = this.getFormAction(form);
        const token = await this.executeRecaptcha(action);
        this.updateTokenInput(form, token);
      } catch (error) {
        console.error("Error setting initial token for form:", error);
      }
    }

    // Initialize WooCommerce forms
    for (const [formType, form] of Object.entries(this.wcForms)) {
      if (form) {
        try {
          const token = await this.executeRecaptcha(`wc_${formType}`);
          this.updateTokenInput(form, token);
        } catch (error) {
          console.error(`Error setting initial token for ${formType}:`, error);
        }
      }
    }
  }

  resetCaptcha() {
    if (typeof grecaptcha !== "undefined" && recaptchaVars.version === "v2") {
      grecaptcha.reset();
    }
  }
}

// Initialize the handler
let handler = null;

const initRecaptcha = () => {
  if (window.grecaptcha?.execute) {
    handler = new RecaptchaHandler();
  } else {
    setTimeout(initRecaptcha, 100);
  }
};

window.addEventListener("load", initRecaptcha);

// Handle WooCommerce checkout updates
if (typeof jQuery !== "undefined") {
  jQuery(document.body).on("updated_checkout", () => {
    if (recaptchaVars.version === "v3") {
      handler?.initializeV3Tokens();
    }
  });
}
