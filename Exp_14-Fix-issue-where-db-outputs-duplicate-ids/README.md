## Exp_14 - Fix issue where the database outputs duplicate text

### Objective
- The vector database outputs duplcate text after reranking. Remove the duplicate text before sending it to the LLM.

### Notes
- The number of text items after reanking is now less than 3. There must be some kind of threshold in place inside Weviate.
