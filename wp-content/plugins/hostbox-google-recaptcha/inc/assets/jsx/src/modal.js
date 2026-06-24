import { useState } from "react";
import { __ } from "@wordpress/i18n";
import { Button, BaseControl, Guide } from "@wordpress/components";
import ModalProvider from "./modal-provider.js";

(function ($, settings) {
  /**
   *
   * Onboarding modal
   *
   */
  const OnboardingModal = (props) => {
    const imageURL = `${settings.pluginURL}inc/assets/img/welcome.svg`;
    const [email, setEmail] = useState("");

    const onEmailChange = (e) => {
      setEmail(e.target.value);
    };

    const onRequestClose = () => {
      $.post(ajaxurl, {
        action: settings.onboardingModalAction,
        _wpnonce: settings.onboardingModalNonce,
        email: email,
      });

      return props.onRequestClose();
    };

    return (
      <Guide
        onFinish={onRequestClose}
        className="wp-modal wp-modal--onboarding"
        pages={[
          {
            image: (
              <picture>
                <img src={imageURL} />
              </picture>
            ),
            content: (
              <form onSubmit={onRequestClose}>
                <div className="wp-modal__header">
                  <h1>{__("One last thing", "hostbox-google-recaptcha")}</h1>
                  <p>
                    {__(
                      "We'll occasionally send you emails about plugin updates. And don't sweat it, you can unsubscribe anytime.",
                      "hostbox-google-recaptcha",
                    )}
                  </p>
                </div>
                <div className="wp-modal__body">
                  <label>{__("Email address", "hostbox-google-recaptcha")}</label>
                  <input type="email" value={email} onChange={onEmailChange} autoFocus />
                </div>
                <div className="wp-modal__footer">
                  <BaseControl>
                    <Button
                      isPrimary={true}
                      onClick={onRequestClose}
                      text={__("Continue", "hostbox-google-recaptcha")}
                    ></Button>
                  </BaseControl>
                </div>
              </form>
            ),
          },
        ]}
      />
    );
  };

  /**
   *
   * Upgrade modal
   *
   */
  const UpgradeModal = (props) => {
    const imageURL = `${settings.pluginURL}inc/assets/img/welcome.svg`;

    return (
      <Guide
        onFinish={props.onRequestClose}
        className="wp-modal wp-modal--upgrade"
        pages={[
          {
            image: (
              <picture>
                <img src={imageURL} />
              </picture>
            ),
            content: (
              <>
                <div className="wp-modal__header">
                  <h1>
                    {__("You'll need to upgrade to get access to this", "hostbox-google-recaptcha")}
                  </h1>
                </div>
                <div className="wp-modal__body">
                  <p>
                    {__(
                      "You're just a mouse click and a few key taps away from building better modals.",
                      "hostbox-google-recaptcha",
                    )}
                  </p>
                  <ul>
                    <li>{__("Advanced features and integrations", "hostbox-google-recaptcha")}</li>
                    <li>{__("Help from our friendly support team", "hostbox-google-recaptcha")}</li>
                    <li>{__("New updates every second week", "hostbox-google-recaptcha")}</li>
                  </ul>
                  <p>{__("Ready to build better modals?", "hostbox-google-recaptcha")}</p>
                </div>
                <div className="wp-modal__footer">
                  <BaseControl>
                    <Button
                      isPrimary={true}
                      href="https://www.riangle.com"
                      target="_blank"
                      text={__("Learn More Now", "hostbox-google-recaptcha")}
                    ></Button>
                    <Button
                      isSecondary={true}
                      onClick={props.onRequestClose}
                      text={__("Nope, Maybe Later", "hostbox-google-recaptcha")}
                    ></Button>
                  </BaseControl>
                </div>
              </>
            ),
          },
        ]}
      />
    );
  };

  const DashboardModalsBaseClass = ModalProvider($, settings);

  class DashboardModalsClass extends DashboardModalsBaseClass {
    constructor() {
      super();
      // Check if we should open the modal on initialization
      if (settings.isJustActivated) {
        this.openOnboardingModal();
      }
    }

    openOnboardingModal() {
      const modal = (
        <OnboardingModal
          onRequestClose={this.closeModal.bind(this, "onboarding")}
          status={settings.trackingStatus}
        />
      );
      this.openModal(modal);
    }

    openUpgradeModal() {
      const modal = <UpgradeModal onRequestClose={this.closeModal.bind(this, "upgrade")} />;
      this.openModal(modal);
    }
  }

  var hostboxModal = window.hostboxModal || {};
  window.hostboxModal = hostboxModal;
  hostboxModal.modals = new DashboardModalsClass();
})(jQuery, _hostboxModalSettings);
