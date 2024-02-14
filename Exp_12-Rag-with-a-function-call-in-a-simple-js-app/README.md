## Exp_12 - Use a OpenAi function call inside a RAG js web app

### Objective
- Create a simple working RAG Javascript web app that uses a function call. The function call is used to query the Weviate vector database.

### Notes
- Everything works.
- Function calls give the model the ability to decide for itself when it needs data, from a database, to answer a question.
- Because the model decides when it needs to query the database, this allows for a true "chat with your docs" experience. For example, when the model is asked to summarize it's previous response it won't make a database query.
- This improves response speed when a user asks follow up questions.
- This web app has the simple chat layout. There's no left and right panel.

  ### Resources

  - ChatGPT Function Calling Experiments<br>
  https://github.com/vbookshelf/ChatGPT-Function-Calling-Experiments

