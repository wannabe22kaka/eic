cd /home/jongeun/hadoop-2.7.2/
./bin/hdfs namenode -format

Y

./sbin/start-all.sh
./sbin/mr-jobhistory-daemon.sh start historyserver
./sbin/yarn-daemon.sh start proxyserver

./bin/hdfs dfs -rm -r /user/jongeun/output

./bin/hdfs dfs -mkdir /user
./bin/hdfs dfs -mkdir /user/jongeun/
./bin/hdfs dfs -mkdir /user/jongeun/conf

./bin/hdfs dfs -put /home/jongeun/crawlers/crawler3/crawler3/newsnaver20160813.json /user/jongeun/conf/
./bin/yarn jar customjar/KorJsonWordcount.jar JsonKWordcount conf/newsnaver20160813.json output
