document.addEventListener('DOMContentLoaded', scrollup(), false);

function scrollup () {
  const element = document.getElementById("searchresults");

  element.scrollIntoView({inline: "start"});
}