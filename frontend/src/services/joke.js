const baseURL = process.env.REACT_APP_JOKE_API_ENDPOINT;

async function getData(url = '') {
  const response = await fetch(url, {
    body: JSON.stringify()
  }).catch((error) => console.log(error.message));
  return response.json();
}

export function getCategories() {
  return getData(baseURL + 'category')
    .then(data => {
      let categoryOptions = [];
      data.forEach((category) => {
        categoryOptions.push({
          text: category.charAt(0).toUpperCase() + category.slice(1),
          value: category
        });
      });
      return categoryOptions;
    });
}

export function getRandomly() {
  return getData(baseURL + 'random')
    .then(data => { return {key: data.id, value: data.value} });
}

export function getByWord(word) {
  return getData(baseURL + 'word/' + word)
    .then(data => {
      let jokes = [];
      data.forEach((joke) => {
        jokes.push({
          key: joke.id,
          value: joke.value
        });
      });
      return jokes;
    });
}

export function getByCategory(category) {
  return getData(baseURL + 'category/' + category)
    .then(data => {
      let jokes = [];
      data.forEach((joke) => {
        jokes.push({
          key: joke.id,
          value: joke.value
        });
      });
      return jokes;
    });
}
