function modalOpenButton($trigger) {

   console.log("Modal Trigger: " + $trigger);

   // Get all the triggers
   let openButtons = document.querySelectorAll($trigger);

   if (openButtons) {
      var openButtonsArray = Array.from(openButtons).entries();
      for (let [index, openButton] of openButtonsArray) {
         openButton.href="javascript:void(0)";
         openButton.classList.add('jsModalSearchTrigger');
         openButton.addEventListener("click", modalOpen);
      };
   }
}

function modalCloseButton() {
   let closeButton = document.getElementById('search__wrapper__close');
   closeButton.addEventListener("click", modalClose);
}

modalCloseButton();

function modalCloseOverlay() {
   let closeOverlay = document.getElementById('search_modal_overlay');
   closeOverlay.addEventListener("click", modalClose);
}
modalCloseOverlay();

function modalClose(event) {
   const html = document.documentElement;
   if (html !== null) {
      html.style.overflow = '';
      html.classList.remove('jsModalSearchOpen');
   }
   event.preventDefault();
}

function modalOpen(event) {
   const html = document.documentElement;
   if (html !== null) {
      html.style.overflow = 'hidden';
      html.classList.add('jsModalSearchOpen');
   }
   event.preventDefault();
}