import $ from "jquery";
class Note {
  constructor() {
    this.events();
  }

  events() {
    $("#my-notes").on("click", ".delete-note", this.deleteNote.bind(this));
    $("#my-notes").on("click", ".edit-note", this.editNote.bind(this));
    $("#my-notes").on("click", ".update-note", this.updateNote.bind(this));
    $(".submit-note").on("click", this.submitNote.bind(this));
  }

  async deleteNote(e) {
    const note = $(e.target).parents("li");

    try {
      const response = await fetch(
        `${universityData.url}/wp-json/wp/v2/note/${note.data("id")}`,
        {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
            "X-WP-Nonce": universityData.nonce,
          },
        }
      );
      if (response.ok) {
        const data = await response.json();
        if (Number(data.noteCount) <= 4) {
          $(".note-limit-message").removeClass("active");
        }
        note.slideUp();
      } else {
        console.log("failed");
      }
    } catch (error) {
      console.log(error);
    }
  }
  async updateNote(e) {
    const note = $(e.target).parents("li");

    const updatePost = {
      title: note.find(".note-title-field").val(),
      content: note.find(".note-body-field").val(),
    };

    try {
      const response = await fetch(
        `${universityData.url}/wp-json/wp/v2/note/${note.data("id")}`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-WP-Nonce": universityData.nonce,
          },
          body: JSON.stringify(updatePost),
        }
      );
      if (response.ok) {
        this.makeNoteReadOnly(note);
      } else {
        console.log("failed");
      }
    } catch (error) {
      console.log(error);
    }
  }
  async submitNote(e) {
    const newNote = {
      title: $(".new-note-title").val(),
      content: $(".new-note-body").val(),
      status: "private",
    };

    try {
      const response = await fetch(
        `${universityData.url}/wp-json/wp/v2/note/`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-WP-Nonce": universityData.nonce,
          },
          body: JSON.stringify(newNote),
        }
      );
      if (response.ok) {
        const createdPost = await response.json();
        $(".new-note-title, .new-note-body").val("");
        $(` <li data-id="${createdPost.id}">
            <input readonly class="note-title-field" type="text" value="${createdPost.title.raw}">
            <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
            <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
            <textarea readonly class="note-body-field" name=""
                id="">${createdPost.content.raw}</textarea>
            <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i>
                Save</span>
        </li>`)
          .prependTo("#my-notes")
          .hide()
          .slideDown();
      } else {
        console.log("failed");
      }
    } catch (error) {
      if (
        error == `SyntaxError: Unexpected token 's', "stop" is not valid JSON`
      ) {
        $(".note-limit-message").addClass("active");
      }
    }
  }

  editNote(e) {
    const note = $(e.target).parents("li");
    if (note.data("state") === "editable") {
      this.makeNoteReadOnly(note);
    } else {
      this.makeNoteEditable(note);
    }
  }
  makeNoteEditable(note) {
    note
      .find(".edit-note")
      .html('<i class="fa fa-times" aria-hidden="true"></i> Cencel');
    note
      .find(".note-title-field, .note-body-field")
      .removeAttr("readonly")
      .addClass("note-active-field");
    note.find(".update-note").addClass("update-note--visible");
    note.data("state", "editable");
  }

  makeNoteReadOnly(note) {
    note
      .find(".edit-note")
      .html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit');
    note
      .find(".note-title-field, .note-body-field")
      .attr("readonly", "readonly")
      .removeClass("note-active-field");
    note.find(".update-note").removeClass("update-note--visible");
    note.data("state", "readonly");
  }
}

export default Note;
