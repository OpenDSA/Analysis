import os
import pandas as pd
from datetime import datetime as dt, timedelta


def read_session_data(filename):
    os.chdir("D:/Sam/VT/2022 Spring/Thesis/data/" + filename)
    global data
    data = pd.read_csv(filename + "_merged_result_unannotated.csv")
    users = data['user ID'].unique()
    global df_dict
    df_dict = {elem: pd.DataFrame for elem in users}
    for user in df_dict.keys():
        df_dict[user] = data[:][data['user ID'] == user]
    # document event, Window open, Window close, window event, FF event, PE event, Other event

def read_raw_data(filename):
    os.chdir("D:/Sam/VT/2022 Spring/Thesis/data/" + filename)
    global data
    data = pd.read_csv(filename + "_sorted.csv")
    users = data['user_id'].unique()
    global df_dict
    df_dict = {elem: pd.DataFrame for elem in users}
    for user in df_dict.keys():
        df_dict[user] = data[:][data['user_id'] == user]
    # document event, Window open, Window close, window event, FF event, PE event, Other event


def getActivitySessionCount(filename):
    read_session_data(filename)
    merged_df = pd.DataFrame()
    seq1 = pd.Series(['document event', 'FF event'])
    seq2 = pd.Series(['document event', 'PE event'])
    seq3 = pd.Series(['Window event', 'FF event'])
    seq4 = pd.Series(['Window open', 'FF event'])
    seq5 = pd.Series(['Window event', 'PE event'])
    seq6 = pd.Series(['Window open', 'PE event'])
    match_count = 0
    for i in data.index - len(seq1):
        if data.iloc[i]['user ID'] == data.iloc[i + 1]['user ID']:
            test_seq = data['Event name'].iloc[i : i + len(seq1)].reset_index(drop=True)
            if len(test_seq) == len(seq1) and ( (test_seq == seq1).all() or (test_seq == seq2).all() or 
                                                (test_seq == seq3).all() or (test_seq == seq4).all() or 
                                                (test_seq == seq5).all() or (test_seq == seq6).all()):
                match_count += 1
        # End of one student
        else:
            print("Total matched event sequence count for student {}: {}".format(data.iloc[i - 1]['user ID'], match_count))
            merged_df = pd.concat([merged_df, pd.DataFrame([[data.iloc[i - 1]['user ID'], str(match_count)]])])
            match_count = 0
    merged_df.to_csv("activity_session_count.csv")

def getReadingCount(filename, start, end):
    read_session_data(filename)
    df = pd.DataFrame()
    match_count = 0
    for i in data.index - 1:
        if data.iloc[i]['user ID'] == data.iloc[i + 1]['user ID']:
            if not pd.isna(data.iloc[i]['Action Time']):
                action_time = data.iloc[i]['Action Time'][13:-3] if "Reading time" in data.iloc[i]['Action Time'] else 0
                if (start <= float(action_time) <= end): 
                    match_count += 1
        else:
            print("Total reading count for student {}: {}".format(data.iloc[i - 1]['user ID'], match_count))
            df = pd.concat([df, pd.DataFrame([[data.iloc[i - 1]['user ID'], str(match_count)]])])
            match_count = 0
    print(df)
    df.to_csv("reading_count.csv")

def creditSeeking(filename, threshold):
    read_session_data(filename)
    df = pd.DataFrame()
    match_count = 0

    openedModule = False
    openedTime = ""
    for i in data.index - 1:
            
        if data.iloc[i]['user ID'] == data.iloc[i + 1]['user ID']:
            if data.iloc[i]['Event name'] == "document event" or data.iloc[i]['Event name'] == "Window open" and openedModule == False:
                openedModule = True
                openedTime = data.iloc[i]['Start time']
            elif data.iloc[i]['Event name'] == "FF event" or data.iloc[i]['Event name'] == "PE event" and openedModule == True:
                now = dt.strptime(data.iloc[i]['Start time'], "%Y-%m-%d %H:%M:%S")
                opened = dt.strptime(openedTime, "%Y-%m-%d %H:%M:%S")
                time_diff = (now - opened).total_seconds()
                if time_diff <= threshold:
                    match_count += 1
                openedModule = False
                
        else:
            print("Total credit seeking count for student {}: {}".format(data.iloc[i - 1]['user ID'], match_count))
            df = pd.concat([df, pd.DataFrame([[data.iloc[i - 1]['user ID'], str(match_count)]])])
            match_count = 0
    print(df)
    df.to_csv("credit_seeking.csv")

def PIcreditSeeking(filename):
    read_raw_data(filename)
    df = pd.DataFrame()
    cs_count = 0

    for i in data.index - 1:
        if data.iloc[i]['user_id'] == data.iloc[i + 1]['user_id']:
            if data.iloc[i]['short_name'] and data.iloc[i]['short_name'] == data.iloc[i + 1]['short_name']:
                now = dt.strptime(data.iloc[i]['action_time'], "%Y-%m-%d %H:%M:%S")
                next = dt.strptime(data.iloc[i + 1]['action_time'], "%Y-%m-%d %H:%M:%S")
                time_diff = (now - next).total_seconds()
                if time_diff < 8:
                    cs_count += 1                
        else:
            print("Total credit seeking count for student {}: {}".format(data.iloc[i - 1]['user_id'], cs_count))
            # merged_df = pd.concat([merged_df, pd.DataFrame([["Total matched event sequence count for student {}:".format(data.iloc[i - 1]['user ID']), str(match_count)]])])
            df = pd.concat([df, pd.DataFrame([[data.iloc[i - 1]['user_id'], str(cs_count)]])])
            cs_count = 0
    print(df)
    df.to_csv("pi_credit_seeking.csv")

getActivitySessionCount("cs4114_fall_2020")
getReadingCount("cs4114_fall_2020", 15, 120)
creditSeeking("cs4114_fall_2020", 15)
PIcreditSeeking("cs4114_fall_2020")