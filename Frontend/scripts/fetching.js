const articleDiv = document.getElementById("articleDiv");

const getAllNews = () => {
  fetch("http://localhost/News Website/Backend/api.php?news_id=1", {
    method: "GET",
  })
    .then((response) => {
      console.log(response)
      return response.json();
    })
    .then((data) => {
      console.log(data)
        displayNews(data);
    })
    .catch((error) => {
      console.error(error);
    });
};


const displayNews = (data) => {
  articleDiv.innerHTML = "";
  data.news?.forEach((newsItem) =>{
      console.log(newsItem); // Log the newsItem object
      const newsDiv = document.createElement("div")

      const newsHeader = document.createElement("h1")
      newsHeader.textContent = newsItem.title

      const newstext = document.createElement("p")
      newstext.textContent = newsItem.text

      newsDiv.appendChild(newsHeader)
      newsDiv.appendChild(newstext)

      articleDiv.appendChild(newsDiv)
  })

}



getAllNews()