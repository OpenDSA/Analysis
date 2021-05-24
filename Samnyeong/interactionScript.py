import os
import pandas as pd
import csv
import re
from datetime import datetime as dt, timedelta
from collections import defaultdict

csvdata = list()

# Reads a raw interaction data file
def readfile(file_name):
    os.chdir("./data/" +  file_name)
    print("Reading " + file_name + " data")
    # cs2114_fall_2020_sorted
    with open(file_name + '_sorted.csv', newline='') as f:
        reader = csv.reader(f)
        global csvdata
        csvdata = list(reader)

# convert time in a specific format to display
def GetTime(seconds):
    sec = timedelta(seconds=int(seconds))
    d = dt(1,1,1) + sec
    return "%d days %d hours %d minutes %d seconds" % (d.day-1, d.hour, d.minute, d.second)
    # print("DAYS:HOURS:MIN:SEC")
    # print("%d:%d:%d:%d" % (d.day-1, d.hour, d.minute, d.second))

# Helper function to write a description for events
def writeDesc(row):
    # exercise_type
    if row[10] == "pe":
        return "Attempted to solve PE"
    elif not row[7]:
        return row[4]
    else:
        # ev_name
        if row[9]:
            return f'Attempted to solve {row[9]} frame '
        else:
            return f'Attempted to solve {row[3]} exercise'

# Helper function to write a time for events
def writeTime(row, start, end):
    if row[4] == "PE" or row[10] == "pe" or check_pe_helper(row[3]):
        return f'In PE for {(end - start).total_seconds()} seconds' if (end - start).total_seconds() > 0 else None
    elif "document" not in row[3]:
        # ev_name
        if row[9]:
            return f'In slideshow for {(end - start).total_seconds()} seconds' if (end - start).total_seconds() > 0 else None
        else:
            return f'In exercise for {(end - start).total_seconds()} seconds' if (end - start).total_seconds() > 0 else None

# Check whether the event is associated with PE
def check_pe_helper(command):
    pe_command = ['jsav-matrix-click', 'jsav-exercise-grade', 'jsav-exercise-reset', 'jsav-node-click', 
                    'button-identifybutton', 'button-editbutton', 'button-addrowbutton', 'button-deletebutton', 'button-setterminalbutton', 'button-addchildbutton',
                    'button-checkbutton', 'button-autobutton', 'button-donebutton',
                    'submit-helpbutton', 'submit-edgeButton', 'submit-deleteButton', 'submit-undoButton', 'submit-redoButton', 'submit-editButton', 'submit-nodeButton',
                    'submit-begin', 'submit-finish','hyperlink']
    if command in pe_command:
        return True
    else:
        return False

# Check whether this and next events are the same type of event (PE)
def bundle_pe(curr, next):
    if curr[10] or curr[4] == 'PE': # curr has value
        if next[10]: # both curr and next has values
            if curr[10] != 'pe' and next[10] != 'pe':
                return False
        else: #if only curr has value
            if not check_pe_helper(next[3]) and next[4] != 'PE':
                return False 
    else: # curr doesn't have value
        if check_pe_helper(curr[3]):
            if next[10] == 'pe' or check_pe_helper(next[3]):
                return True
            else:
                return False
        else:
            return False
    return True
 
# Check whether this and next events are the same type of event (FF)
def bundle_ff(curr, next):
    if curr[9] and curr[9] == next[9]:
        return True
    else:
        return False


def abstract(file_name):
    readfile(file_name)
    print("Reading complete")

    # users = dict()
    # sessions_dict = dict()
    # event_dict = dict()

    session_count = 0 
    # inactive_time = 0

    columns = ["user ID", "Inst Book", "Event name", "Event Description", "Start time", "End Time", "Action Time", "Exercise Type", "Number of events"]
    start_time = 0
    num_event = 1
    is_pe_exercise = False
    with open(file_name + '_merged_result.csv', 'w', newline="") as csv_file:
        writer = csv.writer(csv_file)
        writer.writerow(columns)
        writer.writerow([f'Session {session_count + 1}'])
        session_count += 1

        for i in range(1, len(csvdata) - 1):
            threshold = 3600

        # Example of sample raw interaction data
        # id	    user_id	    inst_book_id	name	        description	            action_time	        inst_chapter_module_id	inst_section_id	    inst_exercise_id	short_name	    ex_type
        # 0         1           2               3               4                       5                   6                       7                   8                   9               10          
        # 22961032	8387	    722	            document-ready	"User loaded module"	2020-01-21 18:43	70590				
        # 23119732	3013	    722	            jsav-node-click	{"ev_num":43}	        2020-01-29 2:41	    70620	                109676	            1095	            sheet1exercise3	pe

            user_id = csvdata[i][1]
            book_id = csvdata[i][2]
            ev_name = csvdata[i][3]
            ev_desc = csvdata[i][4]
            action_time = csvdata[i][5]
            module_id = csvdata[i][6]
            section_id = csvdata[i][7]
            exercise_id = csvdata[i][8]
            exercise_name = csvdata[i][9]
            exercise_type = csvdata[i][10]
            next_ev = csvdata[i + 1]

            now = dt.strptime(action_time, "%Y-%m-%d %H:%M:%S")
            next_time = dt.strptime(next_ev[5], "%Y-%m-%d %H:%M:%S")
            time_diff = (next_time - now).total_seconds()

            end_time = start_time

        
            if start_time == 0:
                start_time = action_time

            if session_count == 0:
                writer.writerow("\n")
                writer.writerow([f'Session {session_count + 1}'])
                session_count += 1

            if user_id == next_ev[1]:
                if (time_diff > threshold): # Creating a new session
                    end_time = action_time
                    start = dt.strptime(start_time, "%Y-%m-%d %H:%M:%S")
                    end = dt.strptime(end_time, "%Y-%m-%d %H:%M:%S")
                    diff = writeTime(csvdata[i], start, end)
                    # diff = f'{(end - start).total_seconds()} seconds' if (end - start).total_seconds() > 0 else None 
                    if is_pe_exercise:
                        writer.writerow([user_id, book_id, ev_name, "Attempted to solve PE", start_time, end_time, diff, exercise_name, num_event])
                    else:
                        writer.writerow([user_id, book_id, ev_name, writeDesc(csvdata[i]), start_time, end_time, diff, exercise_name, num_event])
                    writer.writerow([f'User inactive for {GetTime(time_diff)}'])
                    writer.writerow("\n")
                    writer.writerow([f'Session {session_count + 1}'])
                    session_count += 1
                    start_time = 0
                    num_event = 1
                    is_pe_exercise = False
                else: # Retreive all events in one session
                    if user_id == next_ev[1]:
                        if ev_name == next_ev[3]: # Finds duplicate events within the same session
                            end_time = action_time
                            num_event += 1
                            continue
                        else:
                            if bundle_pe(csvdata[i], next_ev):
                                end_time = action_time
                                num_event += 1
                                is_pe_exercise = True
                                continue
                            elif bundle_ff(csvdata[i], next_ev):
                                end_time = action_time
                                num_event += 1
                                continue
                            else:
                                end_time = action_time                        
                                start = dt.strptime(start_time, "%Y-%m-%d %H:%M:%S")
                                end = dt.strptime(end_time, "%Y-%m-%d %H:%M:%S")
                                diff = writeTime(csvdata[i], start, end)
                                # diff = f'In slideshow for {(end - start).total_seconds()} seconds' if (end - start).total_seconds() > 0 else None 
                                if is_pe_exercise:
                                    writer.writerow([user_id, book_id, ev_name, "Attempted to solve PE", start_time, end_time, diff, exercise_name, num_event])
                                elif ev_name == 'window-focus':
                                    # if next_ev[3] == 'window-blur':
                                        # if (next_time - now).total_seconds() > 3:
                                    writer.writerow([user_id, book_id, ev_name, writeDesc(csvdata[i]), start_time, end_time, f'Reading time: {(next_time - now).total_seconds()} sec', exercise_name, num_event])

                                elif ev_name == 'window-blur' and next_ev[3] == 'window-focus':
                                    # if (next_time - now).total_seconds() > 3:
                                    writer.writerow([user_id, book_id, ev_name, writeDesc(csvdata[i]), start_time, end_time, f'Away time: {(next_time - now).total_seconds()} sec', exercise_name, num_event])
                                else:
                                    writer.writerow([user_id, book_id, ev_name, writeDesc(csvdata[i]), start_time, end_time, diff, exercise_name, num_event])
                                start_time = 0
                                num_event = 1
                                is_pe_exercise = False
                    else:
                        start_time = 0
                        session_count = 0
                        num_event = 1

            else:
                end_time = action_time                        
                start = dt.strptime(start_time, "%Y-%m-%d %H:%M:%S")
                end = dt.strptime(end_time, "%Y-%m-%d %H:%M:%S")
                diff = writeTime(csvdata[i], start, end)
                # diff = f'{(end - start).total_seconds()} seconds' if (end - start).total_seconds() > 0 else None 
                if is_pe_exercise:
                    writer.writerow([user_id, book_id, ev_name, "Attempted to solve PE", start_time, end_time, diff, exercise_name, num_event])
                elif ev_name == 'window-focus' and next_ev[3] == 'window-blur':
                    # if (next_time - now).total_seconds() > 3:
                    writer.writerow([user_id, book_id, ev_name, writeDesc(csvdata[i]), start_time, end_time, f'Reading time: {(next_time - now).total_seconds()} sec', exercise_name, num_event])
                elif ev_name == 'window-blur' and next_ev[3] == 'window-focus':
                    # if (next_time - now).total_seconds() > 3:
                    writer.writerow([user_id, book_id, ev_name, writeDesc(csvdata[i]), start_time, end_time, f'Away time: {(next_time - now).total_seconds()} sec', exercise_name, num_event])
                else:
                    writer.writerow([user_id, book_id, ev_name, writeDesc(csvdata[i]), start_time, end_time, diff, exercise_name, num_event])
                
                start_time = 0
                session_count = 0
                num_event = 1

abstract("cs4114_spring_2020")



# SQL commands for pulling Fall 2020 CS4114 data
# SELECT oui.id, oui.user_id, oui.inst_book_id, oui.name, oui.description, oui.action_time, oui.inst_chapter_module_id, exercise.inst_section_id, exercise.inst_exercise_id, ex.short_name, ex.ex_type
# FROM opendsa.odsa_user_interactions oui
# LEFT JOIN opendsa.inst_book_section_exercises exercise ON oui.inst_book_section_exercise_id = exercise.id
# LEFT JOIN opendsa.inst_exercises ex ON exercise.inst_exercise_id = ex.id
# WHERE oui.inst_book_id = 852 Order by action_time ASC;

