import pandas as pd
import numpy as np

cs3Progress = pd.read_csv('CS3114 - 1330 Array based list summary exercises progresses (data structures and algorithms) 338909 .csv')
cs3Attempts = pd.read_csv('CS3114 - 1330 Array based list summary exrcises attempts (data structures and algorithms) 338909 .csv')
formallangProgress = pd.read_csv('CS4114 - 1319 DFA exercises progresses (Formal languages automata) 336045 .csv')
formallangAttempts = pd.read_csv('CS4114 - 1319 DFA exercises attempts (Formal languages automata) 336045 .csv')
pi_frames = pd.read_csv('pi_frames.csv')

cs3Unique = np.unique(list(cs3Progress['user_id'].unique()) + list(cs3Attempts['user_id'].unique()))
formallangUnique = np.unique(list(formallangProgress['user_id'].unique()) + list(formallangAttempts['user_id'].unique()))
unique = list(cs3Unique) + list(formallangUnique)

cs3Progress['user_id'] = cs3Progress['user_id'].astype('category').cat.codes
cs3Attempts['user_id'] = cs3Attempts['user_id'].astype('category').cat.codes
formallangProgress['user_id'] = formallangProgress['user_id'].astype('category').cat.codes
formallangAttempts['user_id'] = formallangAttempts['user_id'].astype('category').cat.codes
pi_frames['user_id'] = pi_frames['user_id'].astype('category').cat.codes

cs3Progress.to_csv('cs3114progress_anon.csv')
cs3Attempts.to_csv('cs3114attempts_anon.csv')
formallangProgress.to_csv('formallangprogress_anon.csv')
formallangAttempts.to_csv('formallangattempts_anon.csv')
pi_frames.to_csv('piframes_anon.csv')
