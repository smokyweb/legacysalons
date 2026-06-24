import { SlotFillProvider, Popover } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

/**
 *
 * Modal handler class
 *
 */
export default function ModalProvider($, settings) {
  const { render } = wp.element;

  /**
   *
   * Modal wrapper
   *
   */
  const ModalProvider = (props) => {
    return (
      <SlotFillProvider>
        {props.modal}
        <Popover.Slot />
      </SlotFillProvider>
    );
  };

  return class DashboardModals {
    area = null;

    constructor() {
      // Wait for DOM content to be loaded
      if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", () => this.initialize());
      } else {
        this.initialize();
      }
    }

    initialize() {
      const modalArea = document.getElementById("wp-modal-area");
      if (!modalArea) {
        console.warn("Modal area element not found");
        return;
      }
      this.area = modalArea;
    }

    openModal(modal) {
      if (!this.area) {
        // If area isn't ready yet, wait and try again
        setTimeout(() => this.openModal(modal), 100);
        return;
      }
      render(<ModalProvider modal={modal}></ModalProvider>, this.area);
    }

    closeModal(modal) {
      if (!this.area) {
        return;
      }
      render(<></>, this.area);

      if (modal) {
        $.post(ajaxurl, {
          action: settings.actionModalDismiss,
          id: modal,
        });
      }
    }
  };
}
