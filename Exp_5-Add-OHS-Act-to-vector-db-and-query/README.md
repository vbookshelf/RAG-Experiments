## Exp_5 - Add OHS Act to Weaviate vector database and run queries

### Objective
- Manually convert the South African Occupational Health and Safety Act into chunks
- Add the chunks to the Weviate vector database
- Run a similarity search on the database
- Use OpenAi gpt-3.5 to convert search results (context) into a natural language answer

  
### Notes
- The chunks are in the ohs-act.txt file.
- The chunks are separated by the '#' symbol which I added manually.
- The original act is also included in this repo.
- Two Jupyter notebooks are included in this repo.

### Lessons Learned
- This code uses nearest neigbors search. But I think a combination of nearest neigbors and keyword search would be more effective when searching legal Acts. This is because certain legal definitions and keywords are well known and commonly used. People will use these well known keywords when searching. The same will apply when searching tecnical specifications. I found that nearest neighbors search alone is not effective enough to find these keywords.
- I don't think that Weaviate uses ChatGPT by default when creating a generative response internally. When I manually took the context and sent it to OpenAi gpt-3.5 I got a much better generative response.
- This small experiment showed that there's definitely value in applying RAG to legal Acts.
- Another way to improve results would be to add re-ranking by using Cohere with Weaviate.
