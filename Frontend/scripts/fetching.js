const todoForm = document.getElementById("todo-form");
const todoInput = document.getElementById("todo-input");
const todosDiv = document.getElementById("todos");

const getAllTodos = () => {
  fetch("http://localhost/todo/api.php", {
    method: "GET",
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      displayTodos(data);
    })
    .catch((error) => {
      console.error(error);
    });
};

const displayTodos = (data) => {
  todosDiv.innerHTML = "";
  data.todos?.forEach((todoItem) => {
    const todoDiv = document.createElement("div");

    const doneButton = document.createElement("button");
    doneButton.textContent = "Done";
    doneButton.addEventListener("click", () => toggleTodo(todoItem.id));

    const deleteButton = document.createElement("button");
    deleteButton.textContent = "Delete";
    deleteButton.addEventListener("click", () => deleteTodo(todoItem.id));

    const todoText = document.createElement("p");
    todoText.textContent = todoItem.text + " Done: " + todoItem.done;

    todoDiv.appendChild(todoText);
    todoDiv.appendChild(deleteButton);
    todoDiv.appendChild(doneButton);

    todosDiv.appendChild(todoDiv);
  });
};

const toggleTodo = async (id) => {
  try {
    const response = await fetch(`http://localhost/todo/api.php?id=${id}`, {
      method: "PUT",
    });
    const data = await response.json();
    if (data.status === "Success") {
      getAllTodos();
    } else {
      console.log("failed");
    }
  } catch (e) {
    console.error(error);
  }
};
const deleteTodo = (id) => {
  axios.delete(`http://localhost/todo/api.php?id=${id}`).then((response) => {
    if (response.data.status === "Success") {
      getAllTodos();
    } else {
      console.log("Failed");
    }
  });
};

const addTodo = async () => {
  try {
    const formData = new FormData();
    formData.append("text", todoInput.value);
    const response = await axios.post(
      `http://localhost/todo/api.php`,
      formData
    );
    todoInput.value = "";
    getAllTodos();
  } catch (e) {
    console.error(e);
  }
};

todoForm.addEventListener("submit", (e) => {
  e.preventDefault();
  addTodo();
});

getAllTodos();
