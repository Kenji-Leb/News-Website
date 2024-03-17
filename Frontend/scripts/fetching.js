const articleDiv = document.getElementById("articleDiv");

const getAllNews = () => {
  fetch("http://localhost/todo/api.php", {
    method: "GET",
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
        displayNews(data);
    })
    .catch((error) => {
      console.error(error);
    });
};


const displayNews = (data) => {
    articleDiv.innerHTML = "";
    data.news?.forEach((newsItem) =>{
        const newsDiv = document.createElement("div")
        const newsHeader = document.createElement("h2")
        const newstext = document.createElement("p")


        newsDiv.appendChild(newsHeader)
        newsDiv.appendChild(newstext)

        articleDiv.appendChild(newsDiv)
    })

}


getAllNews()