import $ from "jquery";

class Like {
  constructor() {
    this.events();
  }

  events() {
    $(".like-box").on("click", this.clickDispatcher.bind(this));
  }

  clickDispatcher(e) {
    const likeBox = $(e.target).closest(".like-box");
    if (likeBox.attr("data-exists") === "yes") this.deleteLike(likeBox);
    if (likeBox.attr("data-exists") === "no") this.createLike(likeBox);
  }
  async createLike(likeBox) {
    const content = {
      id: likeBox.data("professor"),
    };

    try {
      const response = await fetch(
        `${universityData.url}/wp-json/university/v1/like`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-WP-Nonce": universityData.nonce,
          },
          body: JSON.stringify(content),
        }
      );
      const data = await response.json();
      if (data != "please login first") {
        likeBox.attr("data-exists", "yes");
        let likeCount = Number(likeBox.find(".like-count").html());
        likeCount++;
        likeBox.find(".like-count").html(likeCount);
        likeBox.attr("data-like", data);
      }
    } catch (error) {
      console.log(error);
    }
  }
  async deleteLike(likeBox) {
    const content = {
      id: likeBox.data("like"),
    };
    try {
      const response = await fetch(
        `${universityData.url}/wp-json/university/v1/like`,
        {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
            "X-WP-Nonce": universityData.nonce,
          },
          body: JSON.stringify(content),
        }
      );

      const data = await response.json();
      console.log(data);

      if (response.ok) {
        likeBox.attr("data-exists", "no");
        let likeCount = Number(likeBox.find(".like-count").html());
        likeCount--;
        likeBox.find(".like-count").html(likeCount);
        likeBox.attr("data-like", "");
      }
    } catch (error) {
      console.log(error);
    }
  }
}
export default Like;
