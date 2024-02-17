## Exp_16 - functionRAG JS - Create a template for a conversational RAG web app

### Notes
- Has memory
- The app includes an OpenAi function call to query a vector database.
- The app decides whether or not to query the vector database. Therefore, it's a true "chat with your docs" experience.
- The operation is not robust because the model sometimes does not query the database when it should. Instead it says that the data is not available. Therefor basicRAG is a more robust solution.
