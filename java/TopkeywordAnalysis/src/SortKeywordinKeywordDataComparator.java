

import org.apache.hadoop.io.WritableComparator;
import org.apache.hadoop.record.Buffer;

import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;

import java.io.IOException;

import org.apache.hadoop.io.DataInputBuffer;
import org.apache.hadoop.io.WritableComparable;

public class SortKeywordinKeywordDataComparator extends WritableComparator {
	public static DataInputBuffer buffer = new DataInputBuffer();
	public static SortKeywordinKeywordData    key1   = new SortKeywordinKeywordData();
	public static SortKeywordinKeywordData    key2   = new SortKeywordinKeywordData();
	protected SortKeywordinKeywordDataComparator(){
		super(SortKeywordinKeywordData.class,true);
	}

	@Override
	public int compare(WritableComparable w1, WritableComparable w2){
		SortKeywordinKeywordData d1 = (SortKeywordinKeywordData)w1;
		SortKeywordinKeywordData d2 = (SortKeywordinKeywordData)w2;
		

		//compare sum
		//내림 차순을 위해서 -1을 곱한다.
		int cmp = d1.getSum().compareTo(d2.getSum()) * -1;

	
		if (cmp != 0){
			return cmp;
		}
		
		return cmp;
		
	}
	

}
