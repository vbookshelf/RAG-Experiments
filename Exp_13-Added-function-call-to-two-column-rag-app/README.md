## Exp_13 - Added the OpenAi function call to the two-column RAG js web app

### Objective
- Add the OpenAi function call to the two-column js web app so that the reference text, that the model uses to answer a question,  now appears in the right column.
  
### Notes
- Everything works.
- Bad: When asked some questions the model may refer to existing info that's in the message history and conclude that it can't answer the user's question. It does this instead of making a database call. Example question: What is listed work?
- Good: If, for example, the model has the definitions section in memory, and the user asks for a definition, the model won't make a new database call but it will answer based on the info it has in memory.

### Resources

  - ChatGPT Function Calling Experiments<br>
  https://github.com/vbookshelf/ChatGPT-Function-Calling-Experiments

