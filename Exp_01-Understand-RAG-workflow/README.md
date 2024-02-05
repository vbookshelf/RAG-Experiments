## Exp_01 - Understand the RAG workflow

### Objective
- Create a RAG workflow in a Jupyter notebook on Kaggle
  https://www.kaggle.com/code/vbookshelf/exp3-learn-faiss-rag-system-with-singapore-data
- Use Sentence Transformers to create the vectors
- Use FAISS for the vector search
- Use OpenAi to create a natural language output
  
### Notes
- RAG means Retrieval Augmented Generation. Also known as "Chat with your docs".
- RAG Workflow: Ask a question, use vector search to find text blocks
  in a document that contain the answer to the question, put those text blocks into a LLM prompt and ask the LLM to create a natural language anwer using the text blocks provided.
- Vector search libraries (Python packages) are different from vector databases.
- FAISS, Annoy and ScaNN are python packages. These run locally and are free.
- Weaviate and Pinecone are two vector databases. They are not free.
- Sentence Transformers is a free python package that can be used to convert text into vectors.
- OpenAi Embeddings can also be used to convert text into vectors.
- Cohere is a paid solution (like OpenAi) that can also be used to convert text into vectors.

### Resources

Vector Database Explained | What is Vector Database?<br>
Code Basics<br>
https://www.youtube.com/watch?v=72XgD322wZ8

What is a Vector Database?<br>
Blog Post referred on code basics video<br>
https://www.pinecone.io/learn/vector-database/

Faiss - Introduction to Similarity Search<br>
(Very good explanation)<br>
https://www.youtube.com/watch?v=sKyvsdEv6rk

Sentence transformers docs<br>
https://www.sbert.net/

Docs - Using Sentence Transformers with Rerank<br>
Includes Colab notebook<br>
https://www.sbert.net/examples/applications/retrieve_rerank/README.html
Colab Notebook:<br>
https://colab.research.google.com/github/UKPLab/sentence-transformers/blob/master/examples/applications/retrieve_rerank/retrieve_rerank_simple_wikipedia.ipynb#scrollTo=D_hDi8KzNgMM

Deeplearning.ai Short Course<br>
Large Language Models with Semantic Search<br>
(Weaviate and Cohere)<br>
https://www.deeplearning.ai/short-courses/large-language-models-semantic-search/

Deeplearning.ai Short Course<br>
Vector Databases: from Embeddings to Applications<br>
(Weaviate)<br>
https://www.deeplearning.ai/short-courses/vector-databases-embeddings-applications/

Deeplearning.ai Short Course<br>
Building Applications with Vector Databases<br>
(Sentence Transformers and Pinecone)<br>
https://www.deeplearning.ai/short-courses/building-applications-vector-databases/<br>
