




import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.mapreduce.Partitioner;

public class GroupSortKeywordinKeywordData extends Partitioner<SortKeywordinKeywordData, IntWritable> {

		@Override
		public int getPartition(SortKeywordinKeywordData key, IntWritable val, int numPartitions) {
			int hash = key.getKeyword().hashCode();
			int partition = hash % numPartitions;
			return partition;
		}
}

