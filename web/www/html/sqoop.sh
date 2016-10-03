cd /home/jongeun/sqoop-1.4.6.bin__hadoop-2.0.4-alpha
./bin/sqoop export --username hive --password 740412 --connect jdbc:mysql://localhost:3306/sorting_data --table $1sorting_table --staging-table $1sorting_tabletmp --clear-staging-table --input-fields-terminated-by \n --export-dir /user/jongeun/output/sort -m 1
