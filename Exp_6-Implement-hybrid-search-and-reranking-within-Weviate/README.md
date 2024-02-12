## Exp_6 Implement keyword search, hybrid search and reranking inside Weaviate

### Objective
- Test the Cohere API key to see that it works
- Implement keyword search using BM25
- Implement hybrid search inside Weaviate
- Implement reranking inside Wevaite

  
### Notes
- Using the vectors from the OHS Act data
- Reranking improves the search results

### Lessons Learned
- During a vector search the top result may not be the best result, because vectors are being compared. Reranking improves results because text strings are being compared. To get the most out of reranking ensure that the vector search outputs a large number of results (top_k setting), which can then be fed into reranking. Reranking will not be able to find the best reult if that result is not included in the original vector search results.
- All the RAG steps can be done without leaving Weviate.  
