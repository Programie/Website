import "bootstrap/js/src/carousel";
import "bootstrap/js/src/collapse";
import "bootstrap/js/src/dropdown";
import "../style/main.scss";

import Tooltip from "bootstrap/js/src/tooltip";

document.addEventListener("DOMContentLoaded", function() {
    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new Tooltip(tooltipTriggerEl);
    });
});