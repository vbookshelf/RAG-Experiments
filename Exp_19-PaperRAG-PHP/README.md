## Exp_19 - paperRAG-PHP: Create a PHP RAG to search papers in the ArXiv dataset

### Objective
- Build a ully fuctiobnal RAG app in PHP.
- The app doesn't include generative output. It doesn't seem to add value in this context.
- KaggleRAG does include generative output.

### Notes
- Using cheaper OpenAi embeddings
- Kaggle:<br>
https://www.kaggle.com/code/vbookshelf/exp18-arxiv-prep-256k-wcsvectors-text-3-small
- Tried to upload 256 thousand vectors to the Weaviate database. Got errors, possibly because their are API restrictions when using the sanbox. About 146 thousand vectors were uploaded.
