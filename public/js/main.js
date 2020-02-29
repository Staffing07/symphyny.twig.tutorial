// alert(1); to test

const articles = document.getElementById("articles");

if (articles) {
  articles.addEventListener("click", e => {
    // this will target the exact class
    if (e.target.className === "btn btn-danger delete-article") {
      //alert(2); testing script works on delete button click
      if (confirm("Are you sure?")) {
        const id = e.target.getAttribute("data-id");

        // alert(id); test that the delete is showing the correct id depending on which button is clicked

        fetch(`/article/delete/${id}`, {
          method: "DELETE"
        }).then(res => window.location.reload());
      }

      //targets data-attribute
    }
  });
}
