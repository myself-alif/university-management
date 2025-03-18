import $, { post } from "jquery";
class Search {
  constructor() {
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.searchField = $("#search-term");
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.previousValue;
    this.typingTimer;
    this.resultsDiv = $("#search-overlay__results");
    this.events();
  }

  events() {
    this.openButton.on("click", this.openOverlay.bind(this));
    this.closeButton.on("click", this.closeOverlay.bind(this));
    $(document).on("keydown", this.keyPressDispatcher.bind(this));
    this.searchField.on("keyup", this.typingLogic.bind(this));
  }

  openOverlay() {
    this.searchField.val("");
    this.resultsDiv.html("");
    this.searchOverlay.addClass("search-overlay--active");
    this.isOverlayOpen = true;
    $("body").addClass("body-no-scroll");
    setTimeout(() => {
      this.searchField.focus();
    }, 301);
  }

  closeOverlay() {
    this.searchOverlay.removeClass("search-overlay--active");
    this.isOverlayOpen = false;
    $("body").removeClass("body-no-scroll");
  }

  keyPressDispatcher(e) {
    if (
      e.keyCode === 83 &&
      this.isOverlayOpen === false &&
      !$("input, textarea").is(":focus")
    )
      this.openOverlay();
    if (
      e.keyCode === 27 &&
      this.isOverlayOpen === true &&
      !$("input, textarea").is(":focus")
    )
      this.closeOverlay();
  }

  typingLogic(e) {
    if (this.searchField.val() !== this.previousValue) {
      clearTimeout(this.typingTimer);
      if (this.searchField.val()) {
        this.typingTimer = setTimeout(this.getResults.bind(this), 500);
        if (this.isSpinnerVisible === false) {
          this.resultsDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }
      } else {
        this.resultsDiv.html("");
        this.isSpinnerVisible = false;
      }
    }
    this.previousValue = this.searchField.val();
  }

  async getResults() {
    let response = await fetch(
      `${
        universityData.url
      }/wp-json/university/v1/search?term=${this.searchField.val()}`
    );
    let data = await response.json();

    this.resultsDiv.html(`<div class="row">
      <div class="one-third">
      <h2 class="search-overlay__section-title">General information</h2>
        ${
          data.general_info.length
            ? '<ul class="link-list min-list">'
            : "<p>No information found</p>"
        }
    ${data.general_info
      .map(
        (post) =>
          `<li><a href="${post.url}">${post.title}<a/>${
            post.type === "post" ? ` by ${post.authorName}` : ""
          }</li>`
      )
      .join("")}
    ${data.general_info.length ? "</ul>" : ""}
          <h2 class="search-overlay__section-title">Events</h2>
        ${data.events.length ? "" : "<p>No information found</p>"}
    ${data.events
      .map(
        (post) => `<div class="event-summary">
    <a class="event-summary__date t-center" href="${post.url}">
        <span class="event-summary__month">${post.month}</span>
        <span class="event-summary__day">${post.day}</span>
    </a>
    <div class="event-summary__content">
        <h5 class="event-summary__title headline headline--tiny"><a
                href="${post.url}">${post.title}</a></h5>
        <p>${post.excerpt}<a href="${post.url}" class="nu gray">Learn
                more</a></p>
    </div>
</div>`
      )
      .join("")}
      </div>
      <div class="one-third">
       <h2 class="search-overlay__section-title">Programs</h2>
        ${
          data.programs.length
            ? '<ul class="link-list min-list">'
            : "<p>No information found</p>"
        }
    ${data.programs
      .map((post) => `<li><a href="${post.url}">${post.title}<a/></li>`)
      .join("")}
    ${data.programs.length ? "</ul>" : ""}
 
   
      </div>
      <div class="one-third">
       <h2 class="search-overlay__section-title">Professors</h2>
        ${
          data.professors.length
            ? '<ul class="professor-cards">'
            : "<p>No information found</p>"
        }
    ${data.professors
      .map(
        (
          post
        ) => `<li class="professor-card__list-item"><a class="professor-card" href="${post.url}">
                <img class="professor-card__image" src="${post.image}">
                <span class="professor-card__name">${post.title}</span>
            </a></li>`
      )
      .join("")}
    ${data.professors.length ? "</ul>" : ""}
      </div>
      </div>`);
    this.isSpinnerVisible = false;
  }
}
export default Search;
