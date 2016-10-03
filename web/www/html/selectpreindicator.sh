cd /home/jongeun/hadoop-2.7.2/
./bin/hdfs dfs -rm -r /user
./bin/hdfs dfs -mkdir /user
./bin/hdfs dfs -mkdir /user/www-data
./bin/hdfs dfs -mkdir /user/www-data/conf
./bin/yarn jar customjar/SelectPredndicator.jar Selectpreindicator /user/www-data/conf /user/www-data/output $1 $2
