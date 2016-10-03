cd /home/jongeun/hadoop-2.7.2/
echo  "./bin/hdfs dfs -put /home/jongeun/crawlers/crawler3/crawler3/newsnaver$1.json /user/www-data/conf"
"""
./bin/hdfs dfs -rm -r /user
./bin/hdfs dfs -mkdir /user
./bin/hdfs dfs -mkdir /user/www-data
./bin/hdfs dfs -mkdir /user/www-data/conf
./bin/hdfs dfs -put /home/jongeun/crawlers/crawler3/crawler3/newsnaver$1.json /user/www-data/conf
./bin/yarn jar customjar/KorJsonWordcount.jar JsonKWordcount /user/www-data/conf /user/www-data/output $1 title
"""
