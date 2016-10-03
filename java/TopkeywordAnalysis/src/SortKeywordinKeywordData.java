import org.apache.hadoop.io.WritableComparable;
import org.apache.hadoop.io.WritableUtils;

import java.io.DataInput;
import java.io.DataOutput;
import java.io.IOException;

public class SortKeywordinKeywordData implements WritableComparable<SortKeywordinKeywordData>{
	
	private String  Keyword = new String();
	private Integer Sum 		= new Integer(0);
	
	public String getKeyword(){
		return Keyword;
	}
	
	public void   setKeyword(String _json){
		this.Keyword = _json;
	}
	
	public Integer getSum(){
		return Sum;
	}
	
	public void setSum(Integer _sum){
		this.Sum = _sum;
	}

	@Override
	public String toString(){
		return this.Keyword;
	}
	
	@Override
	public void readFields(DataInput in) throws IOException{
		Keyword = WritableUtils.readString(in);
	    Sum = in.readInt();
	}
	
	@Override
	public void write(DataOutput out) throws IOException{
		WritableUtils.writeString(out,Keyword);
		out.writeInt(Sum);
	}
	
	@Override
	public int compareTo(SortKeywordinKeywordData _data){
		int result = Sum.compareTo(_data.getSum());
		return result;
	}
}

