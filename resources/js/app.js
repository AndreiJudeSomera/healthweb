import "./bootstrap";
import Alpine from "alpinejs";
import $ from "jquery";

window.$ = window.jQuery = $;
window.Alpine = Alpine;
Alpine.start();

toastr.options = {
  closeButton: true,
  debug: true,
  newestOnTop: true,
  progressBar: true,
  positionClass: "toast-bottom-right",
  preventDuplicates: true,
  onclick: null,
  showDuration: "300",
  hideDuration: "1000",
  timeOut: "5000",
  extendedTimeOut: "1000",
  showEasing: "swing",
  hideEasing: "linear",
  showMethod: "fadeIn",
  hideMethod: "fadeOut",
};

// ✅ AUTO-IMPORT EVERYTHING
import.meta.glob('./components/**/*.js', { eager: true });