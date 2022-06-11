# Explanation about data.
Project4_Overall_Anonymous.xlsx is a summary data from 2021 Fall that has students' Project 4 submission activities(submission numbers, submission dates, submission correctness, submission score(Project4 Grade)) and help-seeking data (office hour visits and Piazza usage).
Zoom_anonymous.xlsx data is a raw collected Zoom data. This is a log data of each student visiting office hours. This is also summarized in the Project4_Overall_anonymous.xlsx file.
SurveyData.xlsx is a survey data that was taken in 2021 Spring anonymously. 
# Analyzing a session data

1. Data preprocess project4.ipynb\
This file is to preprocess the Survey data collected. It was used to find distribution on survey result. Copy those file into Google Colab notebook with SurveyData.xlsx file. You might need to change the path and file name for reading each file. 

2. dataAlgorithm.ipynb\
This is the original code that was used to do analysis on the Project4_Overall_Anonymous data. But at the end this file is not used for the analysis. I updated it to the next file. 

3. StrugglingUpdated.ipynb\
StrugglingUpdated file is finalized analysis code used. We modified the way to sort out struggling students so I had to change some methods. You just need to upload the strugglingupdated file in google colab notebook with Project4_Overall_Anonymous.xlsx file. you will need to change the path and file name to read the data files.
