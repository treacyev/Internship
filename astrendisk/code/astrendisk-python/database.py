#!/usr/bin/python

import csv
import MySQLdb
import ConfigParser
from datetime import datetime
import os
from os import listdir
from os.path import isfile, join


		
#get values from INI file
config = ConfigParser.ConfigParser()
config.read('datafile.ini')

host = config.get('DB Connect', 'host')
user = config.get('DB Connect', 'user')
passwd = config.get('DB Connect', 'passwd')
db = config.get('DB Connect', 'db')
input_folder = config.get('FILES', 'input_folder')
output_filename = config.get('FILES', 'output_filename')



#Open database connection
db = MySQLdb.connect(host, user, passwd, db)
cursor = db.cursor()



#file output
fo = open(output_filename, "a")

			
			
#get all csv files in folder
mypath = input_folder
onlyfiles = [f for f in listdir(mypath) if isfile(join(mypath, f))]
for i in range(0, len(onlyfiles)):
	if (onlyfiles[i].find(".csv") != -1) and (onlyfiles[i].find(".bak") == -1):
		print onlyfiles[i]
		input_filename = onlyfiles[i]
		input_path = input_folder + input_filename

			
		date = str(datetime.now())
		fo.write(date + '\t\t')
		fo.write(input_path + '\t\t')



		#file opening
		correct = 2
		wrong = 2
		count = 1
		with open(input_path, 'r') as csvfile:
			readCSV = csv.reader(csvfile, delimiter=',')
			for row in readCSV:
				#print row
				#print len(row)
				
				if (len(row) == 26):
					if row[0] != 'Date':
						count = count + 1
						date = row[0]
						sha1 = row[1]
						engg = row[2]
						imphash = row[3]
						family = row[4]
						trendx = row[5]
						undetection = row[6]
						testday = row[7]
						intellitrap = row[8]
						mip = row[9]
						manydetect = row[10]
						newsmart = row[11]
						nosmart = row[12]
						reason = row[13]
						comment = row[14]
						packer = row[15]
						de4dot = row[16]
						cbaq = row[17]
						ms = row[18]
						nod32 = row[19]
						bitdefender = row[20]
						kaspersky = row[21]
						sophos = row[22]
						symantec = row[23]
						k7 = row[24]
						url = row[25]
						
						#prepare SQL query to INSERT a record into the database
						sql = "INSERT INTO monthlyreport(`Date`,`SHA1`,`Engineer`,`ImpHASH`,`Family`,`TrendX`,`Undetection Reason`,`On test day`,`Intellitrap`,`MIP`,`one_many_detection`,`new_smart`,`no_smart`,`reason_no_smart`,`comment_smartables`,`Packer/Compiler`,`DE4DOT`,`CBAQ rule`,`MS Detection`,`Nod32`,`Bitdefender`,`Kaspersky`,`Sophos`,`Symantec`,`K7AntiVirus`,`URL/Email`) \
								VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'\
								)" % (date, sha1, engg, imphash, family, trendx, undetection, testday, intellitrap, mip, manydetect, newsmart, nosmart, reason, comment, packer, de4dot, cbaq, ms, nod32, bitdefender, kaspersky, sophos, symantec, k7, url)									
						
						try:
							#Execute the SQL command
							cursor.execute(sql)
							#Commit your changes in the database
							db.commit()
							correct = 1
						except:
							#Rollback in case there is any error
							db.rollback()
							wrong = 0
							print "writing line " + str(count) + " failed"
				else:
					wrong = 0
							
					
		#file output
		if correct == 1 and wrong != 0:
			fo.write("Success\n\n")
			print "Success"
			os.rename(input_path, input_folder + "Success\\" + input_filename)
		else:
			fo.write("Failed\n\n")
			print "Failed"
			os.rename(input_path, input_folder + "Failed\\" + input_filename)
			
		
fo.close()
#disconnect from server
db.close()


