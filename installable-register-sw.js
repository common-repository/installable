if (location.protocol == "https:" && "serviceWorker" in navigator) {
window.addEventListener('load', function() {
    
var refreshing;

navigator.serviceWorker.addEventListener("controllerchange",
  function() {
    if (refreshing) return;
    refreshing = true;
    window.location.reload();
  }
);

});
}