# Abstracting a raw data into a session data
Abstracing a raw interaction data can simply be done by running a "abstracting_script.py" file
Change the name of the interaction data file name at the end of the script file
One processed, file_name_merged_result.csv file will be created, which is going to be a session data

# Analyzing a session data
There are three scripts related to analyzing the session data abstracted from the above script.
1. behaviors.py
This file has all four behaviors analysis, however I recommend to use the analysis scripts below as they produce more accurate and faster results

2. cs4114_analysis.ipynb
For the best result and to use this scrip, I recommed upload it to the Google Colab notebook.
Both python version and Colab notebook versions are included in this repository, so use them at your choice

For Activity Sessions behavior of CS4114, use "behaviors.py" script
For PI Credit Seeking behavior of CS4114, use "cs4114_anlysis.ipynb"

3. cs5040_analysis.ipynb
For CS5040, use "cs5040_analysis.ipynb" for Activity Sessions behavior, Reading Prose behavior, and Credit Seeking behavior
