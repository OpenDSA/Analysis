# -*- coding: utf-8 -*-
"""StrugglingUpdated.ipynb

Automatically generated by Colaboratory.

Original file is located at
    https://colab.research.google.com/drive/1XCNRfule7AZJnOWm_SJkrT9IaBS-rmaj
"""

from google.colab import drive
drive.mount('/content/drive')
import sys
# modify "customized_path_to_homework", path of folder in drive, where you uploaded your homework
customized_path_to_homework = "/content/drive/My Drive/research data_actual"
sys.path.append(customized_path_to_homework)
import pandas as pd
import matplotlib.pyplot as plt
from sklearn.metrics import r2_score
from sklearn import linear_model
from scipy.stats import spearmanr
import numpy as np
from sklearn.linear_model import LinearRegression

from scipy.stats import linregress

origin = pd.read_excel("/content/drive/My Drive/research data_actual/Project4_Origin.xlsx")
piazza = pd.read_excel("/content/drive/My Drive/research data_actual/Piazza contribution.xlsx")

less_85 = origin[origin['TG'] < 80]
above_85 = origin[origin['TG'] >= 80]

filterAbove85 = above_85
filterLess85 = less_85
filterAbove85_temp = above_85[(above_85["Max Sub"] > 10) & (above_85["Correctness by thirdsubmission"] < 0.5) ]
filterLess85_temp = less_85
filtered_temp = pd.concat([filterAbove85_temp, filterLess85_temp])

filteredAbove85_Numvisit_4 = above_85[(above_85['NumVisit'] == 0) & (above_85['online'] == 0)]

filterAbove85_4 = above_85[(above_85['NumVisit'] > 0)& (above_85['online'] > 0) & (above_85["Max Sub"] > 9) & (above_85["Correctness by thirdsubmission"] <= 0.5)]
filterLess85_4 = less_85
filterNonVisit4 = filteredAbove85_Numvisit_4[(filteredAbove85_Numvisit_4["Max Sub"] > 9) & (filteredAbove85_Numvisit_4["Correctness by thirdsubmission"] <= 0.5) & (filteredAbove85_Numvisit_4["NumberOfDays"] > 3)]
filtered4 = pd.concat([filterAbove85_4, filterLess85_4, filterNonVisit4])

#correlation

# spearman correlation for struggling
coef,p = spearmanr(filtered_temp.NumVisit,filtered_temp.TG)
print('Spearmans correlation coefficient overall help-seeking activity vs grade: %.3f' % coef)
linregress(filtered_temp.NumVisit, filtered_temp.TG)

# spearman correlation for original
coef,p = spearmanr(origin.NumVisit,origin.TG)
print('Spearmans correlation coefficient overall help-seeking activity vs grade: %.3f' % coef)
linregress(origin.NumVisit, origin.TG)

# spearman correlation struggling
coef,p = spearmanr(filtered_temp.view,filtered_temp.TG)
print('Spearmans correlation coefficient overall help-seeking activity vs grade: %.3f' % coef)
linregress(filtered_temp.view, filtered_temp.TG)

# spearman correlation for original
coef,p = spearmanr(origin.view,origin.TG)
print('Spearmans correlation coefficient overall help-seeking activity vs grade: %.3f' % coef)
linregress(origin.view, origin.TG)

def GetDifferences(df1, df2):
  df = pd.concat([df1, df2]).reset_index(drop=True)
  idx = [diff[0] for diff in df.groupby(list(df.columns)).groups.values() if len(diff) == 1]
  return df.reindex(idx)

#Scatter plot
filtered4_remove = filtered_temp[(filtered_temp["NumVisit"] < 55) ]
Maybe = GetDifferences(origin, filtered_temp)

temp = GetDifferences(filtered_temp, filtered4)
temp

ox = filtered4_remove.NumVisit
oy = filtered4_remove.TG
plt.scatter(ox,oy,color='#003F72')
m, b = np.polyfit(ox, oy, 1)
plt.ylim(0,120)
plt.plot(ox, m*ox+b, color = 'red')
plt.xlabel("Number of visits to office hours")
plt.ylabel("Final Grade of Project 4")
rsquare = r2_score(oy,ox)
b

ox = Maybe.NumVisit
oy = Maybe.TG
plt.scatter(ox,oy,color='#003F72')
m, b = np.polyfit(ox, oy, 1)
plt.ylim(0,120)
plt.plot(ox, m*ox+b, color = 'red')
plt.xlabel("Number of visits to office hours")
plt.ylabel("Final Grade of Project 4")
rsquare = r2_score(oy,ox)
b

ox = filtered4_remove.view
oy = filtered4_remove.TG
plt.scatter(ox,oy,color='#003F72')
m, b = np.polyfit(ox, oy, 1)
plt.ylim(0,120)
plt.plot(ox, m*ox+b, color = 'red')
plt.xlabel("Number of viewing Project 4 related questions on Piazza")
plt.ylabel("Final Grade of Project 4")
rsquare = r2_score(oy,ox)
b

ox = Maybe.view
oy = Maybe.TG
plt.scatter(ox,oy,color='#003F72')
m, b = np.polyfit(ox, oy, 1)
plt.ylim(0,120)
plt.plot(ox, m*ox+b, color = 'red')
plt.xlabel("Number of viewing Project 4 related questions on Piazza")
plt.ylabel("Final Grade of Project 4")
rsquare = r2_score(oy,ox)
b

#linear regression
linregress(origin.NumVisit,origin.TG)

linregress(filtered_temp.NumVisit,filtered_temp.TG)

linregress(Maybe.NumVisit,Maybe.TG)

linregress(origin.view,origin.TG)

linregress(filtered_temp.view,filtered_temp.TG)

linregress(Maybe.view,Maybe.TG)

# spearman correlation for struggling
coef,p = spearmanr(Maybe.NumVisit,Maybe.TG)
print('Spearmans correlation coefficient overall help-seeking activity vs grade: %.3f' % coef)
linregress(Maybe.NumVisit, Maybe.TG)

# spearman correlation for struggling
coef,p = spearmanr(Maybe.view,Maybe.TG)
print('Spearmans correlation coefficient overall help-seeking activity vs grade: %.3f' % coef)
linregress(Maybe.view, Maybe.TG)
