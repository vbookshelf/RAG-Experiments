## Exp_11 - Put the Weaviate code inside a function

### Objective
- Put the code used to query the Weviate database into an async function.

### Notes
- It must be an async function or else the OpenAi model won't have access to the the context when it creates the natural language response. This is beacuse the OpenAi code will run before the data is received from Weaviate.
