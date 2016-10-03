



import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.mapreduce.Partitioner;

public class GroupSortJsonDataPartitioner extends Partitioner<SortJsonData, IntWritable> {

		@Override
		public int getPartition(SortJsonData key, IntWritable val, int numPartitions) {
			int hash = key.getMorpheme().hashCode();
			int partition = hash % numPartitions;
			return partition;
		}
}

