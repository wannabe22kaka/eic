cd /home/jongeun/hadoop-2.7.2/
./bin/hdfs dfs -rm -r /user
./bin/hdfs dfs -mkdir /user
./bin/hdfs dfs -mkdir /user/www-data
./bin/hdfs dfs -mkdir /user/www-data/topkeyword
./bin/yarn jar customjar/TopkeywordAnalysis.jar TopkeywordAnalysis /user/www-data/topkeyword $1 cindex,title $2
